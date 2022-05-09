<?php

namespace App\Service;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class Mailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    { 
        $this->mailer=$mailer;


    }
    public function sendEmail($em,$token){
        $email = (new TemplatedEmail())
        ->from('lup634771@gmail.com')
        ->to(new Address($em))
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Please Confirm your Email')
        ->text('')
        ->htmlTemplate('email/activation.html.twig')
        ->context([ 'token' =>$token ]);
        $this->mailer->send($email);

    }
    public function send($email,$code){
        $email = (new TemplatedEmail())
        ->from('lup634771@gmail.com')
        ->to(new Address($email))
        ->subject('Reset Password')
        ->text('')
        ->htmlTemplate('email/code.html.twig')
        ->context(['code' =>$code]);
        $this->mailer->send($email);
    }
}
