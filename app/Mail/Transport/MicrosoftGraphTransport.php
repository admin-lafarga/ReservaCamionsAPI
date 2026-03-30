<?php

namespace App\Mail\Transport;

use App\Services\GraphMailService;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class MicrosoftGraphTransport implements TransportInterface
{
    protected GraphMailService $graphService;

    public function __construct(GraphMailService $graphService)
    {
        $this->graphService = $graphService;
    }

    public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
    {
        $email = $message instanceof Email ? $message : null;

        if (!$email) {
            throw new \RuntimeException('MicrosoftGraphTransport solo soporta instancias de Email.');
        }

        // Extraer remitente
        $from = $envelope?->getSender()?->getAddress()
            ?? $email->getFrom()[0]?->getAddress()
            ?? config('mail.from.address');

        // Extraer destinatarios
        $to = $this->extractAddresses($email->getTo());
        $cc = $this->extractAddresses($email->getCc());
        $bcc = $this->extractAddresses($email->getBcc());

        // Extraer asunto y cuerpo
        $subject = $email->getSubject() ?? '(Sin asunto)';
        $htmlBody = $email->getHtmlBody() ?? $email->getTextBody() ?? '';

        // Extraer adjuntos
        $attachments = [];
        foreach ($email->getAttachments() as $attachment) {
            $attachments[] = [
                'name'        => $attachment->getFilename() ?? 'attachment',
                'contentType' => $attachment->getContentType() ?? 'application/octet-stream',
                'content'     => base64_encode($attachment->getBody()),
            ];
        }

        // Enviar vía Graph API
        $this->graphService->sendMail(
            from: $from,
            to: $to,
            subject: $subject,
            htmlBody: $htmlBody,
            cc: $cc,
            bcc: $bcc,
            attachments: $attachments
        );

        return new SentMessage($message, $envelope ?? Envelope::create($message));
    }

    /**
     * Extrae direcciones de email del formato Symfony Address.
     *
     * @param Address[] $addresses
     * @return array
     */
    protected function extractAddresses(array $addresses): array
    {
        $result = [];
        foreach ($addresses as $address) {
            $name = $address->getName();
            if ($name) {
                $result[$address->getAddress()] = $name;
            } else {
                $result[] = $address->getAddress();
            }
        }
        return $result;
    }

    public function __toString(): string
    {
        return 'microsoft-graph';
    }
}
