<?php

namespace Olenaza\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
     */
    private $user;

    /**
     * @ORM\ManyToOne(
     *      targetEntity="Post",
     *      inversedBy="likes"
     * )
     */
    private $post;

    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
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
}
