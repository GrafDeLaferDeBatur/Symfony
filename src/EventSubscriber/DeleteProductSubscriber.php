<?php

namespace App\EventSubscriber;

use App\Events\AddAttributesSessionEvent;
use App\Repository\ProductRepository;
use App\Service\Model\ProductModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class DeleteProductSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestStack $request,
//        private readonly ProductRepository $productRepository,
//        private readonly ProductModel $productModel,
    ){}
    public static function getSubscribedEvents(): array
    {
        return [
            AddAttributesSessionEvent::NAME => [
                ['deleteSessionProduct', -30],
            ],
        ];
    }

    public function deleteSessionProduct(AddAttributesSessionEvent $event): void
    {
        if(!$this->request->getSession()) {
            return;
        }
        if ($event->getState() === 'deleted') {
            if ($event->getProduct()->getId() === $this->request->getSession()->get('idProduct')) {
                $this->request->getSession()->remove('idProduct');
                $this->request->getSession()->remove('slugProduct');
            }

            $this->request->getSession()->getFlashBag()->add('success', 'The product was deleted successfully');
        }

    }
}