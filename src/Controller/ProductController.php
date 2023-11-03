<?php

namespace App\Controller;

use App\Entity\Color;
use App\Entity\User;
use App\Form\Color\ColorType;
use App\Form\Object\ProductJson;
use App\Form\Object\SearchProduct;
use App\Form\Product\ProductJsonType;
use App\Form\Product\ProductType;
use App\Form\SearchProduct\SearchProductType;
use App\Message\FileMessage;
use App\ORM\Criteria\MyOwnSearchCriteriaProvider;
use App\Repository\ProductRepository;
use App\Service\ImageUploader;
use App\Service\JSONUploader;
use App\Service\Model\ColorModel;
use App\Service\Model\ProductModel;
use App\Service\User\AuthService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\MapColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class ProductController extends AbstractController
{
    public function __construct(
        ImageUploader $imageUploader,
        JSONUploader $jsonUploader,
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
    public function showAll(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $searchProduct = new SearchProduct();

        $formSearchProduct = $this->createForm(SearchProductType::class, $searchProduct);
        $formSearchProduct->handleRequest($request);

        $products = $productRepository->findByField($searchProduct);

        $pagination = $paginator->paginate(
            $products,
            $formSearchProduct->get('page')->getData() ?? 1,
            6
        );

        return $this->render('product/products.html.twig',[
            'items' => $products,
            'formSearchProduct' => $formSearchProduct,
            'user'=> $this->authService->getUser(),
            'pagination' => $pagination,
        ]);
    }

    public function delete(ProductModel $model, ImageUploader $imageUploader, ProductRepository $productRepository, ?string $slug): JsonResponse
    {
        $product = $productRepository->findOneBySlug($slug);
        $this->denyAccessUnlessGranted('delete', $product);
        if (!$product) {
            return new JsonResponse(['message' => 'Продукт не найден'], 404);
        }
        $model->delete($product);
        return new JsonResponse(['message' => 'Продукт успешно удален']);
    }
    public function giveTitlesAndDescriptions(ProductRepository $productRepository, ?string $given = null, ?string $side = null): JsonResponse
    {
//        dump($productRepository->giveTitlesAndDescriptionsJSON($given, $side));die;
        return new JsonResponse(
            $productRepository->giveTitlesAndDescriptionsJSON($given, $side),
        );
    }

    public function add(ProductModel $model, Request $request, ImageUploader $imageUploader, EntityManagerInterface $entityManager,?string $slug = null): Response
    {
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

    public function addJsonProduct(Request $request, JSONUploader $jsonUploader, MessageBusInterface $bus): Response
    {
        $locale = $request->get('_locale', 'en');

        $productJson = new ProductJson();
        $status = null;
        $form = $this->createForm(ProductJsonType::class, $productJson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $jsonFile = $form->get('jsonProduct')->getData();


            $status = 'Can`t download your file';

            if ($jsonFile) {
                $imageFileName = $jsonUploader->upload($jsonFile);
                $productJson->setPathToJsonProduct($imageFileName);

                $bus->dispatch(new FileMessage($imageFileName), [
                    new AmqpStamp('%env(MESSENGER_TRANSPORT_DSN)%', 0),
                ]);

                $status = 'File downloaded correct';
            }

            return $this->render('product/savejson.html.twig', [
                'form' => $form,
                'status' => $status,
            ]);
        }

        return $this->render('product/savejson.html.twig', [
            'form' => $form,
            'status' => $status,
        ]);
    }
    public function addColor(Request $request, ColorModel $colorModel): Response
    {
        $color = new Color();
        $form = $this->createForm(ColorType::class, $color);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $color->setColor($form->get('color')->getData());
            $colorModel->addColor($color);

            return $this->render('product/color.html.twig', [
                'form' => $form,
                'status' => 'Your color was added',
            ]);
        }

        return $this->render('product/color.html.twig', [
            'form' => $form,
            'status' => '',
        ]);
    }
    public function adminPanel(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $dataTableFactory->create()
            ->add('id', TextColumn::class,['searchable' => false])
            ->add('email', TextColumn::class,['searchable' => true])
            ->add('isActive', MapColumn::class, [
                'searchable' => false,
                'map' => [
                    '0' => 'unverified',
                    '1' => 'banned',
                    '2' => 'active',
                ]
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => User::class,
                'criteria' => [new MyOwnSearchCriteriaProvider()],
                'query' => function(QueryBuilder $queryBuilder){
                    $queryBuilder->select('user')->from(User::class,'user');
                    $queryBuilder->andWhere("user.roles NOT LIKE '%ROLE_ADMIN%'");
                },
            ])
            ->handleRequest($request);
//dump();
//dd($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/panel.html.twig', ['entity' => $table]);
    }

    public function test(ProductModel $model): Response
    {
        return new Response(json_encode('ROLE_ADMIN'));
    }
}
