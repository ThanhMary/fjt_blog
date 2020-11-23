<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleSearch;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\SearchForm;
use DateTime;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    // public function index(ArticleRepository $articleRepository, Request $request): Response
    // {
    //     $data = new ArticleSearch();
    //     $data->page = $request->get('page', 1);
    //     $form = $this->createForm(SearchForm::class, $data);
    //     $form->handleRequest($request);
    //     $articles = $articleRepository->findSearch($data);
    //     return $this->render('default/home.html.twig', [
    //         'articles' => $articles,
    //         'form' => $form->createView()
    //     ]);
    // }

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
     * @Route("/", name="default_home", methods={"GET"})
     */

    public function home(PaginatorInterface $paginator, Request $request): Response
    {
        
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
            $donnees = $this->getDoctrine()->getRepository(Article::class)->findBy([],['creationDate' => 'desc']);
            $articles = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), 
              5 ); // Nombre de résultats par page
        
        return $this->render('default/home.html.twig', [
            'articles' => $articles,
        ]);
    }

     /**
     * @Route("/detail/{id}", name="default_show", methods={"GET"})
     */
    public function show(Article $article, Request $request, EntityManagerInterface $manager): Response
    {
       
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $comment->setDate(new DateTime());
       
        if($form->isSubmitted()&& $form->isValid()){
            $comment->setDate(new \DateTime())
                    ->setArticle($article);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('article_detail', ['id'=>$article->getId()]);
        }
        return $this->render('article/detail.html.twig', [
            'article'=>$article,
            'Form'=>$form->createView()
        ]);
        
    }
}
