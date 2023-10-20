<?php

namespace App\Repository;

use App\Entity\Product;
use App\Form\Object\SearchProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByField(SearchProduct $searchProduct)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->addSelect('u')
            ->leftJoin('p.productAttribute', 'u');

        if($searchProduct->getMinWeight() != null)
        {
            $queryBuilder
                ->andwhere('u.weight >= :min_weight')
                ->setParameter('min_weight', $searchProduct->getMinWeight());
        }
        if($searchProduct->getMaxWeight() != null)
        {
            $queryBuilder->andwhere('u.weight <= :max_weight')
                ->setParameter('max_weight', $searchProduct->getMaxWeight());

        }
        if($searchProduct->getMinPrice() != null){
            $queryBuilder->andwhere("p.price >= :min_price")
                ->setParameter('min_price', $searchProduct->getMinPrice());
        }
        if($searchProduct->getMaxprice() != null) {
            $queryBuilder->andwhere("p.price <= :max_price")
            ->setParameter('max_price', $searchProduct->getMaxPrice());
        }
        if($searchProduct->getCategory() != null){
            $queryBuilder->andWhere("p.category = :category")
                ->setParameter('category', $searchProduct->getCategory());
        }
        if($searchProduct->getColor() != null){
            $queryBuilder->andWhere("p.color = :color")
                ->setParameter('color', $searchProduct->getColor());
        }
        if($searchProduct->getSort() != null){
            $queryBuilder->orderBy($searchProduct->getSort());
        }
        if($searchProduct->getSubstring() != null){
            $queryBuilder->andWhere('p.title LIKE :substring OR p.descr LIKE :substring')
                ->setParameter('substring', "%" . $searchProduct->getSubstring() ."%");
        }
        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    public function countProductItems(){
        return $this
            ->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function idMax(){
        return $this
            ->createQueryBuilder('p')
            ->select('MAX(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function save(Product $product, bool $flush): void
    {
        $this->getEntityManager()->persist($product);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $product, bool $flush): void
    {
        $this->getEntityManager()->remove($product);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function removeAll(): void
    {
        foreach ($this->findAll() as $item){
            $this->getEntityManager()->remove($item);
        }
        $this->getEntityManager()->flush();
    }
    public function findOneBySlug($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.slug = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
