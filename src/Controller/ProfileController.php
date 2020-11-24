<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Interaction;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
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

    private function get_current_user()
    {
        return $this->security->getUser();
    }

    /**
     * @Route("/profile/get_liked_articles", name="profile.get_liked_articles")
     */

    public function get_liked_articles()
    {

        $user = $this->get_current_user();

        /* Last articles */
        $liked_articles = $this->getDoctrine()->getManager()
            ->getRepository(Interaction::class)
            ->findBy([
                'user' => $user,
                'interaction_type' => 1
            ]);

        /* Create liked articles array */
        $liked_articles_arr = [];
        foreach ($liked_articles as $liked_article) {
            $article = $liked_article->getArticle();
            array_push($liked_articles_arr, [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'subtitle' => $article->getSubtitle(),
                'creation_date' => $article->getCreationDate(),
                'category_name' => $article->getCategory()->getName(),
                'picture_path' => $article->getPicturePath(),
                'content' => $article->getContent(),
                'author' => $article->getAutor()->getFirstname()
            ]);
        }

        return new JsonResponse($liked_articles_arr);
    }

    /**
     * @Route("/profile/liked_articles", name="profile.liked_articles")
     */

    public function liked_articles_page()
    {
        return $this->render('profile/articles.html.twig', [
            'page' => 'liked'
        ]);
    }

    /**
     * @Route("/profile/shared_articles", name="profile.shared_articles")
     */

    public function shared_articles_page()
    {
        return $this->render('profile/articles.html.twig', [
            'page' => 'shared'
        ]);
    }
}
