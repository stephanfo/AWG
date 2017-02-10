<?php

namespace CartBundle\Repository;

/**
 * PriceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PriceRepository extends \Doctrine\ORM\EntityRepository
{

    public function getPrices($format)
    {
        $query = $this->createQueryBuilder('price')
                ->join('price.format', 'format')
                ->leftjoin('price.discount', 'discount')
                ->where('format.id = :format')
                ->orderBy('price.quantity', 'DESC')
                ->addOrderBy('price.updated', 'DESC')
        ;

        $query->AndWhere(
                $query->expr()->orX(
                        $query->expr()->isNull('price.discount'),
                        $query->expr()->andX(
                                $query->expr()->orX(
                                        $query->expr()->lte('discount.startTime', ':now'),
                                        $query->expr()->isNull('discount.startTime')
                                ),
                                $query->expr()->orX(
                                        $query->expr()->gte('discount.stopTime', ':now'),
                                        $query->expr()->isNull('discount.stopTime')
                                ),
                                $query->expr()->eq('discount.active', ':active')
                        )
                )
        );

        $query->setParameters(array(
            'now' => new \DateTime('now'),
            'active' => true,
            'format' => $format
        ));

        return $query->getQuery()->getResult();
    }

}
