<?php

namespace App\Jobs;

use App\Mail\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;
    protected $employee;
    protected $salaryDetails;
    protected $pdfPath;

    /**
     * Create a new job instance.
     */
    public function __construct($employee, $salaryDetails, $pdfPath)
    {
        $this->employee = $employee;
        $this->salaryDetails = $salaryDetails;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $email = new SendMail($this->employee, $this->salaryDetails, $this->pdfPath);
            Mail::to($this->employee['email'])->send($email);
        } finally {
            if (file_exists($this->pdfPath)) {
                unlink($this->pdfPath);
            }
        }
    }
}
