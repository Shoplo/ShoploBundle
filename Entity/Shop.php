<?php

namespace Shoplo\ShoploBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Shop
 *
 * @ORM\Table(name="shoplo_shops")
 * @ORM\Entity(repositoryClass="Shoplo\ShoploBundle\Repository\ShopRepository")
 */
class Shop implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function getId() : int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return [
            'ROLE_USER',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}
