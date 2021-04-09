<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table("trick_videos")
 */
class TrickVideo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="trickVideos")
     * @ORM\JoinColumn(name="trick_id", referencedColumnName="id")
     */
    private $trick;


    public function getId(): int
    {
        return $this->id;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getTrick(): Trick
    {
        return $this->trick;
    }

    public function setTrick(Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}