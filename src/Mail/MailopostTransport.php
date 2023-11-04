<?php

namespace Xserg\LaravelMailopost\Mail;

use Illuminate\Support\Collection;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mailer\Header\MetadataHeader;
use Symfony\Component\Mailer\Header\TagHeader;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;
use Illuminate\Support\Facades\Http;

class MailopostTransport implements TransportInterface
{
    private string $key;
    private string $domain;
    /**
     * The collection of Symfony Messages.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $messages;

    /**
     * Create a new array transport instance.
     *
     * @return void
     */
    public function __construct(string $key, string $domain)
    {
        $this->key = $key;
        $this->domain = $domain;
        $this->messages = new Collection;
    }

    /**
     * {@inheritdoc}
     */
    public function send(RawMessage $message, Envelope $envelope = null): ?SentMessage
    {
        return $this->doSendApi(new SentMessage($message, $envelope ?? Envelope::create($message)), $message, $envelope);
        //return $this->messages[] = new SentMessage($message, $envelope ?? Envelope::create($message));
    }

    /**
     * Retrieve the collection of messages.
     *
     * @return \Illuminate\Support\Collection
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * Clear all of the messages from the local collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function flush()
    {
        return $this->messages = new Collection;
    }

    /**
     * Get the string representation of the transport.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'array';
    }

    protected function doSendApi(SentMessage $sentMessage, Email $email, Envelope $envelope)
    {
        $payload = $this->getPayload($email, $envelope);

        if (!empty($payload['body']['params']['template_id'])) {
          $url_path = '/v1/email/templates/' . $payload['body']['params']['template_id'] . '/messages';
        } else {
          $url_path = '/v1/email/messages';
        }
        $endpoint = $this->domain . $url_path;
        $response = Http::withHeaders($payload['headers'])->post($endpoint, $payload['body']);

        try {
            $statusCode = $response->getStatusCode();
            $result = $response->json();
        } catch (TransportExceptionInterface $e) {
            throw new HttpTransportException('Could not reach the remote server.', $response, 0, $e);
        }

        $sentMessage->setMessageId($result['id'] ?? '');
        return $sentMessage;
    }

    protected function getPayload(Email $email, Envelope $envelope): array
    {
        $headers = $email->getHeaders();
        foreach ($headers->all() as $name => $header) {
            if ($header instanceof MetadataHeader) {
                $params[$header->getKey()] = $header->getValue();
                continue;
            }
        }
        $payload =  [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->key,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json'
            ],
            'body' => [
                'to' => $params['email'],
                'params' => $params,
            ],
        ];

        if (empty($params['template_id'])) {
            $payload['body'] = [
              'from_email' => $envelope->getSender()->toString(),
              'to' => $params['email'],
              'subject' => $email->getSubject(),
              'text' =>  $email->getTextBody() ?? $email->getHtmlBody(),
              'html' =>  $email->getHtmlBody(),
            ];
        }
        return $payload;
    }

}
