<?php

namespace Olenaza\BlogBundle\Entity;

use Symfony\Component\Validator\Constraints as SymfonyConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="Olenaza\BlogBundle\Repository\PostRepository")
 *
 * @UniqueEntity(
 *     "coverImage",
 *     message="This image is already used as a cover image in the other post"
 * )
 * @UniqueEntity(
 *     fields={"title", "subtitle"},
 *     errorPath="subtitle",
 *     message="This combination of title and subtitle already exists in the other post"
 * )
 * @UniqueEntity(
 *     fields={"title", "subtitle"},
 *     errorPath="subtitle",
 *     message="This combination of title and subtitle already exists in the other post"
 * )
 * @SymfonyConstraint\GroupSequence({"Post", "Strict"})
 */
class Post
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @SymfonyConstraint\NotBlank()
     * @SymfonyConstraint\Type("string")
     * @SymfonyConstraint\Length(
     *      min=2,
     *      max=255,
     *      minMessage="The post title must be at least 2 characters long",
     *      maxMessage="The post title cannot be longer than 255 characters"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @SymfonyConstraint\Type("string")
     * @SymfonyConstraint\Length(
     *      min=2,
     *      max=255,
     *      minMessage="The post subtitle must be at least 2 characters long",
     *      maxMessage="The post subtitle cannot be longer than 255 characters"
     * )
     */
    private $subtitle;

    /**
     * @ORM\Column(type="text")
     *
     * @SymfonyConstraint\NotBlank()
     * @SymfonyConstraint\Type("string")
     * @SymfonyConstraint\Length(
     *      min=2,
     *      minMessage="The post text must be at least 2 characters long"
     * )
     */
    private $text;

    /**
     * @ORM\Column(type="text")
     *
     * @SymfonyConstraint\NotBlank()
     * @SymfonyConstraint\Url(
     *     checkDNS = true
     * )
     */
    private $coverImage;

    /**
     * @ORM\Column(type="boolean")
     *
     * @SymfonyConstraint\NotNull()
     * @SymfonyConstraint\Type("bool")
     */
    private $published;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $publishedOn;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @SymfonyConstraint\Date()
     * @SymfonyConstraint\Range(
     *      min="today",
     *      max="+365 days",
     *      minMessage="The post publication date can't be earlier than today",
     *      maxMessage="The post publication date can't be later than 365 days from today"
     * )
     */
    private $publishOn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $beginning;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts")
     * @ORM\JoinTable(name="posts_tags")
     *
     * @SymfonyConstraint\Valid
     *
     * @SymfonyConstraint\Count(
     *      min="1",
     *      minMessage="You must specify at least one tag"
     * )
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $subtitle
     *
     * @return Post
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $text
     *
     * @return Post
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $coverImage
     *
     * @return Post
     */
    public function setCoverImage($coverImage)
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    /**
     * @return string
     */
    public function getCoverImage()
    {
        return $this->coverImage;
    }

    /**
     * @param bool $published
     *
     * @return Post
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param \DateTime $publishedOn
     *
     * @return Post
     */
    public function setPublishedOn($publishedOn)
    {
        $this->publishedOn = $publishedOn;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedOn()
    {
        return $this->publishedOn;
    }

    /**
     * @param string $slug
     *
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $this->title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return Post
     */
    public function setBeginning()
    {
        $this->beginning = 'Beginnning';

        return $this;
    }

    /**
     * @return string
     */
    public function getBeginning()
    {
        return $this->beginning;
    }

    /**
     * @param \DateTime $publishOn
     *
     * @return Post
     */
    public function setPublishOn($publishOn)
    {
        $this->publishOn = $publishOn;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublishOn()
    {
        return $this->publishOn;
    }

    public function addTag(Tag $tag)
    {
        $tag->addPost($this);
        $this->tags[] = $tag;
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @SymfonyConstraint\IsTrue(message="The subtitle cannot match the title of the post", groups={"Strict"})
     */
    public function isSubtitleDifferentThanTitle()
    {
        return $this->title !== $this->subtitle;
    }

    /**
     * Get published.
     *
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
    }
}
