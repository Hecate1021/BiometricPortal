<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\DepartmentDtrReport;

class GenerateAndSendDtrPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $html;
    public $email;
    public $deptName;
    public $month;
    public $year;

    public $timeout = 120; // Allow 2 minutes per individual department

    public function __construct($html, $email, $deptName, $month, $year)
    {
        $this->html = $html;
        $this->email = $email;
        $this->deptName = $deptName;
        $this->month = $month;
        $this->year = $year;
    }

    public function handle()
    {
        // 1. Call Doczilla API in the background
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('API2PDF_KEY'),
            'Content-Type'  => 'application/json'
        ])->post('https://api.doczilla.app/v1/pdf', [
            'page' => [ 'html' => base64_encode($this->html) ],
            'pdf' => [
                'format' => 'legal',
                'printBackground' => true,
                'margin' => ['top' => '10mm', 'right' => '10mm', 'bottom' => '10mm', 'left' => '10mm']
            ]
        ]);

        if ($response->failed()) {
            Log::error("Job Failed: Doczilla API rejected " . $this->deptName);
            return;
        }

        // 2. Send the Email
        $pdfContent = $response->body();
        Mail::to($this->email)->send(new DepartmentDtrReport($pdfContent, $this->deptName, $this->month, $this->year));
    }
}
