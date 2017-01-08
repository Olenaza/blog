<?php

namespace Olenaza\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as SymfonyConstraint;

/**
 * @ORM\Table(name="about_page")
 * @ORM\Entity(repositoryClass="Olenaza\BlogBundle\Repository\AboutPageRepository")
 */
class AboutPage
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     *
     * @SymfonyConstraint\NotBlank()
     * @SymfonyConstraint\Type("string")
     * @SymfonyConstraint\Length(
     *      min=10
     * )
     */
    private $text = 'Author info is currently unavailable';

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $text
     *
     * @return AboutPage
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
}
