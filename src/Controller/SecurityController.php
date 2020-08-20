<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class SecurityController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $helper): Response
    {

        $last_username = $helper->getLastUsername();
//        dump($last_username);die;
        return $this->render('Security/login.html.twig', [
            // dernier username saisi (si il y en a un)
            'last_username' => $last_username,
            // La derniere erreur de connexion (si il y en a une)
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }


    /**
     * @Route("/front/logincustomer", name="security_logincustomer")
     */
    public function logincustomer(AuthenticationUtils $helper): Response
    {
        $last_username = $helper->getLastUsername();
//        dump($last_username);die;
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        return $this->render('Security/logincustomer.html.twig', [
            // dernier username saisi (si il y en a un)
            'last_username' => $last_username,
            // La derniere erreur de connexion (si il y en a une)
            'error' => $helper->getLastAuthenticationError(),
            'categories' => $category
        ]);
    }


    /**
     * La route pour se deconnecter.
     *
     * Mais celle ci ne doit jamais être executé car symfony l'interceptera avant.
     *
     *
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }


    /**
     * La route pour se deconnecter.
     *
     * Mais celle ci ne doit jamais être executé car symfony l'interceptera avant.
     *
     *
     * @Route("/front/logouts", name="security_logouts")
     */
    public function logouts(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
