<?php

namespace App\Entity;

use App\Repository\CommentLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentLikeRepository::class)
 */
class CommentLike
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="likes")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Likes")
     */
    private $User;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
