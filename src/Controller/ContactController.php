<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class,$contact);
        $form->handleRequest($request);
      
        if($form->isSubmitted()&& $form->isValid()){

            $check= $contactRepository->checkDispo($contact);
            if($check){
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($contact);
                $manager->flush();
    
                $this->addFlash('success','Le contact a été bien enregistré');
            }
        }

        return $this->render('contact/index.html.twig', [
           'form' => $form->createView(),
        ]);
    }
}
