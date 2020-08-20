<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Subcategory;
use App\Entity\Category;
use App\Entity\User;
use App\Form\SubcategoryType;
use App\Repository\SubcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/subcategory")
 */
class SubcategoryController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="subcategory_index", methods={"GET","POST"})
     */
    public function index(SubcategoryRepository $subcategoryRepository,Request $request): Response
    {
        $subcategory = new Subcategory();
        $form = $this->createForm(SubcategoryType::class, $subcategory);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subcategory);
            $entityManager->flush();

            return $this->redirectToRoute('subcategory_index');
        }

        $count_cat = count($this->entityManager->getRepository(Category::class)->findAll());
        $count_users = count($this->entityManager->getRepository(User::class)->findAll());
        $subs = $this->entityManager->getRepository(Subcategory::class)->findAll();
        $count_sub_cat = count($subs);
        $count_annonces = count($this->entityManager->getRepository(Annonce::class)->findAll());
        $count_annonceurs= count($this->entityManager->getRepository(user::class)->findByRoles());

        return $this->render('subcategory/index.html.twig', [
            'form' => $form->createView(),
            'count_cat' => $count_cat,
            'count_annonces' => $count_annonces,
            'count_sub_cat' => $count_sub_cat,
            'count_users' => $count_users,
            'count_annonceurs' =>$count_annonceurs,
            'subcategories' => $subcategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="subcategory_show", methods={"GET"})
     */
    public function show(Subcategory $subcategory): Response
    {
        return $this->render('subcategory/show.html.twig', [
            'subcategory' => $subcategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="subcategory_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Subcategory $subcategory): Response
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SubcategoryType::class, $subcategory);
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
     * @Route("/{id}", name="subcategory_delete", methods="GET|POST")
     */
    public function delete(Request $request, Subcategory $subcategory): Response
    {
        if ('id' . $subcategory->getId()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($subcategory);
            try {
                $em->flush();
                return new JsonResponse(['message' => 'Sous-Catégorie supprimer'], 201);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'erreur'], 422);
            }

        } else {
            return new JsonResponse(['message' => 'erreur'], 422);
        }
    }
}
