<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\Subcategory;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/")
 */
class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/user", name="user_index", methods="GET")
     */
    public function index(UserRepository $userRepository): Response
    {
        $count_cat = count($this->entityManager->getRepository(Category::class)->findAll());
        $count_annonces = count($this->entityManager->getRepository(Annonce::class)->findAll());
        $count_users = count($this->entityManager->getRepository(User::class)->findAll());
        $subs = $this->entityManager->getRepository(Subcategory::class)->findAll();
        $count_sub_cat = count($subs);
        $count_annonceurs= count($this->entityManager->getRepository(user::class)->findByRoles());
        //$annonceurs=$userRepository->findByRoles();
        return $this->render('user/index.html.twig', 
        ['users' => $userRepository->findByRoles2(),
            'count_users' => $count_users,
            'count_sub_cat' => $count_sub_cat,
            'count_cat' => $count_cat,
            'count_annonceurs' =>$count_annonceurs,
            'count_annonces' => $count_annonces,
]);
    }


    /**
     * @Route("/count_annonceurs", name="user_count_annonceurs", methods="GET")
     */
    public function count_annonceurs(UserRepository $userRepository): Response
    {
        $annonceurs=count($userRepository->findByRoles());
        return $this->render('base.html.twig', ['count_annonceurs' =>$annonceurs]);
    }


    /**
     * @Route("user/annonceurs", name="user_annonceurs", methods="GET")
     */
    public function annonceurs(UserRepository $userRepository): Response
    {
       $annonceurs=$userRepository->findByRoles();
        $count_cat = count($this->entityManager->getRepository(Category::class)->findAll());
        $count_users = count($this->entityManager->getRepository(User::class)->findAll());
        $subs = $this->entityManager->getRepository(Subcategory::class)->findAll();
        $count_sub_cat = count($subs);
        $count_annonces = count($this->entityManager->getRepository(Annonce::class)->findAll());
        $count_annonceurs= count($this->entityManager->getRepository(user::class)->findByRoles());
        return $this->render('user/annonceurs.html.twig', [
            'annonceurs' => $annonceurs,
            'count_cat' => $count_cat,
            'count_annonces' => $count_annonces,
            'count_sub_cat' => $count_sub_cat,
            'count_users' => $count_users,
            'count_annonceurs' =>$count_annonceurs
            ]);
    }



    /**
     * @Route("/new", name="user_new", methods="GET|POST")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $eventDispatcher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            // Par defaut l'utilisateur aura toujours le rôle ROLE_ADMIN
            $user->setRoles(['ROLE_ADMIN']);
            $em = $this->getDoctrine()->getManager();
            $user->setStatut(1);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }
        $count_cat = count($this->entityManager->getRepository(Category::class)->findAll());
        $count_annonces = count($this->entityManager->getRepository(Annonce::class)->findAll());
        $count_users = count($this->entityManager->getRepository(User::class)->findAll());
        $subs = $this->entityManager->getRepository(Subcategory::class)->findAll();
        $count_sub_cat = count($subs);
        $count_annonceurs= count($this->entityManager->getRepository(user::class)->findByRoles());

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'count_sub_cat' => $count_sub_cat,
            'count_users' => $count_users,
            'count_cat' => $count_cat,
            'count_annonceurs' =>$count_annonceurs,
            'count_annonces' => $count_annonces,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods="GET")
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

   /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $eventDispatcher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }
        $count_cat = count($this->entityManager->getRepository(Category::class)->findAll());
        $count_annonces = count($this->entityManager->getRepository(Annonce::class)->findAll());
        $count_users = count($this->entityManager->getRepository(User::class)->findAll());
        $subs = $this->entityManager->getRepository(Subcategory::class)->findAll();
        $count_sub_cat = count($subs);
        $count_annonceurs= count($this->entityManager->getRepository(user::class)->findByRoles());
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'count_sub_cat' => $count_sub_cat,
            'count_users' => $count_users,
            'count_cat' => $count_cat,
            'count_annonceurs' =>$count_annonceurs,
            'count_annonces' => $count_annonces,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/annonceur_edit", name="user_annonceur_edit", methods="GET|POST")
     */
    public function annonceur_edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $eventDispatcher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_annonceurs', ['id' => $user->getId()]);
        }
        $count_cat = count($this->entityManager->getRepository(Category::class)->findAll());
        $count_annonces = count($this->entityManager->getRepository(Annonce::class)->findAll());
        $count_users = count($this->entityManager->getRepository(User::class)->findAll());
        $subs = $this->entityManager->getRepository(Subcategory::class)->findAll();
        $count_sub_cat = count($subs);
        $count_annonceurs= count($this->entityManager->getRepository(user::class)->findByRoles());
        return $this->render('user/edit_annonceur.html.twig', [
            'user' => $user,
            'count_sub_cat' => $count_sub_cat,
            'count_users' => $count_users,
            'count_cat' => $count_cat,
            'count_annonceurs' =>$count_annonceurs,
            'count_annonces' => $count_annonces,

            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete", methods="GET|POST")
     */
    public function delete(Request $request, User $user): Response {
        if ('id' . $user->getId()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            try {
                $em->flush();
                return new JsonResponse(['message' => 'Utilisateur supprimé'], 201);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'erreur'], 422);
            }

        } else {
            return new JsonResponse(['message' => 'erreur'], 422);
        }
    }
}
