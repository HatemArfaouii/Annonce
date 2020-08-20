<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Message;
use App\Entity\Subcategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Annonce;
use App\Form\UserType;
use App\Form\AnnonceType;
use App\Form\MessageType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use App\Events;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerBuilder;

/**
 * @Route("/front")
 */
class CustomerController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="customer")
     */
    public function home(Request $request)
    {
        //$entityManager = $this->getDoctrine()->getManager();
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $subs = $this->entityManager->getRepository(Subcategory::class)->findAll();
//        $count_users = count($this->entityManager->getRepository(User::class)->findAll());
        $count_sub_cat = count($subs);
        $count_annonceurs = count($this->entityManager->getRepository(user::class)->findByRoles());
        $annonces = $this->entityManager->getRepository(Annonce::class)->findBy(['verifannonce' => '1']);
        $count_annonces = count($annonces);

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        return $this->render('customer/home.html.twig', [
            'form' => $form->createView(),
            'categories' => $category,
            'subcategorys' => $subs,
            'count_sub_cat' => $count_sub_cat,
            'annonces' => $annonces,
            'count_annonceurs' => $count_annonceurs,
            'count_annonces' => $count_annonces
        ]);
    }


    /**
     * @Route("/profil", name="customer_profil")
     */
    public function profilAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $eventDispatcher)
    {
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            // Par defaut l'utilisateur aura toujours le rôle ROLE_USER
            $user->setRoles(['ROLE_USER']);
            // On enregistre l'utilisateur dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            //On déclenche l'event
            $event = new GenericEvent($user);
            $eventDispatcher->dispatch(Events::USER_REGISTERED, $event);
            return $this->redirectToRoute('security_logincustomer');
        }
        return $this->render(
            'customer/profil.html.twig',
            array(
                'form' => $form->createView(),
                'categories' => $category
            )
        );
    }


    /**
     * @Route("/customer/posteannonce", name="customer_postannonce")
     */
    public function postannonce(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setVerifannonce(0);
            $annonce->setUserid($user);
            $annonce->setDateadd(new \DateTime());
            $entityManager->persist($annonce);
            $entityManager->flush();
            return $this->redirectToRoute('customer_customerprofil');
        }
        return $this->render('Customer/posteannonce.html.twig', [
            'categories' => $category,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/settingprofile", name="customer_settingprofile", methods="GET|POST")
     */
    public function settingprofile(Request $request, UserPasswordEncoderInterface $passwordEncoder, User $user): Response
    {
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('customer_customerprofil');
        }
        return $this->render('customer/settingprofile.html.twig', [
            'categories' => $category,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/customer/customerprofil", name="customer_customerprofil")
     */
    public function customerprofil(Request $request): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $my_annonces = $this->entityManager->getRepository(Annonce::class)->findBy(['userid' => $user]);
        $active = count($this->entityManager->getRepository(Annonce::class)->findBy(['userid' => $user, 'verifannonce' => 1]));
        $refuse = count($this->entityManager->getRepository(Annonce::class)->findBy(['userid' => $user, 'verifannonce' => 2]));
        $attente = count($this->entityManager->getRepository(Annonce::class)->findBy(['userid' => $user, 'verifannonce' => 0]));
        $all_annonce = count($my_annonces);
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        return $this->render('Customer/customerprofil.html.twig', [
            'categories' => $category,
            'my_annonces' => $my_annonces,
            'all_annonce' => $all_annonce,
            'active' => $active,
            'refuse' => $refuse,
            'attente' => $attente
        ]);
    }


    /**
     * @Route("/{id}/editannonce", name="customer_editannonce", methods="GET|POST")
     */
    public function editannonce(Request $request, Annonce $annonce): Response
    {
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('customer_customerprofil');
        }
        return $this->render('customer/editannonce.html.twig', [
            'categories' => $category,
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/details", name="customer_details")
     */
    public function details(Request $request): Response
    {
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $detail = $request->get('id');
        $details = $this->entityManager->getRepository(Annonce::class)->findBy(['id' => $detail]);
        return $this->render('Customer/detail_annonce.html.twig', [
            'details' => $details,
            'categories' => $category
        ]);
    }


    /**
     * @Route("/customer/{id}", name="customer_deleteannonce", methods={"GET|POST"})
     */

    public function deleteannonce(Request $request, Annonce $annonce): Response
    {
        if ('id' . $annonce->getId()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($annonce);
            try {
                $em->flush();
                return new JsonResponse(['message' => 'Annonce  supprimer'], 201);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'erreur'], 422);
            }
        } else {
            return new JsonResponse(['message' => 'erreur'], 422);
        }
    }


    /**
     * @Route("/searchbyregion", name="customer_searchbyregion", methods="GET|POST")
     */
    public function searchbyregion(Request $request): Response
    {
        $region = $request->get('region');
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $jmsSerializer = SerializerBuilder::create()->build();
        $list_annonce_by_regions = $jmsSerializer->toArray($this->entityManager->getRepository(Annonce::class)->findBy(['ville' => $region]));
        $count_annonce_by_region = count($list_annonce_by_regions);
        if ($list_annonce_by_regions != null) {
            return new JsonResponse([
                'categories' => $category,
                'list_annonce_by_regions' => $list_annonce_by_regions,
                'count_annonce_by_region' => $count_annonce_by_region,
            ], 201);
        }
        return $this->render('customer/searchbyregion.html.twig', [
            'categories' => $category,
            'list_annonce_by_regions' => $list_annonce_by_regions,
            'count_annonce_by_region' => $count_annonce_by_region,
        ]);
    }


    /**
     * @Route("/searchbycategory", name="customer_searchbycategory", methods="GET|POST")
     */
    public function searchbycategory(Request $request): Response
    {
        $sub_id = $request->get('id');
        $jmsSerializer = SerializerBuilder::create()->build();
        $annonces_list_by_cat = $jmsSerializer->toArray($this->entityManager->getRepository(Annonce::class)->findBy(['subcategory' => $sub_id]));
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $count_annonce_by_cat = count($annonces_list_by_cat);
        if ($annonces_list_by_cat != null) {
            return new JsonResponse([
                'categories' => $category,
                'annonces_list_by_cat' => $annonces_list_by_cat,
                'count_annonce_by_cat' => $count_annonce_by_cat,
            ], 201);
        }
        return $this->render('customer/searchbyregion.html.twig', [
            'categories' => $category,
            'annonces_list_by_cat' => $annonces_list_by_cat,
            'count_annonce_by_cat' => $count_annonce_by_cat,
        ]);
    }


    /**
     * @Route("/searchautocomplate", name="customer_searchautocomplate", methods="GET|POST")
     */
    public function searchautocomplate(Request $request): Response
    {
        $sub_id = $request->get('q');
        $jmsSerializer = SerializerBuilder::create()->build();
        $annonces_autocomplt = $jmsSerializer->toArray($this->entityManager->getRepository(Annonce::class)->findAll());
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $count_annonce_by_cat = count($annonces_autocomplt);
        if ($annonces_autocomplt != null) {
            return new JsonResponse([
                'annonces_list_by_cat' => $annonces_autocomplt,
                'count_annonce_by_cat' => $count_annonce_by_cat,
            ], 201);
        }
        return $this->render('customer/searchbyregion.html.twig', [
            'categories' => $category,
            'annonces_list_by_cat' => $annonces_autocomplt,
            'count_annonce_by_cat' => $count_annonce_by_cat,
        ]);
    }

    /**
     * @Route("/comment", name="customer_comment", methods="GET|POST")
     */
    public function comment(Request $request): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $jmsSerializer = SerializerBuilder::create()->build();
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $id_annonce = $request->get('id');
        $id_anc = $this->entityManager->getRepository(Annonce::class)->findOneBy(['id' => $id_annonce]);
        $content_comment = $request->get('comment');
        $comment = new Comment();
        $entityManager = $this->getDoctrine()->getManager();
        $comment->setUsercommet($user);
        $comment->setComment($content_comment);
        $comment->setAnnonceid($id_anc);
        $entityManager->persist($comment);
        $entityManager->flush();
        //$annonce = $jmsSerializer->toArray($this->entityManager->getRepository(Comment::class)->findBy(["annonceid" => $id_annonce]));
        if ($category != null) {
            return new JsonResponse([
//                'comments' => $annonce,
            ], 201);
        }
    }


    /**
     * @Route("/addcomment", name="customer_addcomment", methods="GET|POST")
     */
    public function addcomment(Request $request): Response
    {
        $jmsSerializer = SerializerBuilder::create()->build();
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $id_annonce = $request->get('id');
        $annonce = $jmsSerializer->toArray($this->entityManager->getRepository(Comment::class)->findBy(["annonceid" => $id_annonce]));
        if ($category != null) {
            return new JsonResponse([
                //                'categories' => $category,
                'comments' => $annonce,
            ], 201);
        }
    }


    /**
     * @Route("/sendmessage", name="customer_sendmessage", methods="GET|POST")
     */
    public function sendmessage(Request $request): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $jmsSerializer = SerializerBuilder::create()->build();
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $id_annonce = $request->get('id');
        $id_anc = $this->entityManager->getRepository(Annonce::class)->findOneBy(['id' => $id_annonce]);
        $content_message = $request->get('message');
        $message = new Message();
        $entityManager = $this->getDoctrine()->getManager();
        $message->setUserid($user);
        $message->setContent($content_message);
        $message->setAnnonceid($id_anc);
        $message->setDateadd(new \DateTime());
        $entityManager->persist($message);
        $entityManager->flush();
        $last_insert = $message->getId();
        $message = $jmsSerializer->toArray($this->entityManager->getRepository(Message::class)->findBy(["id" => $last_insert]));
        if ($category != null) {
            return new JsonResponse([
                'message' => $message,
            ], 201);
        }
    }


    /**
     * @Route("/getmessage", name="customer_getmessage", methods="GET|POST")
     */
    public function getmessage(Request $request): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $jmsSerializer = SerializerBuilder::create()->build();
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $id_annonce = $request->get('id');
        $annonce = $jmsSerializer->toArray($this->entityManager->getRepository(Message::class)->findBy(["annonceid" => $id_annonce]));
        if ($category != null) {
            return new JsonResponse([
                'messages' => $annonce,
            ], 201);
        }
    }

    /**
     * @Route("/{id}/repmessage", name="customer_repmessage", methods="GET|POST")
     */
    public function repmessage(Request $request ): Response
    {
        $id_message= $request->get('id');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $messages = $this->entityManager->getRepository(Message::class)->findBy(["annonceid"=>$id_message]);
        return $this->render('customer/repmessage.html.twig', [
            'messages' => $messages,
            'categories' => $category,
        ]);
    }

    /**
     * @Route("/{id}/msg", name="customer_msg", methods="GET|POST")
     */
    public function msg(Request $request ): Response
    {
        $id_msg= $request->get('id');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $category = $this->entityManager->getRepository(Category::class)->findAll();
        $msgs = $this->entityManager->getRepository(Message::class)->findOneBy(["id"=>$id_msg]);
        $res=$msgs->getAnnonceid();
//        dump($res->getId());die;
        return $this->render('customer/msg.html.twig', [
            'annonceid'=>$res->getId(),
            'msgs' => $msgs,
            'categories' => $category,
        ]);
    }

    /**
     * @Route("/savemessage", name="customer_savemessage")
     */
    public function savemessage(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $id_annonce= $request->get('annonceid');
        $content= $request->get('content');
        $id_anc=$this->entityManager->getRepository(Annonce::class)->findOneBy(['id'=>$id_annonce]);
        $msgs = $this->entityManager->getRepository(Message::class)->findOneBy(["id"=>$id_annonce]);
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        $message->setAnnonceid($id_anc);
        $message->setContent($content);
        $message->setUserid($user);
        $message->setDateadd(new \DateTime());
        $entityManager->persist($message);
        $entityManager->flush();

        return $this->redirectToRoute('customer_customerprofil');
    }

}
