<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Model\ProductModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AppRenderController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $request,
    ){}
    public function renderHeader(ProductRepository $productRepository): Response
    {
        return $this->render('layout/header.html.twig', [
            'countItems' => $productRepository->countProductItems(),
            'idProduct' => $this->request->getSession()->get('idProduct') ?? null,
            'slugProduct' => $this->request->getSession()->get('slugProduct') ?? null,
        ]);
    }
}