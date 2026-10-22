<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IclockController extends Controller
{
    public function cdata(Request $request)
    {
        // 1. WIRETAP: Logs exactly what the machine sends for debugging
        Log::info('Device Hit cdata:', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'body' => $request->getContent()
        ]);

        // 2. HEARTBEAT TRACKER: Keeps the device "Online" in the dashboard
        $sn = $request->query('SN');
        if ($sn) {
            Cache::put("zkteco_device_{$sn}", [
                'sn' => $sn,
                'ip' => $request->ip(),
                'last_seen' => now(),
            ], now()->addMinutes(10)); // Stays online for 10 mins to prevent flashing red

            $deviceList = Cache::get('zkteco_devices_list', []);
            if (!in_array($sn, $deviceList)) {
                $deviceList[] = $sn;
                Cache::forever('zkteco_devices_list', $deviceList);
            }
        }

        // 3. HANDSHAKE: Device checks if server is alive
        if ($request->isMethod('get')) {
            return response("OK", 200)->header('Content-Type', 'text/plain');
        }

        // 4. ATTENDANCE DATA: Handling live punches or bulk syncs
        if ($request->query('table') === 'ATTLOG') {
            $lines = preg_split('/\r\n|\r|\n/', trim($request->getContent()));
            $count = 0;

            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                $fields = preg_split('/\t+|\s{2,}/', trim($line));

                if (count($fields) >= 2) {
                    
                    // Creates a temporary employee if they don't exist
                    $employee = Employee::firstOrCreate(
                        ['biometric_id' => trim($fields[0])],
                        ['name' => 'New Employee #' . trim($fields[0])]
                    );

                    // --- ADD THIS UNHIDE LOGIC ---
                    if ($employee->is_hidden) {
                        $employee->is_hidden = false;
                        $employee->save();
                    }

                    // Saves the punch (firstOrCreate prevents duplicates)
                    $log = AttendanceLog::firstOrCreate(
                        ['employee_id' => $employee->id, 'punch_time' => Carbon::parse(trim($fields[1]))],
                        ['punch_type' => $fields[2] ?? 0]
                    );
                    
                    if ($log->wasRecentlyCreated) {
                        $count++;
                    }
                }
            }
            return response("OK: {$count}\n", 200)->header('Content-Type', 'text/plain');
        }

        // 5. USER SYNC: Handling employee names pushed from the machine
        if ($request->query('table') === 'USERINFO' || $request->query('table') === 'user') {
            $lines = preg_split('/\r\n|\r|\n/', trim($request->getContent()));
            $count = 0;

            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                
                preg_match('/PIN=([^\t]+)/i', $line, $pinMatch);
                preg_match('/Name=([^\t]+)/i', $line, $nameMatch);
                
                if (!empty($pinMatch[1])) {
                    $biometricId = trim($pinMatch[1]);
                    $name = !empty($nameMatch[1]) ? trim($nameMatch[1]) : 'New Employee #' . $biometricId;
                    
                    Employee::updateOrCreate(
                        ['biometric_id' => $biometricId],
                        ['name' => $name]
                    );
                    $count++;
                }
            }
            return response("OK: {$count}\n", 200)->header('Content-Type', 'text/plain');
        }

        // 6. DEFAULT CATCH-ALL
        return response("OK\n", 200)->header('Content-Type', 'text/plain');
    }
}