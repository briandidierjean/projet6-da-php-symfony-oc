<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TrickGroup", inversedBy="tricks")
     * @ORM\JoinColumn(name="trick_group_id", referencedColumnName="id")
     */
    private $trickGroup;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tricks")
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
     * @ORM\Column(type="datetime")
     */
    private $updateDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $mainPhoto;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TrickPhoto", mappedBy="trick")
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TrickVideo", mappedBy="trick")
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="trick")
     */
    private $messages;

    public function __construct() {
        $this->creationDate = new \DateTime();
        $this->updateDate = $this->creationDate;
        $this->photos = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTrickGroup()
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

    public function getUpdateDate(): \DateTime
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTime $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getMainPhoto()
    {
        return $this->mainPhoto;
    }

    public function setMainPhoto($mainPhoto): self
    {
        $this->mainPhoto = $mainPhoto;

        return $this;
    }

    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function setPhotos(Collection $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    public function addPhoto(TrickPhoto $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
        }

        return $this;
    }

    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(TrickVideo $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
        }

        return $this;
    }

    public function setVideos(Collection $videos): self
    {
        $this->videos = $videos;

        return $this;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function setMessages(Collection $messages): self
    {
        $this->messages = $messages;

        return $this;
    }

}