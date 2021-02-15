<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table("tricks")
 * @UniqueEntity(
 *     fields="name",
 *     message="Cette figure existe déjà."
 * )
 */
class Trick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickGroup", inversedBy="trick")
     * @ORM\JoinColumn(name="trick_group_id", referencedColumnName="id")
     */
    private $trickGroup;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="trick")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank(
     *     message="Veuillez saisir un nom."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *     message="Veuillez saisir une description."
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TrickPhoto", mappedBy="tricks")
     */
    private $photos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TrickVideo", mappedBy="tricks")
     */
    private $videos;

    public function __construct() {
        $this->photos = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTrickGroup(): TrickGroup
    {
        return $this->trickGroup;
    }

    public function setTrickGroup(TrickGroup $trickGroup): self
    {
        $this->trickGroup = $trickGroup;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTime $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getPhotos(): ArrayCollection
    {
        return $this->photos;
    }

    public function setPhotos(ArrayCollection $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    public function getVideos(): ArrayCollection
    {
        return $this->videos;
    }

    public function setVideos(ArrayCollection $videos): self
    {
        $this->videos = $videos;

        return $this;
    }
}