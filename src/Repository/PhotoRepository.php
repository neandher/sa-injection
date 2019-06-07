<?php

namespace App\Repository;

use App\Entity\Photo;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException as DBALExceptionAlias;
use Doctrine\ORM\NonUniqueResultException as NonUniqueResultExceptionAlias;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Photo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Photo::class);
    }

    /**
     * @param null $search
     * @return mixed
     */
    public function findAllPhotos($search = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.user', 'u')
            ->addSelect('u')
            ->orderBy('p.id', 'DESC');

        if ($search && $search != '') {
            $qb->where('p.title like :search')
                ->setParameter('search', '%' . $search . '%');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $id
     * @return Photo|null
     * @throws NonUniqueResultExceptionAlias
     */
    public function findOneById($id): ?Photo
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :id')->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
