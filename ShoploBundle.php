<?php

namespace Shoplo\ShoploBundle;

use Shoplo\ShoploBundle\DependencyInjection\Security\Factory\HmacFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ShoploBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new HmacFactory());
    }
}
