<?php

namespace SolidCloud\Bundle\MenuBundle;

use SolidCloud\Bundle\MenuBundle\DependencyInjection\Compiler\AddExtensionsPass;
use SolidCloud\Bundle\MenuBundle\DependencyInjection\Compiler\AddProvidersPass;
use SolidCloud\Bundle\MenuBundle\DependencyInjection\Compiler\AddRenderersPass;
use SolidCloud\Bundle\MenuBundle\DependencyInjection\Compiler\AddVotersPass;
use SolidCloud\Bundle\MenuBundle\DependencyInjection\Compiler\MenuBuilderPass;
use SolidCloud\Bundle\MenuBundle\DependencyInjection\Compiler\RegisterMenusPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SolidMenuBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterMenusPass());
        $container->addCompilerPass(new MenuBuilderPass());
        $container->addCompilerPass(new AddExtensionsPass());
        $container->addCompilerPass(new AddProvidersPass());
        $container->addCompilerPass(new AddRenderersPass());
        $container->addCompilerPass(new AddVotersPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
