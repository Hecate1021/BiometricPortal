<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepartmentDtrReport extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;
    public $deptName;
    public $month;
    public $year;

    public function __construct($pdfContent, $deptName, $month, $year)
    {
        $this->pdfContent = $pdfContent;
        $this->deptName = $deptName;
        $this->month = $month;
        $this->year = $year;
    }

    public function build()
    {
        $monthName = date('F', mktime(0, 0, 0, $this->month, 1));
        $fileName = "DTR_{$this->deptName}_{$monthName}_{$this->year}.pdf";

        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->replyTo(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject("Official DTR Report - {$this->deptName} ({$monthName} {$this->year})")
                    ->view('emails.department_dtr')
                    ->attachData($this->pdfContent, $fileName, [
                        'mime' => 'application/pdf',
                    ]);
    }
}
