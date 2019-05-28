<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException as DBALExceptionAlias;
use Doctrine\ORM\NonUniqueResultException as NonUniqueResultExceptionAlias;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Modelo correto a se aplicar
     *
     * @param null $search
     * @return mixed
     */
    public function findAllPosts($search = null)
    {
        $qb = $this->createQueryBuilder('p');
        if ($search) {
            $qb->andWhere('p.title like :search')->setParameter('search', '%' . $search . '%');
        }
        return $qb->getQuery()
            ->getResult();
    }

    /**
     * Modelo correto a se aplicar
     *
     * Ao utilizar parâmetros, na execução da consulta, os parametros são vistos como texto puro
     *
     * @param $id
     * @return Post|null
     * @throws NonUniqueResultExceptionAlias
     */
    public function findOneById($id): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Modelo vulnerável a Sql Injection
     *
     * Adicionar uma aspas simples na URL
     * Adicionar ao final da URL: ;truncate table post;
     * Usar um usuário do banco com menos permissões
     *
     * @param $id
     * @return Post|null
     * @throws DBALExceptionAlias
     */
    /*public function findOneById($id): ?Post
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT id, title, content, image
            FROM post p            
            WHERE p.id = ' . $id . '
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $arrayPost = $stmt->fetch();

        return (new Post())
            ->setTitle($arrayPost['title'])
            ->setContent($arrayPost['content'])
            ->setImage($arrayPost['image']);
    }*/

}
