<?php

namespace Shoplo\ShoploBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Shoplo\ShoploBundle\Security\Authentication\Token\HmacUserToken;

class HmacProvider implements AuthenticationProviderInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var string
     */
    private $secret;

    /**
     * @param UserProviderInterface $userProvider
     * @param string $secret
     */
    public function __construct(UserProviderInterface $userProvider, $secret)
    {
        $this->userProvider = $userProvider;
        $this->secret = $secret;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        /** @var HmacUserToken $token */
        if ($user && $this->validateDigest($token->getDigest(), $token->getPayload())) {
            $authenticatedToken = new HmacUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The HMAC authentication failed.');
    }

    /**
     * Validate HMAC digest
     *
     * @param string $digest
     * @param array $payload
     *
     * @see https://docs.shoplo.com/api/webhook
     *
     * @return bool
     */
    protected function validateDigest($digest, array $payload)
    {
        $algo = 'sha256';
        $data = http_build_query($payload);
        $key = $this->secret;
        $hash = hash_hmac($algo, $data, $key);
        $expected = base64_encode($hash);

        return hash_equals($expected, $digest);
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof HmacUserToken;
    }
}
