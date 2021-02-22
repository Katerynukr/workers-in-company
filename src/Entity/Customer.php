<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity(fields={"phone"}, message="Such phone number has already been used")
 * @UniqueEntity(fields={"email"}, message="Such phone email has already been used")
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message="Name field can not be empty!")
     * @Assert\Length(
     *      min = 2,
     *      max = 32,
     *      minMessage = "The name is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message="Surname field can not be empty!")
     * @Assert\Length(
     *      min = 2,
     *      max = 32,
     *      minMessage = "The surname is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The surname cannot be longer than {{ limit }} characters"
     * )
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=24)
     * @Assert\NotBlank(message="Phone field can not be empty!")
     * @Assert\Length(
     *      min = 5,
     *      max = 32,
     *      minMessage = "The phone is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The phone cannot be longer than {{ limit }} characters"
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Email field can not be empty!")
     * @Assert\Length(
     *      min = 5,
     *      max = 32,
     *      minMessage = "The email is too short. Minimum length is {{ limit }} characters",
     *      maxMessage = "The email cannot be longer than {{ limit }} characters"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Text field can not be empty!")
     */
    private $comment;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="Company id field cannot have zero or negative amount of pages")
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $company_id;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCompanyId(): ?int
    {
        return $this->company_id;
    }

    public function setCompanyId(int $company_id): self
    {
        $this->company_id = $company_id;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
