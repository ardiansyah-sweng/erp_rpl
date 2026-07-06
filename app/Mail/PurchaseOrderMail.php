<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class PurchaseOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; // Diubah menjadi $data agar sesuai dengan email.blade.php
    public $pdfContent;

    public function __construct($data, $pdfContent)
    {
        $this->data = $data;
        $this->pdfContent = $pdfContent;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Dokumen Purchase Order: ' . $this->data['header']['po_number'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'purchase_orders.email',
            with: [
                'data' => $this->data,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'PO-' . $this->data['header']['po_number'] . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}