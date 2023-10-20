<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Telephone;
use App\Form\Object\SearchProduct;
use App\Form\Product\ProductType;
use App\Form\SearchProduct\SearchProductType;
use App\Repository\ProductRepository;
use App\Service\ImageUploader;
use App\Service\Model\ProductModel;
use App\Service\User\AuthService;
use App\Events\AddAttributesSessionEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ProductController extends AbstractController
{
    public function __construct(
        ImageUploader $imageUploader,
        private readonly AuthService $authService,
    ){}
    public function show(ProductRepository $productRepository, ?string $slug = null): Response
    {
        $product = $productRepository->findOneBySlug($slug);

        return $this->render('product/productslug.html.twig', [
            'item' => $product,
            'user'=> $this->authService->getUser(),
        ]);
    }
    public function showAll(ProductRepository $productRepository, Request $request, EventDispatcherInterface $EventDispatcher): Response
    {
        $searchProduct = new SearchProduct();

        $formSearchProduct = $this->createForm(SearchProductType::class, $searchProduct);
        $formSearchProduct->handleRequest($request);

        $products = $productRepository->findByField($searchProduct);
        return $this->render('product/products.html.twig',[
            'items' => $products,
            'formSearchProduct' => $formSearchProduct,
            'user'=> $this->authService->getUser(),
        ]);
    }

    public function delete(ProductModel $model, ImageUploader $imageUploader, ProductRepository $productRepository, ?string $slug): RedirectResponse
    {
        $product = $productRepository->findOneBySlug($slug);
        $this->denyAccessUnlessGranted('delete', $product);
        if (!$product) {
            throw $this->createNotFoundException('The product does not exist');
        }
        $model->delete($product);
        return $this->redirectToRoute('showProducts');

    }

    public function add(ProductModel $model, Request $request, ImageUploader $imageUploader, EntityManagerInterface $entityManager,?string $slug = null): Response
    {

        // dummy code - add some example tags to the task
        // (otherwise, the template will render an empty list of tags)
//        $phone1 = new Telephone();
//        $phone1->setPhoneNumber('+79963855574');
//        $task->getTags()->add($tag1);
//        $phone2 = new Telephone();
//        $phone2->setPhoneNumber('+79963855574');
//        $task->getTags()->add($tag2);


        $product = $model->createOrFind($slug);

        if($product->getId()){
            $this->denyAccessUnlessGranted('edit', $product);
        }

        if (!$product) {
            throw $this->createNotFoundException('The product does not exist');
        }

        $originalPhoneNumbers = new ArrayCollection();

        foreach($product->getPhoneNumber() as $phoneNumber){
            $originalPhoneNumbers->add($phoneNumber);
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($originalPhoneNumbers as $phoneNumber){
                if(false === $product->getPhoneNumber()->contains($phoneNumber)){
                    $phoneNumber->getProducts()->removeElement($product);
                    $entityManager->persist($product);
                    $entityManager->flush();
                }
            }

            $imageFile = $form->get('imageName')->getData();
            if ($imageFile) {
                $imageFileName = $imageUploader->upload($imageFile);
                $product->setImageName($imageFileName);
            }

            $model->update($product);

            return $this->redirectToRoute('editProduct',['slug' => $product->getSlug()]);
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form,
            'image' => $product->getImageName(),
        ]);
    }

//    public function test()
//    {
//        $productsList = array('qwe','qwe','qwe','qwe',);
//        foreach($products = $productsList as $k => $pr){
//            dump($products[$k]);
//        }
//        return new Response();
//    }
}
