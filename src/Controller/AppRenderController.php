<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use App\Service\RedisService;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class AppRenderController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $request,
//        private readonly RedisService $redisService,
        private readonly TagAwareCacheInterface $myCachePool,
    ){}
    public function renderHeader(ProductRepository $productRepository): Response
    {
        return $this->render('layout/header.html.twig', [
            'countItems' => $productRepository->countProductItems(),
            'idProduct' => $this->request->getSession()->get('idProduct') ?? null,
            'slugProduct' => $this->request->getSession()->get('slugProduct') ?? null,
//            'productSlug' => $cacheItem->get(),
//            'productSlug' => $this->redisService->getClient()->get('product.slug'),
            'productSlug' => $this->myCachePool->getItem('product.slug')->get(),
        ]);
    }
}