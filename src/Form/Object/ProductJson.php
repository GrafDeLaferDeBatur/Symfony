<?php

namespace App\Form\Object;

class ProductJson
{
//    private jsonProduct;
    private ?string $pathToJsonProduct = null;

    public function setPathToJsonProduct(?string $pathToJsonProduct): void
    {
        $this->pathToJsonProduct = $pathToJsonProduct;
    }

    public function getPathToJsonProduct(): ?string
    {
        return $this->pathToJsonProduct;
    }
}