<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\Subcategory;
use App\Entity\User;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Routing\Annotation\Route;
use \Sightengine\SightengineClient;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/annonce")
 */
class AnnonceController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/testphoto", name="annonce_testphoto", methods={"GET","POST"})
     */
    public function testPhoto(Request $request): Response
    {
        $client = new SightengineClient('1200427185', 'ptziDnxQJerzgDMSsWkU');
        $file = $request->files->get('file');
        $filename = $file->getClientOriginalName();
        $originalFilename = pathinfo($filename, PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        // Move the file to the directory where brochures are stored
        try {
            $file->move(
                $this->getParameter('app.path.product_images'),
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        $output = $client->check(['nudity'])->set_file('/uploads/images/products/' . $newFilename);
        return new JsonResponse($output, 201);
    }


    /**
     * @Route("/", name="annonce_index", methods={"GET","POST"})
     */
    public function index(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $annonce = new Annonce();
        $valide = count($this->entityManager->getRepository(Annonce::class)->findBy(['verifannonce' => '1']));
        $attende = count($this->entityManager->getRepository(Annonce::class)->findBy(['verifannonce' => '0']));
        $refuse = count($this->entityManager->getRepository(Annonce::class)->findBy(['verifannonce' => '2']));
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getRoles() == ['ROLE_ADMIN']) {
                $annonce->setVerifannonce(1);
            } else {
                $annonce->setVerifannonce(0);
            }
            $annonce->setUserid($user);
            $annonce->setDateadd(new \DateTime());
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('annonce_index');
        }
        $count_cat = count($this->entityManager->getRepository(Category::class)->findAll());
        $count_annonces = count($this->entityManager->getRepository(Annonce::class)->findAll());
        $count_users = count($this->entityManager->getRepository(User::class)->findAll());
        $subs = $this->entityManager->getRepository(Subcategory::class)->findAll();
        $count_sub_cat = count($subs);
        $count_annonceurs= count($this->entityManager->getRepository(user::class)->findByRoles());

        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findAll(),
            'form' => $form->createView(),
            'valide' => $valide,
            'attende' => $attende,
            'refuse' => $refuse,
            'count_users' => $count_users,
            'list_category' => $category,
            'count_sub_cat' => $count_sub_cat,
            'count_users' => $count_users,
            'count_cat' => $count_cat,
            'count_annonces' => $count_annonces,
            'count_annonceurs' =>$count_annonceurs
        ]);
    }

    /**
     * @Route("/new", name="annonce_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        //dump($request);die;
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($user->getRoles() == ['ROLE_USER']) {
                $annonce->setVerifannonce(1);
            } else {
                $annonce->setVerifannonce(0);
            }
            $annonce->setUserid($user);
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('annonce_index');
        }

        return $this->render('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annonce_show", methods={"GET"})
     */
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="annonce_edit", methods={"GET","POST"})
     */

    public function edit(Request $request, Annonce $annonce): Response
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $data = $request->request->all();
        $form->submit($data, false);
        // if ($form->isSubmitted() && $form->isValid()) {
        try {
            $em->flush();
            return new JsonResponse([
                'message' => 'Annonce modifié',
                'annonce' => $data
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'erreur'], 422);
        }

        // } else {
        //     return new JsonResponse(['message' => 'erreur'], 422);
        // }
    }

    /**
     * @Route("/{id}", name="annonce_delete", methods={"GET|POST"})
     */

    public function delete(Request $request, Annonce $annonce): Response
    {
        if ('id' . $annonce->getId()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($annonce);
            try {
                $em->flush();
                return new JsonResponse(['message' => 'Annonce  supprimé'], 201);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'erreur'], 422);
            }
        } else {
            return new JsonResponse(['message' => 'erreur'], 422);
        }
    }


    /**
     * @Route("/{id}/statusannonce", name="annonce_statusannonce", methods="GET|POST")
     */
    public function statusannonce(Request $request, Annonce $annonce): Response
    {
        $em = $this->getDoctrine()->getManager();

        $count_valide = count($this->entityManager->getRepository(Annonce::class)->findBy(['verifannonce' => '1']));
        $count_attende = count($this->entityManager->getRepository(Annonce::class)->findBy(['verifannonce' => '0']));
        $count_refuse = count($this->entityManager->getRepository(Annonce::class)->findBy(['verifannonce' => '2']));

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        $data = $request->request->all();
        $form->submit($data, false);
        //if ($form->isSubmitted() && $form->isValid()) {
        try {
            $em->flush();
            return new JsonResponse([
                'success' => true,
                'count_valide' => $count_valide,
                'count_attende' => $count_attende,
                'count_refuse' => $count_refuse,
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'erreur'], 422);
        }
    }

}
