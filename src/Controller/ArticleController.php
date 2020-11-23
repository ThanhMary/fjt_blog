<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\Asset\Packages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/home", name="article_home", methods={"GET"})
     */
    public function home(ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        if ($user) {
            $articles = $user->getArticles()->toArray();
            foreach ($articles as $article) {
                $tabArticles[] = $article->getId();
            }
        }

        return $this->render('article/home.html.twig', [
            'articles' => $articleRepository->findAll(),
            'user_article' => isset($tabArticles) && $tabArticles ? $tabArticles : null,
        ]);
    }

    /**
     * @Route("/detail", name="article_detail", methods={"GET"})
     */
    // public function detail(Article $article, Request $request, EntityManagerInterface $manager): Response
    // {
    //     $comment = new Comment();
    //     $form = $this->createForm(CommentType::class, $comment);
    //     $form->handleRequest($request);

    //     if($form->isSubmitted()&& $form->isValid()){
    //         $comment->setDate(new \DateTime())
    //                 ->setArticle($article);
    //         $manager->persist($comment);
    //         $manager->flush();

    //         return $this->redirectToRoute('article_detail', ['id'=>$article->getId()]);
    //     }
    //     return $this->render('article/detail.html.twig', [
    //         'article'=>$article,
    //         'commentForm'=>$form->createView()
    //     ]);

    // }



    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request, Packages $assetPackage): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $picture = $form->get('picturePath')->getData();
            $article->setCreationDate(new DateTime());
            if ($picture) {
                $newFilename = uniqid() . '.' . $picture->guessExtension();
                try {
                    $picture->move(
                        $this->getParameter('pictures'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new Exception('Error on picture upload');
                }
                $article->setPicturePath($request->getSchemeAndHttpHost() . '/uploads/pictures/' . $newFilename);
                $article->setUser($this->getUser());
            }

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }

}
