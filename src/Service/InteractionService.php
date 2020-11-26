<?php

namespace App\Service;

use App\Entity\Interaction;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InteractionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class InteractionService
{
    private $manager;
    private $interactionRepo;
    public function __construct(EntityManagerInterface $em, InteractionRepository $ir)
    {
        $this->manager = $em;
        $this->interactionRepo = $ir;
    }
    public function interact_add($article, $user, $interaction_type): JsonResponse
    {
        $entityManager = $this->manager;
        $state = false;

        $interact = new Interaction();
        $interact->setUser($user)
            ->setArticle($article)
            ->setInteractionType($interaction_type);
        $entityManager->persist($interact);
        $entityManager->flush();
        if ($interact->getId()) {
            $state = true;
        }
        return new JsonResponse(['action' => 'add', 'state' => $state]);
    }


    //devrait cree un services pour le gerer
    public function interact_remove($article, $user, $interaction_type): JsonResponse
    {
        $entityManager = $this->manager;
        $state = false;
        $interact = $this->interactionRepo->findOneBy(['article' => $article->getId(), 'user' => $user->getId(), 'interaction_type' => $interaction_type]);

        if ($interact) {
            $entityManager->remove($interact);
            $entityManager->flush();
            $state = true;
        }
        return new JsonResponse(['action' => 'remove', 'state' => $state]);
    }
}
