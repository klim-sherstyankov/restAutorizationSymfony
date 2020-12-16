<?php

namespace App\Entity;

use App\Repository\DataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DataRepository::class)
 */
class Data
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $patronymic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="smallint")
     */
    private $public;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dtCreate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dtUpdate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $subscription;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = self::createPassword($password);

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPublic(): ?int
    {
        return $this->public;
    }

    public function setPublic(int $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getDtCreate(): ?\DateTimeInterface
    {
        return $this->dtCreate;
    }

    public function setDtCreate(?\DateTimeInterface $dtCreate): self
    {
        $this->dtCreate = $dtCreate;

        return $this;
    }

    public function getDtUpdate(): ?\DateTimeInterface
    {
        return $this->dtUpdate;
    }

    public function setDtUpdate(?\DateTimeInterface $dtUpdate): self
    {
        $this->dtUpdate = $dtUpdate;

        return $this;
    }
    /**
     * generate Password
     * @param string|null $password
     * @return string
     */
    public static function createPassword(string $password = null): string
    {
        $password = $password ?: uniqid();

        return password_hash($password, PASSWORD_DEFAULT);
    }
    /**
     * @param string $password
     * @return bool
     */
    public function verifyPassword($password): bool
    {
        return password_verify($password, $this->password);
    }


    public function getSubscription(): ?bool
    {
        return $this->subscription;
    }

    public function setSubscription(?bool $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }
}
