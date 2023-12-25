<?php
namespace App\Queues;

use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\SendmailTransport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;

class SendEmail {

    public static function handle(array $payload) : bool
    {
        $transport = Transport::fromDsn(config('app.mailerDsn')); 
        // $transport = new GmailSmtpTransport('restapioauth2@gmail.com', 'qoxxppoqaxcjyqik');
        $mailer = new Mailer($transport); 
        $email = (new Email()) 
                ->from('restapioauth2@gmail.com')
                ->to($payload['to'])
                // ->cc($payload['cc'])
                // ->bcc($payload['bcc'])
                // ->priority(Email::PRIORITY_HIGHEST)
                ->subject($payload['subject'])
                ->text($payload['body']);
        try {
            $mailer->send($email);
            return true;
        } catch (TransportException $e) {
            print_r($e->getMessage());
            logger('error', $e->getMessage());
            return false;
        }
        
    }

}