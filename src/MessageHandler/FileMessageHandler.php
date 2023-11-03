<?php

namespace App\MessageHandler;

use App\Entity\Product;
use App\Entity\ProductAttribute;
use App\Message\FileMessage;
use App\Repository\CategoryRepository;
use App\Repository\ColorRepository;
use App\Repository\UserRepository;
use App\Service\Model\ProductModel;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Command\StopWorkersCommand;
use Symfony\Component\Messenger\Exception\StopWorkerException;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsMessageHandler]
class FileMessageHandler
{
    private Filesystem $filesystem;
    public function __construct(
        private readonly string $targetDirectory,
        private readonly CategoryRepository $categoryRepository,
        private readonly ColorRepository $colorRepository,
        private readonly UserRepository $userRepository,
        private readonly ProductModel $productModel,
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $entityManager,
        private readonly Filesystem       $_filesystem,
        private readonly LoggerInterface $myChannelLogger,
    ){
        $this->filesystem = $this->_filesystem;
    }

    /**
     * @throws Exception
     */
    public function __invoke(FileMessage $message): void
    {
        try {
            $isUncorrectJson = false;

            $encoder = [new JsonEncoder()];
            $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);
            $normalizer = [new ArrayDenormalizer(), new ObjectNormalizer(null, null, null, $extractor)];
            $serializer = new Serializer($normalizer, $encoder);

            $productList = file_get_contents($this->targetDirectory . $message->getFile());

            $productSerials = $serializer->deserialize($productList, 'App\Form\Object\ProductSerial[]', 'json');

            $this->entityManager->getConnection()->beginTransaction();
            foreach ($productSerials as $productSerial) {

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
                $product->setDimensions(match ($productSerial->getDimensions()) {
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

                $errors = $this->validator->validate($product, null, ['Default', 'create_product_console']);

                $this->productModel->update($product);
                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        $isUncorrectJson = true;
                        $this->myChannelLogger->info($message->getFile().': '.$error->getMessage());
                    }
                }
            }
            if($isUncorrectJson){
                $this->entityManager->getConnection()->rollBack();
            }else{
                $this->entityManager->getConnection()->commit();
            }
        }catch (\Exception $ex){
            $this->myChannelLogger->debug($ex);
        }
        if ($this->filesystem->exists($this->targetDirectory.'/'.$message->getFile())) {
            unlink($this->targetDirectory.'/'.$message->getFile());
        }
    }

    public function remove(string $fileName): void
    {
        if ($this->filesystem->exists($this->targetDirectory.'/'.$fileName)) {
            unlink($this->targetDirectory.'/'.$fileName);
        }
    }
}