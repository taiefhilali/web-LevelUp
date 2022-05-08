<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\AdministrateurRepository;
use App\Repository\FournisseurRepository;
use App\Repository\LivreurRepository;
use App\Repository\ClientRepository;

class DashController extends AbstractController
{
    /**
     * @Route("/dash", name="app_dash")
     */
    public function index(AdministrateurRepository $adminRepository,FournisseurRepository $fournisseurRepository,
    LivreurRepository $livreurRepository, ClientRepository $clientRepository): Response
    {
        $admins = $adminRepository->createQueryBuilder('u')
            ->select('count(u.idUser)')
            ->getQuery()
            ->getSingleScalarResult();

            $fournisseurs= $fournisseurRepository->createQueryBuilder('u')
            ->select('count(u.idUser)')
            ->getQuery()
            ->getSingleScalarResult();
            $clients= $clientRepository->createQueryBuilder('u')
            ->select('count(u.idUser)')
            ->getQuery()
            ->getSingleScalarResult();
            $livreurs= $livreurRepository->createQueryBuilder('u')
            ->select('count(u.idUser)')
            ->getQuery()
            ->getSingleScalarResult();
            $femmes= $clientRepository->createQueryBuilder('u')
            ->select('count(u.idUser)')
            ->where('u.sexe = :femme')
            ->setParameter('femme', "femme")
            ->getQuery()
            ->getSingleScalarResult();
            $hommes= $clientRepository->createQueryBuilder('u')
            ->select('count(u.idUser)')
            ->where('u.sexe = :homme')
            ->setParameter('homme', "homme")
            ->getQuery()
            ->getSingleScalarResult();
            $hf=[$hommes,$femmes];
            $utilisateurs=[$admins,$fournisseurs,$livreurs,$clients,0];
            
        return $this->render('dash/index.html.twig', [
            'admins' => $admins,
            'fournisseurs' => $fournisseurs,
            'livreurs' => $livreurs,
            'clients' => $clients,
            'hf' =>json_encode($hf),
            'users' =>json_encode($utilisateurs)

        ]);
    }
}
