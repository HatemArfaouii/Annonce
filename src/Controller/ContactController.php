<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/front")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact", methods="POST|GET")
     */
    public function contact(Request $request, \Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();
            $message = (new \Swift_Message($contactFormData['email']))
                ->setFrom($contactFormData['email'])
                ->setTo('arfahatem93@gmail.com')
                ->setBody(
                    $contactFormData['message'],
                    'text/plain'
                );
            $mailer->send($message);
            $this->addFlash('success', 'Message envoyé');
            return $this->redirectToRoute('contact');
        }
        return $this->render('contact/contact.html.twig', [
            'our_form' => $form->createView(),
        ]);
    }
}
