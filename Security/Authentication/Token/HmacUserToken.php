<?php

namespace Shoplo\ShoploBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class HmacUserToken extends AbstractToken
{
    /**
     * @var string
     */
    private $digest;

    /**
     * @var array
     */
    private $payload = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(array $roles = [])
    {
        parent::__construct($roles);

        $this->setAuthenticated(count($roles) > 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getDigest()
    {
        return $this->digest;
    }

    /**
     * @param string $digest
     */
    public function setDigest($digest)
    {
        $this->digest = $digest;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;
    }
}
