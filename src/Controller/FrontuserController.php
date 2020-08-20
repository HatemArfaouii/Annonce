<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontuserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/frontuser", name="frontuser")
     */
    public function index()
    {
        $categories =$this->entityManager->getRepository(Category::class)->findAll();
        return $this->render('frontuser/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/home", name="frontuser_home")
     */
    public function home()
    {
        $categories =$this->entityManager->getRepository(Category::class)->findAll();
        return $this->render('base_front.html.twig', [
            'categories' => $categories,
        ]);
    }


}
