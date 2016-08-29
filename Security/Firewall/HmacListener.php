<?php

namespace Shoplo\ShoploBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Shoplo\ShoploBundle\Security\Authentication\Token\HmacUserToken;

class HmacListener implements ListenerInterface
{
    protected $tokenStorage;
    protected $authenticationManager;

    /**
     * {@inheritdoc}
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->headers->has('shoplo-shop-id') || !$request->headers->has('shoplo-hmac-sha256')) {
            return;
        }

        $token = new HmacUserToken();
        $token->setUser($request->headers->get('shoplo-shop-id'));
        $token->setDigest($request->headers->get('shoplo-hmac-sha256'));
        $token->setPayload($request->request->all());

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {
        }

        // By default deny authorization
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}
