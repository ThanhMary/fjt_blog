<?php

namespace App\Entity;

use App\Repository\InteractionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InteractionRepository::class)
 */
class Interaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $interaction_type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="interactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="interactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInteractionType(): ?int
    {
        return $this->interaction_type;
    }

    public function setInteractionType(int $interaction_type): self
    {
        $this->interaction_type = $interaction_type;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getArticleId(): ?Article
    {
        return $this->article_id;
    }

    public function setArticleId(?Article $article_id): self
    {
        $this->article_id = $article_id;

        return $this;
    }
}
