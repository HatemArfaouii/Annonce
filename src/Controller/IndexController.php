<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
  use App\Entity\Subcategory;
use App\Entity\Annonce;
use App\Entity\Category;

class IndexController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/admin", name="index")
     */
    public function index()
    {
//        $authCkecker =$this->container->get('security.authorization_checker');
//        if ($authCkecker->$this->isGranted('ROLE_ADMIN')) {
            $count_cat = count($this->entityManager->getRepository(Category::class)->findAll());
            $count_annonces = count($this->entityManager->getRepository(Annonce::class)->findAll());
            $count_users = count($this->entityManager->getRepository(User::class)->findAll());
            $subs = $this->entityManager->getRepository(Subcategory::class)->findAll();
            $count_sub_cat = count($subs);
            $count_annonceurs = count($this->entityManager->getRepository(user::class)->findByRoles());

            return $this->render('index.html.twig', [
                'count_sub_cat' => $count_sub_cat,
                'count_users' => $count_users,
                'count_cat' => $count_cat,
                'count_annonceurs' => $count_annonceurs,
                'count_annonces' => $count_annonces,
                'controller_name' => 'IndexController',
            ]);
//        }
//        else
//        {
//            return $this->render('Security/login.html.twig');
//        }
    }
}
