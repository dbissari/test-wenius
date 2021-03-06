<?php

namespace AppBundle\Repository\Work;

use Doctrine\ORM\EntityRepository;

/**
 * OperationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OperationRepository extends EntityRepository
{
    /**
     * Recherche les opérations en cours
     * 
     * @return array
     */
    public function getEnCours()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT o
                  FROM AppBundle:Work\Operation o
                  WHERE o.dateDebut <= :now
                  AND o.dateFinEffective IS NULL
                  ORDER BY o.dateDebut ASC'
            )
            ->setParameter('now', new \DateTime())
            ->getResult();
    }

    /**
     * Recherche les opérations futures
     * 
     * @return array
     */
    public function getFutures()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT o
                  FROM AppBundle:Work\Operation o
                  WHERE o.dateDebut > :now
                  ORDER BY o.dateDebut ASC'
            )
            ->setParameter('now', new \DateTime())
            ->getResult();
    }
}
