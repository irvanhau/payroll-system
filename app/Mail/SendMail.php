<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $salaryDetails;
    public $pdfPath;
    /**
     * Create a new message instance.
     */
    public function __construct($employee, $salaryDetails, $pdfPath)
    {
        $this->employee = $employee;
        $this->salaryDetails = $salaryDetails;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Slip Gaji ' . $this->employee['name'] . ' - ' . $this->salaryDetails['month_year'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.emails.send_emails',
            with: [
                'employee' => $this->employee,
                'salaryDetails' => $this->salaryDetails,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromUrl($this->pdfPath)
                ->as('slip_gaji_' . $this->employee['name'] . '_' . $this->salaryDetails['month_year'] . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
