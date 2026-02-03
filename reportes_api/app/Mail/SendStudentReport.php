<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendStudentReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Datos del estudiante
     *
     * @var array
     */
    public array $student;

    /**
     * PDF en base64
     *
     * @var string
     */
    public string $pdfBase64;

    /**
     * Nombre del archivo PDF
     *
     * @var string
     */
    public string $filename;

    /**
     * Crear una nueva instancia del mensaje.
     *
     * @param array $student
     * @param string $pdfBase64
     * @param string $filename
     */
    public function __construct(array $student, string $pdfBase64, string $filename)
    {
        $this->student = $student;
        $this->pdfBase64 = $pdfBase64;
        $this->filename = $filename;
    }

    /**
     * Obtener el sobre del mensaje.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Constancia de Notas - {$this->student['full_name']}",
        );
    }

    /**
     * Obtener la definición del contenido del mensaje.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.student-report',
        );
    }

    /**
     * Obtener los archivos adjuntos del mensaje.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => base64_decode($this->pdfBase64), $this->filename)
                ->withMime('application/pdf'),
        ];
    }
}
