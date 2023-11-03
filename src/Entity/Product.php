<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    CONST DIMENSIONS_SMALL = 1;

    CONST DIMENSIONS_MEDIUM = 2;

    CONST DIMENSIONS_LARGE = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $descr = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Color $color = null;

    #[ORM\OneToOne(inversedBy: 'product', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?ProductAttribute $productAttribute = null;

    #[ORM\Column(nullable: true)]
    private ?int $dimensions = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'createdAt', type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(name: 'updatedAt', type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\ManyToOne(inversedBy: 'product_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    private ?string $slug = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Telephone::class, inversedBy: 'products', cascade: ['persist'])]
    private Collection $phoneNumber;

    #[ORM\Column(nullable: true)]
    private ?array $tags = null;

    public function __construct()
    {
        $this->phoneNumber = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescr(): ?string
    {
        return $this->descr;
    }

    public function setDescr(string $descr): static
    {
        $this->descr = $descr;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getProductAttribute(): ?ProductAttribute
    {
        return $this->productAttribute;
    }

    public function setProductAttribute(ProductAttribute $productAttribute): static
    {
        $this->productAttribute = $productAttribute;

        return $this;
    }

    public function getDimensions(): ?int
    {
        return $this->dimensions;
    }

    public function setDimensions(?int $dimensions): static
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    public function getColorId(): ?int
    {
        return $this->color_id;
    }

    public function setColorId(?int $color_id): static
    {
        $this->color_id = $color_id;

        return $this;
    }

    public function created_at(): ?string
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function updated_at(): ?string
    {
        return $this->updated_at->format('Y-m-d H:i:s');
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Telephone>
     */
    public function getPhoneNumber(): Collection
    {
        return $this->phoneNumber;
    }

    public function addPhoneNumber(Telephone $phoneNumber): static
    {
        if (!$this->phoneNumber->contains($phoneNumber)) {

            $phoneNumber->addProduct($this);

            $this->phoneNumber->add($phoneNumber);
        }

        return $this;
    }

    public function removePhoneNumber(Telephone $phoneNumber): static
    {
        $this->phoneNumber->removeElement($phoneNumber);

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): static
    {
        $this->tags = $tags;

        return $this;
    }
}
