<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table("trick_groups")
 * @UniqueEntity(
 *     fields="name",
 *     message="Ce groupe existe déjà."
 * )
 */
class TrickGroup
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
     *     message="Veuillez saisir un nom."
     * )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Trick", mappedBy="groupTrick")
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