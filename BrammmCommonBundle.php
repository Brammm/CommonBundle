<?php

namespace Brammm\CommonBundle;

use Brammm\CommonBundle\DependencyInjection\Compiler\RenderersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BrammmCommonBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RenderersPass());
    }
}