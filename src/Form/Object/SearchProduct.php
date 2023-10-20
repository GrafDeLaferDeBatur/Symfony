<?php

namespace App\Form\Object;


use App\Entity\Category;
use App\Entity\Color;

class SearchProduct
{
    private ?int $id = null;
    private ?string $substring = null;
    private ?string $title = null;
    private ?string $descr = null;
    private ?int $min_price = null;
    private ?int $max_price = null;
    private ?Category $category = null;
    private ?Color $color = null;
    private ?int $min_weight = null;
    private ?int $max_weight = null;
    private ?string $sort = null;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getSubstring(): ?string
    {
        return $this->substring;
    }
    public function setSubstring(?string $substring): ?string
    {
        $this->substring = $substring;
        return $this->substring;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(?string $title): ?string
    {
        $this->title = $title;
        return $this->title;
    }
    public function getDescr(): ?string
    {
        return $this->descr;
    }
    public function setDescr(?string $descr): ?string
    {
        $this->descr = $descr;
        return $this->descr;
    }
    public function getSort(): ?string
    {
        return $this->sort;
    }
    public function setSort(?string $sort): ?string
    {
        $this->sort = $sort;

        return $this->sort;
    }
    public function getMinPrice(): ?int
    {
        return $this->min_price;
    }

    public function setMinPrice(?int $min_price): static
    {
        $this->min_price = $min_price;

        return $this;
    }
    ///////////////////////////////////////
    public function getMaxPrice(): ?int
    {
        return $this->max_price;
    }

    public function setMaxPrice(?int $max_price): static
    {
        $this->max_price = $max_price;

        return $this;
    }
   ////////////////////////////////
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
   ////////////////////////////////
    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): static
    {
        $this->color = $color;

        return $this;
    }
   ////////////////////////////////
    public function getMinWeight(): ?int
    {
        return $this->min_weight;
    }

    public function setMinWeight(?int $min_weight): static
    {
        $this->min_weight = $min_weight;

        return $this;
    }
   ////////////////////////////////
    public function getMaxWeight(): ?int
    {
        return $this->max_weight;
    }

    public function setMaxWeight(?int $max_weight): static
    {
        $this->max_weight = $max_weight;

        return $this;
    }
   ////////////////////////////////

}