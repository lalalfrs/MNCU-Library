<?php

namespace App\Mail;

use App\Models\Pengguna;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Inisialisasi mailable reset kata sandi baru.
     */
    public function __construct(
        public Pengguna $pengguna,
        public string $newPassword,
    ) {}

    /**
     * Dapatkan amplop email dengan subjek resmi perpustakaan.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kata Sandi Baru Akun Perpustakaan MNC University',
        );
    }

    /**
     * Dapatkan definisi konten tampilan HTML email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset_password',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
