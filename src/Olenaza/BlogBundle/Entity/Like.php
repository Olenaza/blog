<?php

namespace Olenaza\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="likes_count")
 * @ORM\Entity(repositoryClass="Olenaza\BlogBundle\Repository\LikeRepository")
 *
 * @UniqueEntity(
 *     fields={"user", "post"},
 *     message="Ви вже вподобали цей запис"
 * )
 */
class Like
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="User",
     *      inversedBy="likes"
     * )
     * @SymfonyConstraint\NotBlank(
     *     message = "Авторизуйтеся, щоб вподобати цей запис"
     * )
     */
    private $user;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Post",
     *      inversedBy="likes"
     * )
     */
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }
}
