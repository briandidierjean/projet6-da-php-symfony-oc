<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 * @UniqueEntity(
 *     fields="email",
 *     message="Cette adresse e-mail est déjà utilisée."
 * )
 * @UniqueEntity(
 *     fields="username",
 *     message="Ce pseudo est déjà utilisé."
 * )
 */
class User implements UserInterface
{
    public const PENDING_STATUS = 0;
    public const VALIDATED_STATUS = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank(
     *     message="Veuillez choisir un pseudo"
     * )
     * @Assert\Length(
     *     min = 3,
     *     max = 50,
     *     minMessage="Votre pseudo doit contenir au moins {{ limit }} caractères.",
     *     maxMessage="Votre pseudo ne doit pas dépasser {{ limit }} caractères"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\Email(
     *     message = "L'adresse e-mail '{{ value }}' n'est pas une adresse e-mail valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\Regex(
     *     pattern="/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/",
     *     message="Veuillez saisir un mot de passe d'au moins 8 charactères et contenant 1 minuscule, 1 majuscule et 1 chiffre."
     * )
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Trick", mappedBy="user")
     */
    private $tricks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="user")
     */
    private $messages;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $status = self::PENDING_STATUS;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $registrationToken;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $resetPasswordToken;

    public function __construct() {
        $this->tricks = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getTricks(): Collection
    {
        return $this->tricks;
    }

    public function setTricks(Collection $tricks): self
    {
        $this->tricks = $tricks;

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

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $resetPasswordToken): self
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    public function getRegistrationToken(): ?string
    {
        return $this->registrationToken;
    }

    public function setRegistrationToken(string $registrationToken): self
    {
        $this->registrationToken = $registrationToken;

        return $this;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }

}
