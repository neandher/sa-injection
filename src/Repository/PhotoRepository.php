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
     * @throws DBALExceptionAlias
     */
    public function findAllPhotos($search = null)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT p.id, title, file, u.email
            FROM photo p 
            INNER JOIN user u on p.user_id = u.id            
            ';

        if ($search && $search != '') {
            $sql .= " WHERE title like '%" . $search . "%' ";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $arrayPhotos = $stmt->fetchAll();

        $ar = [];
        foreach ($arrayPhotos as $arrayPhoto){
            $ar[] = (new Photo())
                ->setId($arrayPhoto['id'])
                ->setTitle($arrayPhoto['title'])
                ->setFile($arrayPhoto['file'])
                ->setUser((new User())->setEmail($arrayPhoto['email']));
        }

        return $ar;
    }

    /**
     * Modelo vulnerável a Sql Injection
     *
     * Adicionar uma aspas simples na URL
     * Adicionar ao final da URL: ;truncate table photo;
     * Usar um usuário do banco com menos permissões
     *
     * @param $id
     * @return Photo|null
     * @throws DBALExceptionAlias
     */
    public function findOneById($id): ?Photo
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT p.id, title, file, u.email
            FROM photo p 
            INNER JOIN user u on p.user_id = u.id
            WHERE p.id = ' . $id . '
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $arrayPhoto = $stmt->fetch();

        return (new Photo())
            ->setTitle($arrayPhoto['title'])
            ->setFile($arrayPhoto['file'])
            ->setUser((new User())->setEmail($arrayPhoto['email']));
    }

}
