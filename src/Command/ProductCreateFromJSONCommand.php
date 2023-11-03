<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Color;
use App\Entity\Product;
use App\Entity\ProductAttribute;
use App\Entity\User;
use App\Form\Object\ProductSerial;
use App\Repository\CategoryRepository;
use App\Repository\ColorRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\Model\ProductModel;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:create-json-product',
    description: 'Creates a new product from JSON'
)]
class ProductCreateFromJSONCommand extends Command
{
//    private readonly Filesystem $filesystem;
    public function __construct(
        private readonly string $targetDirectory,
        private readonly CategoryRepository $categoryRepository,
        private readonly ColorRepository $colorRepository,
        private readonly UserRepository $userRepository,
        private readonly ProductModel $productModel,
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $entityManager,
//        private readonly Finder $finder,
//        private readonly Filesystem $_filesystem,
    )
    {
        parent::__construct();
//        $this->filesystem = $this->_filesystem;
    }

    /**
     * @throws ExceptionInterface
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $encoder = [new JsonEncoder()];
        $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);
        $normalizer = [new ArrayDenormalizer(), new ObjectNormalizer(null, null, null, $extractor)];
        $serializer = new Serializer($normalizer, $encoder);

        $productList = file_get_contents($this->targetDirectory."product_1.json");

        $productSerials = $serializer->deserialize($productList, 'App\Form\Object\ProductSerial[]' ,'json');

        $this->entityManager->getConnection()->beginTransaction();
        foreach ($productSerials as $productSerial){

            $productAttribute = new ProductAttribute();

            $productAttribute->setWeight((int)$productSerial->getWeight());
            $productAttribute->setLength((int)$productSerial->getLength());
            $productAttribute->setWidth((int)$productSerial->getWidth());
            $productAttribute->setHeight((int)$productSerial->getHeight());

            $product = new Product();

            $product->setTitle($productSerial->getTitle());
            $product->setDescr($productSerial->getDescr());
            $product->setPrice((int)$productSerial->getPrice());
            $product->setAmount((int)$productSerial->getAmount());
            $product->setDimensions(match ($productSerial->getDimensions()){
                'Small' => Product::DIMENSIONS_SMALL,
                'Medium' => Product::DIMENSIONS_MEDIUM,
                'Large' => Product::DIMENSIONS_LARGE,
                '' => null
            });
            $product->setUser($this->userRepository->findOneByEmail($productSerial->getUser()));
            $product->setColor($this->colorRepository->findOneByColor($productSerial->getColor()));
            $product->setCategory($this->categoryRepository->findOneByName($productSerial->getCategory()));
            $product->setProductAttribute($productAttribute);

            $productAttribute->setProduct($product);

            $errors = $this->validator->validate($product, null, ['Default' , 'create_product_console']);
            $output->writeln('Count error:' . count($errors));

            try {
                $this->productModel->update($product);
            }catch (\Exception $ex) {
                $this->entityManager->getConnection()->rollBack();
                throw $ex;
            }
        }
        $this->entityManager->commit();
        return Command::SUCCESS;
    }
    protected function configure(): void
    {
    }
}