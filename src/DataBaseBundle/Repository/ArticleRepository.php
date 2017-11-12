<?php

namespace DataBaseBundle\Repository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
use Doctrine\ORM\EntityRepository;
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function findVisible()
    {
        $query = $this->_em->createQuery('SELECT a FROM DataBaseBundle:Article a WHERE a.visible = 1 ORDER BY a.dateCreation DESC');
        $result = $query->getResult();
        return $result;
    }

        public function rechercheCustumC($data)
    {
        $query = $this->createQueryBuilder('a');
        $query->where('a.visible = 1');
        switch ($data['trie_par']){
            case 'title':
                $query->orderBy("a.title",'ASC');
                break;
            case 'dateCreation':
                $query->orderBy("a.dateCreation",'ASC');
                break;
            case 'likes':
                $query->orderBy("a.likes",'ASC');
                break;
            default:
                $query->orderBy("a.dateCreation",'ASC');
                break;
        }
        if($data['Recherhce']!='') {
            $text='%'.$data['Recherhce'].'%';
            $query->andWhere('a.title LIKE  :text ')
                ->orWhere('a.description LIKE  :text')
                ->setParameter('text',$text);
        }
        return $query->getQuery()->getResult();
    }

    public function rechercheCustumD($data)
    {
        $query = $this->createQueryBuilder('a');
        $query->where('a.visible = 1');
        switch ($data['trie_par']){
            case 'title':
                $query->orderBy("a.title",'DESC');
                break;
            case 'dateCreation':
                $query->orderBy("a.dateCreation",'DESC');
                break;
            case 'likes':
                $query->orderBy("a.likes",'DESC');
                break;
            default:
                $query->orderBy("a.dateCreation",'DESC');
                break;
        }
        if($data['Recherhce']!='') {
            $text='%'.$data['Recherhce'].'%';
            $query->andWhere('a.title LIKE  :text ')
                ->orWhere('a.description LIKE  :text')
                ->setParameter('text',$text);
        }
        return $query->getQuery()->getResult();
    }
}
