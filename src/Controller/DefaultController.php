<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('article_home');
    }

    /**
     * @Route("/propos", name="propos")
     */
    public function propos(): Response
    {
        return $this->render('default/propos.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

     /**
     * @Route("/home", name="default_home", methods={"GET"})
     */
    public function home(ArticleRepository $articleRepository): Response
    {
       // dd($articleRepository->findAll()[0]);
        return $this->render('default/home.html.twig', [
            'articles' => $articleRepository->findAll(),
           
        ]);
    }
}
