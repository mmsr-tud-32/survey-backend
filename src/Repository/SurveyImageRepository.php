<?php

namespace App\Repository;

use App\Entity\SurveyImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SurveyImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyImage[]    findAll()
 * @method SurveyImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyImageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SurveyImage::class);
    }

    /**
     * @param string $value
     * @return SurveyImage Returns a Survey object
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findByUuid(string $value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.uuid = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;
    }
}
