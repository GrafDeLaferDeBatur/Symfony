<?php

namespace App\Command;

use App\Entity\Product;
use App\Entity\ProductAttribute;
use App\Repository\CategoryRepository;
use App\Repository\ColorRepository;
use App\Repository\ProductAttributeRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\Model\ProductModel;
use App\Service\User\AuthService;
use App\Validators\ValidConsoleProduct;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:create-product',
    description: 'Creates a new product'
)]
class ProductCreateCommand extends Command
{
    private ?string $title = null;
    private ?string $descr = null;
    private ?string $price = null;
    private ?string $amount = null;
    private ?string $hue = null;
    private ?string $dimensions = null;
    private ?string $weight = null;
    private ?string $length = null;
    private ?string $height = null;
    private ?string $category = null;
    private ?string $width = null;
    private ?string $email = null;


    public function __construct(
        private readonly ProductModel               $productModel,
        private readonly CategoryRepository         $categoryRepository,
        private readonly ColorRepository            $colorRepository,
        private readonly UserRepository             $userRepository,
        private readonly ValidatorInterface         $trueValidator,

    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);
        $output->writeln('Product Creator(in web version you can add product image)');
        $output->writeln('The * symbol means that the field is required.');
        $this->email = $ui->ask('*Email creator');
        $this->title = $ui->ask('*Product title(4+ chars)');
        $this->descr = $ui->ask('*Product description(10+ chars)');
        $this->price = $ui->ask('*Product price(> 0)');
        $this->amount = $ui->ask('*Product amount(> 0)');
        $this->hue = $ui->choice('Product color', ['Brown', 'Green', 'BlueWhite', '']);
        $this->dimensions = $ui->choice('Product dimensions', ['Small', 'Medium', 'Large', '']);
        $this->weight = $ui->ask('Product weight(> 0)');
        $this->width = $ui->ask('Product width(> 0)');
        $this->length = $ui->ask('Product length(> 0)');
        $this->height = $ui->ask('Product height(> 0)');
        $this->category = $ui->choice('*Product category', ['Food', 'NotFood', 'Milk', 'Meat', 'Stone']);

        $product = new Product();
        $productAttribute = new ProductAttribute();

        $productAttribute->setWeight($this->weight);
        $productAttribute->setWidth($this->width);
        $productAttribute->setLength($this->length);
        $productAttribute->setHeight($this->height);

        $product->setTitle($this->title);
        $product->setDescr($this->descr);
        $product->setPrice($this->price);
        $product->setAmount($this->amount);
        $product->setDimensions(match ($this->dimensions) {
            $product::DIMENSIONS_SMALL => '1',
            $product::DIMENSIONS_MEDIUM => '2',
            $product::DIMENSIONS_LARGE => '3',
            default => null,
        });
        $product->setCategory($this->categoryRepository->findOneByName($this->category));
        $product->setColor($this->colorRepository->findOneByColor($this->hue));
        $product->setUser($this->userRepository->findOneByEmail($this->email));

        $productAttribute->setProduct($product);

        $errors = $this->trueValidator->validate($product, null, ['create_product_console']);
        $output->writeln('Count error:' . count($errors));
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $output->writeln($error->getMessage());
                $output->writeln($error->getPropertyPath());
            }
            return Command::INVALID;
        }
        $this->productModel->update($product);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
    }
}