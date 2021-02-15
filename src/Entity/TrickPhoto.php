<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table("trick_photos")
 * @UniqueEntity(
 *     fields="name",
 *     message="Ce nom de fichier existe déjà."
 * )
 */
class TrickPhoto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank(
     *     message="Veuillez saisir un nom de fichier."
     * )
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Trick", inversedBy="trickPhotos")
     * @JoinTable(name="trick_photos_tricks")
     */
    private $tricks;

    public function __construct() {
        $this->tricks = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTricks(): ArrayCollection
    {
        return $this->tricks;
    }

    public function setTricks(ArrayCollection $tricks): self
    {
        $this->tricks = $tricks;

        return $this;
    }
}