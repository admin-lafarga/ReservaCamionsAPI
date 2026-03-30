<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GraphMailService
{
    protected string $tenantId;
    protected string $clientId;
    protected string $clientSecret;
    protected Client $httpClient;

    public function __construct()
    {
        $this->tenantId     = config('services.microsoft_graph.tenant_id');
        $this->clientId     = config('services.microsoft_graph.client_id');
        $this->clientSecret = config('services.microsoft_graph.client_secret');
        $this->httpClient   = new Client();
    }

    /**
     * Obtiene un access token usando client_credentials grant.
     */
    public function getAccessToken(): string
    {
        $url = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";

        $response = $this->httpClient->post($url, [
            'form_params' => [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope'         => 'https://graph.microsoft.com/.default',
                'grant_type'    => 'client_credentials',
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return $body['access_token'];
    }

    /**
     * Envía un email a través de Microsoft Graph API.
     *
     * @param string      $from        Dirección del remitente (debe ser buzón del tenant)
     * @param array       $to          Array de destinatarios ['email' => 'name'] o ['email']
     * @param string      $subject     Asunto del email
     * @param string      $htmlBody    Cuerpo HTML del email
     * @param array       $cc          Destinatarios en CC (mismo formato que $to)
     * @param array       $bcc         Destinatarios en BCC (mismo formato que $to)
     * @param array       $attachments Adjuntos [['name' => 'file.pdf', 'content' => base64, 'contentType' => 'application/pdf']]
     */
    public function sendMail(
        string $from,
        array  $to,
        string $subject,
        string $htmlBody,
        array  $cc = [],
        array  $bcc = [],
        array  $attachments = []
    ): void {
        $token = $this->getAccessToken();

        $toRecipients = $this->formatRecipients($to);
        $ccRecipients = $this->formatRecipients($cc);
        $bccRecipients = $this->formatRecipients($bcc);

        $message = [
            'message' => [
                'subject' => $subject,
                'body'    => [
                    'contentType' => 'HTML',
                    'content'     => $htmlBody,
                ],
                'toRecipients'  => $toRecipients,
                'ccRecipients'  => $ccRecipients,
                'bccRecipients' => $bccRecipients,
            ],
            'saveToSentItems' => true,
        ];

        if (!empty($attachments)) {
            $message['message']['attachments'] = array_map(function ($att) {
                return [
                    '@odata.type'  => '#microsoft.graph.fileAttachment',
                    'name'         => $att['name'],
                    'contentType'  => $att['contentType'] ?? 'application/octet-stream',
                    'contentBytes' => $att['content'], // Ya debe venir en base64
                ];
            }, $attachments);
        }

        $url = "https://graph.microsoft.com/v1.0/users/{$from}/sendMail";

        $response = $this->httpClient->post($url, [
            'headers' => [
                'Authorization' => "Bearer {$token}",
                'Content-Type'  => 'application/json',
            ],
            'json' => $message,
        ]);

        Log::info('Graph API sendMail response', [
            'status' => $response->getStatusCode(),
            'from'   => $from,
            'to'     => $to,
        ]);
    }

    /**
     * Convierte un array de emails al formato de Graph API recipients.
     */
    protected function formatRecipients(array $recipients): array
    {
        $formatted = [];

        foreach ($recipients as $key => $value) {
            if (is_string($key)) {
                // Formato ['email' => 'name']
                $formatted[] = [
                    'emailAddress' => [
                        'address' => $key,
                        'name'    => $value,
                    ],
                ];
            } else {
                // Formato ['email']
                $formatted[] = [
                    'emailAddress' => [
                        'address' => $value,
                    ],
                ];
            }
        }

        return $formatted;
    }
}
