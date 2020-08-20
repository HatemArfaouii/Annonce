<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\Subcategory;
use App\Entity\User;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    private $entityManager;

    /**
     * @Route("/", name="category_index", methods="GET|POST")
     */
    public function index(CategoryRepository $categoryRepository, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }
        $count_cat = count($this->entityManager->getRepository(Category::class)->findAll());
        $count_users = count($this->entityManager->getRepository(User::class)->findAll());
        $subs = $this->entityManager->getRepository(Subcategory::class)->findAll();
        $count_sub_cat = count($subs);
        $count_annonces = count($this->entityManager->getRepository(Annonce::class)->findAll());
        $count_annonceurs= count($this->entityManager->getRepository(user::class)->findByRoles());

        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'form' => $form->createView(),
            'count_cat' => $count_cat,
            'count_annonces' => $count_annonces,
            'count_sub_cat' => $count_sub_cat,
            'count_users' => $count_users,
            'count_annonceurs' =>$count_annonceurs,


        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods="GET|POST")
     */
    public function edit(Request $request, Category $category): Response
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        $data = $request->request->all();
        $form->submit($data, false);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->flush();
                return new JsonResponse(['success' => true], 201);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'erreur'], 422);
            }

        } else {
            return new JsonResponse(['message' => 'erreur'], 422);
        }
    }

    /**
     * @Route("/{id}", name="category_delete", methods="GET|POST")
     */
    public function delete(Request $request, Category $categoryr): Response
    {
        if ('id' . $categoryr->getId()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categoryr);
            try {
                $em->flush();
                return new JsonResponse(['message' => 'Catégorie supprimer'], 201);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'erreur'], 422);
            }

        } else {
            return new JsonResponse(['message' => 'erreur'], 422);
        }
    }

    // /**
    //  * @Route("/seif", name="category_seif", methods="GET")
    //  */
    // public function seif(CategoryRepository $categoryRepository,Request $request): Response
    // {
    //     return $this->render('category/home.html.twig', [
    //         'categories' => $categoryRepository->findAll(),
    //     ]);
    // }

}
