<?php

namespace DataBaseBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="DataBaseBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

     /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Entrez votre prénom", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="Le prénom est trop court.",
     *     maxMessage="Le prénom est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Entrez votre nom", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="Le nom est trop court.",
     *     maxMessage="Le nom est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Choisissez votre rôle", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     max=255,
     *     groups={"Registration", "Profile"}
     * )
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Renseignez votre établissement", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="Le nom de l'établissement est trop court.",
     *     maxMessage="Le nom de l'établissement est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $school;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setfirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getfirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set school
     *
     * @param string $school
     *
     * @return User
     */
    public function setSchool($school)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return string
     */
    public function getSchool()
    {
        return $this->school;
    }
}
