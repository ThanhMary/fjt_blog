<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Interaction;
use http\Env\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    private $security;

    public function __construct(\Symfony\Component\Security\Core\Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function index(): Response
    {

        $user = $this->get_current_user();

        $last_liked_articles = $this->getDoctrine()
            ->getRepository(Interaction::class)
            ->findBy([
                'user' => $user,
                'interaction_type' => 1
            ], ['id' => 'DESC'], 5);

        /* Last shared articles */
        $last_shared_articles =  $this->getDoctrine()
            ->getRepository(Interaction::class)
            ->findBy([
                'user' => $user,
                'interaction_type' => 0
            ], ['id' => 'DESC'], 5);

        /* Last commented articles */
        $last_commented_articles =  $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy(
                ['user' => $user],
                ['date' => 'DESC'],
                5
            );

        return $this->render('profile/index.html.twig', [
            'last_liked_articles' => $last_liked_articles,
            'last_shared_articles' => $last_shared_articles,
            'last_commented_articles' => $last_commented_articles,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profile/liked_articles", name="profile.liked_articles")
     */
    public function liked_articles_page()
    {
        return $this->render('profile/articles.html.twig', [
            'page' => 'liked'
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profile/shared_articles", name="profile.shared_articles")
     */
    public function shared_articles_page()
    {
        return $this->render('profile/articles.html.twig', [
            'page' => 'shared'
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profile/comment_articles", name="profile.comment_articles")
     */
    public function comment_articles_page()
    {
        return $this->render('profile/articles.html.twig', [
            'page' => 'comment'
        ]);
    }

    private function get_current_user()
    {
        return $this->security->getUser();
    }

    private function get_interaction_articles($interaction_type){
        $user = $this->get_current_user();
        $articles = $this->getDoctrine()->getManager()
            ->getRepository(Interaction::class)
            ->findBy([
                'user' => $user,
                'interaction_type' => $interaction_type
            ]);
        return $this->generate_array_of_articles($articles);
    }

    private function generate_array_of_articles($articles){
        /* Create articles array */
        $articles_arr = [];

        foreach ($articles as $article) {
            $art = $article->getArticle();
            array_push($articles_arr, [
                'id' => $art->getId(),
                'title' => $art->getTitle(),
                'subtitle' => $art->getSubtitle(),
                'creation_date' => $art->getCreationDate(),
                'category_name' => $art->getCategory()->getName(),
                'picture_path' => $art->getPicturePath(),
                'content' => $art->getContent(),
                'author' => $art->getAutor()->getFirstname()
            ]);
        }
        return $articles_arr;
    }

    /**
     * @Route("/profile/get_liked_articles", name="profile.get_liked_articles")
     */
    public function get_liked_articles()
    {
        /* Get liked articles */
        return new JsonResponse($this->get_interaction_articles(Interaction::LIKE));
    }

    /**
     * @Route("/profile/get_shared_articles", name="profile.get_shared_articles")
     */
    public function get_shared_articles()
    {
        /* Get shared articles */
        return new JsonResponse($this->get_interaction_articles(Interaction::SHARE));
    }

    /**
     * @Route("/profile/get_comment_articles", name="profile.get_comment_articles")
     */
    public function get_comment_articles()
    {
        /* Get commented articles */
        $user = $this->get_current_user();
        $articles = $this->getDoctrine()->getManager()
            ->getRepository(Comment::class)
            ->findBy(['user' => $user]);

        return new JsonResponse($this->generate_array_of_articles($articles));
    }
}
