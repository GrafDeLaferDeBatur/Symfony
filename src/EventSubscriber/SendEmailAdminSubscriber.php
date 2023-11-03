<?php

namespace App\EventSubscriber;

use App\Controller\MailerController;
use App\Events\AddAttributesSessionEvent;
use App\Events\SendEmailAdminEvent;
use App\Repository\ProductRepository;
use App\Service\Model\ProductModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class SendEmailAdminSubscriber implements EventSubscriberInterface
{
    private MailerController $mailerController;
    public function __construct(
        private readonly MailerController $_mailerController,
    ){
        $this->mailerController = $this->_mailerController;
    }
    public static function getSubscribedEvents(): array
    {
        return [
            SendEmailAdminEvent::NAME => [
                ['sendEmailAdmin', 0],
            ],
        ];
    }

    public function sendEmailAdmin(SendEmailAdminEvent $event): void
    {
        $this->mailerController->sendEmail($event->getProduct());
    }
}