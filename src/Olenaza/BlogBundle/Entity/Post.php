<?php

namespace Olenaza\BlogBundle\Entity;

use Symfony\Component\Validator\Constraints as SymfonyConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;

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
 * @SymfonyConstraint\GroupSequence({"Post", "Strict"})
 */
class Post
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"list", "details"})
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
     * @Groups({"list", "details"})
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
     * @Groups({"details"})
     */
    private $subtitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @SymfonyConstraint\NotBlank(groups={"Published"})
     * @SymfonyConstraint\Type("string")
     * @SymfonyConstraint\Length(
     *      min=2,
     *      minMessage="The post text must be at least 2 characters long"
     * )
     * @Groups({"details"})
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @SymfonyConstraint\NotBlank(groups={"Published"})
     * @SymfonyConstraint\Type("string")
     * @SymfonyConstraint\Length(
     *      min=2,
     *      max=255,
     *      minMessage="The post beginning must be at least 2 characters long",
     *      maxMessage="The post beginning cannot be longer than 255 characters"
     * )
     * @Groups({"list"})
     */
    private $beginning;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @SymfonyConstraint\NotBlank(groups={"Published"})
     * @SymfonyConstraint\Url(
     *     checkDNS = true
     * )
     * @Groups({"list"})
     */
    private $coverImage;

    /**
     * @ORM\Column(type="boolean")
     *
     * @SymfonyConstraint\NotNull()
     * @SymfonyConstraint\Type("bool")
     */
    private $forPublication;

    /**
     * @ORM\Column(type="boolean")
     *
     * @SymfonyConstraint\NotNull()
     * @SymfonyConstraint\Type("bool")
     */
    private $published = false;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @SymfonyConstraint\Date()
     * @SymfonyConstraint\Range(
     *      min="today",
     *      max="+365 days",
     *      minMessage="The post publication date can't be earlier than today",
     *      maxMessage="The post publication date can't be later than 365 days from today"
     * )
     * @Groups({"list", "details"})
     */
    private $publishedOn;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"list", "details"})
     */
    private $slug;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="Tag",
     *     inversedBy="posts",
     *     cascade={"persist"}
     * )
     * @ORM\JoinTable(name="posts_tags")
     *
     * @SymfonyConstraint\Count(
     *     min="1",
     *     minMessage="You must specify at least one tag",
     *     groups={"Published"}
     * )
     * @Groups({"details"})
     * @MaxDepth(2)
     */
    private $tags;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="Category",
     *     inversedBy="posts",
     *     cascade={"persist"}
     * )
     *
     * @SymfonyConstraint\Count(
     *     min="1",
     *     minMessage="You must specify at least one category",
     *     groups={"Published"}
     * )
     * @MaxDepth(2)
     */
    private $categories;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Comment",
     *      mappedBy="post",
     *      orphanRemoval=true
     * )
     * @ORM\OrderBy({"publishedAt" = "DESC"})
     * @Groups({"details"})
     * @MaxDepth(2)
     */
    private $comments;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Like",
     *      mappedBy="post",
     *      orphanRemoval=true
     * )
     * @Groups({"details"})
     * @MaxDepth(2)
     */
    private $likes;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
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
     * @param string $beginning
     *
     * @return Post
     */
    public function setBeginning($beginning)
    {
        $this->beginning = $beginning;

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
     * @param bool $forPublication
     *
     * @return Post
     */
    public function setForPublication($forPublication)
    {
        $this->forPublication = $forPublication;

        return $this;
    }

    /**
     * @return bool
     */
    public function isForPublication()
    {
        return $this->forPublication;
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
        $this->slug = $slug;

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
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return ArrayCollection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $tag->addPost($this);

        $this->tags->add($tag);
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category)
    {
        $category->addPost($this);

        $this->categories->add($category);
    }

    /**
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * @param Comment $comment
     *
     * @return Post
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * @param Like $like
     *
     * @return Post
     */
    public function addLike(Like $like)
    {
        $this->likes[] = $like;

        return $this;
    }

    /**
     * @param Like $like
     */
    public function removeLike(Like $like)
    {
        $this->likes->removeElement($like);
    }

    /**
     * @SymfonyConstraint\IsTrue(message="The subtitle cannot match the title of the post", groups={"Strict"})
     */
    public function isSubtitleDifferentThanTitle()
    {
        return $this->title !== $this->subtitle;
    }

    /**
     * Determine validation groups depending on whether the post is set to be published.
     *
     * @param FormInterface $form
     *
     * @return array
     */
    public static function determineValidationGroups(FormInterface $form)
    {
        $data = $form->getData();

        if (true === $data->isPublished()) {
            return ['Default', 'Published'];
        }

        return ['Default'];
    }
}
