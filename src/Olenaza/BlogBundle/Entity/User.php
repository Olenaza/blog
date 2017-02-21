<?php

namespace Olenaza\BlogBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    private $facebookId;

    private $facebookAccessToken;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Like",
     *      mappedBy="post",
     *      orphanRemoval=true
     * )
     */
    private $likes;

    public function __construct()
    {
        parent::__construct();
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
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookAccessToken
     *
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @return ArrayCollection
     */
    public function getLikes()
    {
        return $this->likes;
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
}
