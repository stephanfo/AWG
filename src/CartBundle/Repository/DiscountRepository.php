<?php

namespace CartBundle\Repository;

/**
 * DiscountRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DiscountRepository extends \Doctrine\ORM\EntityRepository
{

    public function getCurrentDiscount($total = null)
    {
        $query = $this->createQueryBuilder("discount")
                ->where('discount.active = :active')
                ->addSelect('(CASE WHEN discount.stopTime IS NULL THEN 1 ELSE 0 END) AS HIDDEN nullStopTime')
                ->orderBy('nullStopTime', 'ASC')
                ->addOrderBy('discount.stopTime', 'ASC')
                ->addOrderBy('discount.updated', 'DESC')
                ->setMaxResults(1)
        ;

        $query->AndWhere(
                $query->expr()->andX(
                        $query->expr()->orX(
                                $query->expr()->lte('discount.startTime', ':now'), $query->expr()->isNull('discount.startTime')
                        ), $query->expr()->orX(
                                $query->expr()->gte('discount.stopTime', ':now'), $query->expr()->isNull('discount.stopTime')
                        )
                )
        );

        $query->setParameters(array(
            'now' => new \DateTime('now'),
            'active' => true
        ));

        if (!is_null($total))
        {
            $query->AndWhere(
                    $query->expr()->andX(
                            $query->expr()->orX(
                                    $query->expr()->lte('discount.discountStart', ':total'), $query->expr()->isNull('discount.discountStart')
                            ), $query->expr()->orX(
                                    $query->expr()->gt('discount.discountStop', ':total'), $query->expr()->isNull('discount.discountStop')
                            )
                    )
            );

            $query->setParameter('total', $total);
        }

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getAllCurrentDiscount()
    {
        $query = $this->createQueryBuilder("discount")
                ->where('discount.active = :active')
                ->addSelect('(CASE WHEN discount.stopTime IS NULL THEN 1 ELSE 0 END) AS HIDDEN nullStopTime')
                ->orderBy('nullStopTime', 'ASC')
                ->addOrderBy('discount.stopTime', 'ASC')
                ->addOrderBy('discount.discountStart', 'ASC')
                ->addOrderBy('discount.updated', 'DESC')
        ;

        $query->AndWhere(
                $query->expr()->andX(
                        $query->expr()->orX(
                                $query->expr()->lte('discount.startTime', ':now'), $query->expr()->isNull('discount.startTime')
                        ), $query->expr()->orX(
                                $query->expr()->gte('discount.stopTime', ':now'), $query->expr()->isNull('discount.stopTime')
                        )
                )
        );

        $query->setParameters(array(
            'now' => new \DateTime('now'),
            'active' => true
        ));

        return $query->getQuery()->getResult();
    }

}
