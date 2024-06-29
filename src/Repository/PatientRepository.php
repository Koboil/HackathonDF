<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    //    /**
    //    //     * @return Patient[] Returns an array of Patient objects
    //    //     */
    //    //    public function findByExampleField($value): array
    //    //    {
    //    //        return $this->createQueryBuilder('p')
    //    //            ->andWhere('p.exampleField = :val')
    //    //            ->setParameter('val', $value)
    //    //            ->orderBy('p.id', 'ASC')
    //    //            ->setMaxResults(10)
    //    //            ->getQuery()
    //    //            ->getResult()
    //    //        ;
    //    //    }

    //    public function findOneBySomeField($value): ?Patient
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findPatientInfoBy(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p.id, 
                p.gender, 
                p.surname, 
                p.name, 
                p.birth_date, 
                p.phone_number,
                s.bubble_status, 
                s.type, 
                s.isActive,
                s.send_sms
         FROM App\Entity\Patient p
         LEFT JOIN p.status s
         ORDER BY p.id ASC'
        );

        return $query->getResult();
    }

}
