<?php

namespace App\Mail;

use Illuminate\Support\Facades\Log;
use SendGrid;
use SendGrid\Mail\Mail;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mime\RawMessage;

class SendGridTransport implements TransportInterface
{
    protected $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
    {
        $email = new Mail();
        
        $symfonyMessage = MessageConverter::toEmail($message);
        
        $from = $symfonyMessage->getFrom()[0];
        $email->setFrom($from->getAddress(), $from->getName());
        
        foreach ($symfonyMessage->getTo() as $address) {
            $email->addTo($address->getAddress(), $address->getName() ?? '');
        }
        
        $email->setSubject($symfonyMessage->getSubject());
        $email->addContent("text/html", $symfonyMessage->getHtmlBody());

        $sendgrid = new SendGrid($this->key);
        
        try {
            $response = $sendgrid->send($email);
            return new SentMessage($message, $envelope ?? Envelope::create($message));
        } catch (\Exception $e) {
            Log::error('SendGrid Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}