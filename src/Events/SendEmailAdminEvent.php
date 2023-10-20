<?php

namespace App\Events;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class SendEmailAdminEvent extends Event
{
    public const NAME = 'admin.send.email';

    protected Product $product;

    public function getProduct(): Product
    {
        return $this->product;
    }
    public function setProduct($product): void
    {
        $this->product = $product;
    }
}