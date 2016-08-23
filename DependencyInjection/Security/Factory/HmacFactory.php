<?php

namespace Shoplo\ShoploBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class HmacFactory implements SecurityFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.wsse.' . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('shoplo.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProvider));

        $listenerId = 'security.authentication.listener.wsse.' . $id;
        $container->setDefinition($listenerId, new DefinitionDecorator('shoplo.security.authentication.listener'));

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return 'pre_auth';
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'shoplo_hmac';
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(NodeDefinition $node)
    {
    }
}
