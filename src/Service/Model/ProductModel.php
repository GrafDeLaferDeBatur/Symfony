<?php

namespace App\Service\Model;

use App\Entity\Product;
use App\Entity\User;
use App\Events\SendEmailAdminEvent;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\User\AuthService;
use App\Events\AddAttributesSessionEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class ProductModel
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly AuthService $authService,
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }


    public function createOrFind(?string $slug = null): Product
    {
        if($slug){
            $product = $this->productRepository->findOneBySlug($slug);
        }else{
            $product = new Product();
        }
        return $product;
    }

    public function update(Product $product): void
    {
        if ($product->getUser() === null) {
            $product->setUser($this->authService->getUser());
        }

        $event = new AddAttributesSessionEvent();
        $event->setProduct($product);
        $eventSendEmail = new SendEmailAdminEvent();
        $eventSendEmail->setProduct($product);

        if (!$product->getId()) {
            $event->setState('created');
        }else{
            $event->setState('updated');
        }

        $this->productRepository->save($product, true);

        try {
            $this->dispatcher->dispatch($eventSendEmail, $eventSendEmail::NAME);
            $this->dispatcher->dispatch($event, $event::NAME);
        }catch (\Exception){}


    }
    public function delete(Product $product): void
    {
        $event = new AddAttributesSessionEvent();
        $event->setProduct($product);
        $event->setState('deleted');

        $this->productRepository->remove($product, true);

        $this->dispatcher->dispatch($event, $event::NAME);
    }

}