<?php

namespace App\Service\Model;

use App\Entity\Color;
use App\Entity\Product;
use App\Entity\User;
use App\Events\SendEmailAdminEvent;
use App\Repository\ColorRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\User\AuthService;
use App\Events\AddAttributesSessionEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class ColorModel
{
    public function __construct(
        private readonly colorRepository $colorRepository,
    ) {
    }


    public function addColor(Color $color): void
    {
        $this->colorRepository->save($color, true);
    }

}