<?php

namespace DataBaseBundle\Repository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function findVisible()
    {
        $query = $this->_em->createQuery('SELECT a FROM DataBaseBundle:Article a WHERE a.visible = 1 ORDER BY a.dateCreation DESC');
        $result = $query->getResult();
        return $result;
    }
}