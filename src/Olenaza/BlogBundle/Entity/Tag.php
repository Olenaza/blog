<?php

namespace Olenaza\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="Olenaza\BlogBundle\Repository\TagRepository")
 *
 * @UniqueEntity("name")
 *
 * @ExclusionPolicy("none")
 */
class Tag
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name= "`name`", type="string", length=255, unique=true)
     *
     * @SymfonyConstraint\NotBlank()
     * @SymfonyConstraint\Type("string")
     * @SymfonyConstraint\Length(
     *      min=2,
     *      max=255,
     *      minMessage="The tag name must be at least 2 characters long",
     *      maxMessage="The tag name cannot be longer than 255 characters"
     * )
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags")
     *
     * @Exclude()
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ?: '';
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Post $post
     *
     * @return Tag
     */
    public function addPost(Post $post)
    {
        //$this->posts[] = $post;
        $this->posts->add($post);

        return $this;
    }

    /**
     * @param Post $post
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
