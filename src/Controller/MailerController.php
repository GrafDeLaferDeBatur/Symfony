<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerController extends AbstractController
{
    private MailerInterface $mailer;
    public function __construct(
        private readonly MailerInterface $_mailer,
    ){
        $this->mailer = $_mailer;
    }
    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(Product $product)
    {
        $email = (new TemplatedEmail())
            ->from('alexeybaturin46@gmail.com')
            ->to('alexeybaturin46@gmail.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('layout/email/email.html.twig')
            ->textTemplate('layout/email/email.html.twig')
            ->context([
                'product' => $product
            ])
        ;

        $this->mailer->send($email);

        return new Response(urlencode('eaaetbkazyfouuyz'));

    }
}