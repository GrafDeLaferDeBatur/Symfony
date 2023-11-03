<?php

namespace App\Events;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class AddAttributesSessionEvent extends Event
{
    public const NAME = 'product.session.added';

    protected Product $product;
    protected ?string $state;

    public function getProduct(): Product
    {
        return $this->product;
    }
    public function setProduct($product): void
    {
        $this->product = $product;
    }
    public function getState(): ?string
    {
        return $this->state;
    }
    public function setState($state): void
    {
        $this->state = $state;
    }
}