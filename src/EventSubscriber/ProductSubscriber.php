<?php

namespace App\EventSubscriber;

use App\Controller\MailerController;
use App\Events\AddAttributesSessionEvent;
use App\Repository\ProductRepository;
use App\Service\Model\ProductModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ProductSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestStack $request,
    ){}
    public static function getSubscribedEvents(): array
    {
        return [
            AddAttributesSessionEvent::NAME => [
                ['updateSessionProduct', 0],
                ['createSessionProduct', 10],
                ['addSessionProduct', -10],
            ],
        ];
    }

    public function updateSessionProduct(AddAttributesSessionEvent $event): void
    {
        if(!$this->request->getSession()) {
            return;
        }
        $this->deleteSessionIdProduct();
        $this->deleteSessionSlugProduct();
        if ($event->getState() === 'updated') {
            $this->request->getSession()->getFlashBag()->add('success', 'The product was updated successfully');
        }
    }

    public function createSessionProduct(AddAttributesSessionEvent $event): void
    {
        if ($event->getState() === 'created' && $this->request->getSession()) {
            $this->request->getSession()->getFlashBag()->add('success', 'The product was created successfully');
        }else{
            return;
        }
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function addSessionProduct(AddAttributesSessionEvent $event): void
    {
        if(!$this->request->getSession()) {
            return;
        }
        if ($event->getState() === 'created' || $event->getState() === 'updated') {
            $this->setSessionIdProduct($event->getProduct()->getId());
            $this->setSessionSlugProduct($event->getProduct()->getSlug());
        }
    }

    public function setSessionIdProduct(int $id): void
    {
        $this->request->getSession()->set('idProduct',$id);
    }
    public function findSessionIdProduct(): ?string
    {
        return $this->request->getSession()->get('idProduct') ?? null;
    }
    public function deleteSessionIdProduct(): void
    {
        $this->request->getSession()->remove('idProduct');
    }
    public function setSessionSlugProduct(?string $slug): void
    {
        $this->request->getSession()->set('slugProduct',$slug);
    }
    public function findSessionSlugProduct(): ?string
    {
        return $this->request->getSession()->get('slugProduct') ?? null;
    }
    public function deleteSessionSlugProduct(): void
    {
        $this->request->getSession()->remove('slugProduct');
    }
}