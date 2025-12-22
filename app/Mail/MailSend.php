<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailSend extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $attachmentPath;
    public $attachmentName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $attachmentPath = null, $attachmentName = null)
    {
        $this->details = $details;
        $this->attachmentPath = $attachmentPath;
        $this->attachmentName = $attachmentName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject($this->details['subject'])->view('backend.mail.user-mail-send');
        
        // Attach file if provided (PDF or Excel)
        if ($this->attachmentPath && file_exists($this->attachmentPath)) {
            $extension = pathinfo($this->attachmentPath, PATHINFO_EXTENSION);
            $mimeType = $extension === 'xlsx' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'application/pdf';
            $defaultName = $extension === 'xlsx' ? 'statement.xlsx' : 'statement.pdf';
            
            $mail->attach($this->attachmentPath, [
                'as' => $this->attachmentName ?? $defaultName,
                'mime' => $mimeType,
            ]);
        }
        
        return $mail;
    }
}
