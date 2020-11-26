<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InteractionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileUploadService
{
    private $params;
    private $manager;
    private $interactionRepo;
    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em, InteractionRepository $ir)
    {
        $this->params = $params;
        $this->manager = $em;
        $this->interactionRepo = $ir;
    }
    public function store_picture($picture)
    {
        $newFilename = uniqid() . '.' . $picture->guessExtension();
        try {
            $picture->move(
                $this->params->get('pictures'),
                $newFilename
            );
            return $newFilename;
        } catch (FileException $e) {
        }
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
