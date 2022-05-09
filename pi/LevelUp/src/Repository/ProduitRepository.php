<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Produit $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Produit $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }

    }
    public function findEntitiesByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM App\Entity\Produit p
                WHERE p.nom LIKE :str 
                OR p.reference LIKE :str 
                OR p.prix LIKE :str 
                OR p.prixFinal LIKE :str 
                OR p.promotion LIKE :str 
                OR p.description LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }

    /**
     * @return Produit[]
     */
    public function findSearch(SearchData $search):array{
        $query =$this
            ->createQueryBuilder('p')
            ->select('c','p')
            ->join('p.idCategorie','c');
        if (!empty($search->q)){

            $query =$query
                ->andWhere('p.nom LIKE :q 
                OR p.prixFinal LIKE :q 
                OR p.description LIKE :q 
                OR p.promotion LIKE :q')
                ->setParameter('q',"%{$search->q}%");
        }
        if(!empty($search->min)){

            $query =$query
                ->andWhere('p.prixFinal >= :min')
                ->setParameter('min',$search->min);
        }
        if(!empty($search->max)){

            $query =$query
                ->andWhere('p.prixFinal <= :max')
                ->setParameter('max',$search->max);
        }
        if(!empty($search->categories)){

            $query =$query
                ->andWhere('c.idCategorie IN (:categories)')
                ->setParameter('categories',$search->categories);
        }


        return $query->getQuery()->getResult();


    }






//$q = $this->createQueryBuilder('g')
//->innerJoin(
//'App\Entity\CollabMembers',
//'s',
//Join::WITH,
//'g.idUtilisateur = s.ID_Utlisateur'
//)
//->where('s.id_collab = :id')
//->orderBy('g.prenom', 'ASC')
//->setParameter('id', $id);
//return $q->getQuery()->getResult();







    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
