<?php

namespace Brammm\CommonBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RenderersPass implements CompilerPassInterface
{

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        // collect all tagged services in the entire project
        $taggedServiceIds = $container ->findTaggedServiceIds('response_renderer');
        $objectRendererDefinition = $container->getDefinition('brammm_common.view_listener');
        foreach ($taggedServiceIds as $serviceId => $tags) {
            // services can have many tag elements with the same tag name
            foreach ($tags as $tagAttributes) {
                // call addRenderer() to register this specific renderer
                $objectRendererDefinition->addMethodCall('addRenderer', [
                    $tagAttributes['type'],
                    new Reference($serviceId),
                ]);
            }
        }
    }


}
