<?php

namespace App\Entity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
*/

use PhpParser\Node\Expr\Array_;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=254, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     */
    private $roles;

    /**
     * @ORM\OneToOne(targetEntity="Pilot")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id", unique=true)
     */
    protected $pilot;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * Set pilot
     *
     * @param \App\Entity\Pilot $pilot
     * @return User
     */
    public function setPilot(Pilot $pilot)
    {
        $this->pilot = $pilot;

        return $this;
    }

    /**
     * Get pilot
     *
     * @return \App\Entity\Pilot
     */
    public function getPilot()
    {
        return $this->pilot;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
//        return $serializer->deserialize($this->roles, , 'json');
        return unserialize($this->roles);
//        return [
//            'ROLE_ADMIN'
//        ];
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->roles
        ]);
    }

    public function unserialize($serialized)
    {
        list($this->id,
            $this->username,
            $this->password,
            $this->roles) = unserialize($serialized);
    }

    public function __toString()
    {
        return $this->getPilot()?$this->getPilot()->getName():$this->getUsername();
    }
}
