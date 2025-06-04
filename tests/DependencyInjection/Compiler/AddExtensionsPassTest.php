<?php

namespace SolidCloud\Bundle\MenuBundle\Tests\DependencyInjection\Compiler;

use SolidCloud\Bundle\MenuBundle\DependencyInjection\Compiler\AddExtensionsPass;
use Knp\Menu\MenuFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddExtensionsPassTest extends TestCase
{
    public function testProcessWithoutProviderDefinition(): void
    {
        $containerBuilder = new ContainerBuilder();
        (new AddExtensionsPass())->process($containerBuilder);

        self::assertFalse($containerBuilder->has('knp_menu.factory'));
    }

    public function testProcessWithAlias(): void
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->register('knp_menu.factory', MenuFactory::class);

        $containerBuilder->register('id', 'stdClass')
            ->addTag('knp_menu.factory_extension')
            ->addTag('knp_menu.factory_extension', ['priority' => 12]);

        $containerBuilder->register('foo', 'stdClass')
            ->addTag('knp_menu.factory_extension', ['priority' => -4]);

        $menuPass = new AddExtensionsPass();
        $menuPass->process($containerBuilder);

        self::assertEquals(
            [
                ['addExtension', [new Reference('id'), 0]],
                ['addExtension', [new Reference('id'), 12]],
                ['addExtension', [new Reference('foo'), -4]],
            ],
            $containerBuilder->getDefinition('knp_menu.factory')->getMethodCalls()
        );
    }

    public function testMissingAddExtension(): void
    {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->register('knp_menu.factory', 'SimpleMenuFactory');
        $containerBuilder->register('foo', 'stdClass')->addTag('knp_menu.factory_extension');

        $menuPass = new AddExtensionsPass();

        $this->expectException(InvalidConfigurationException::class);
        $menuPass->process($containerBuilder);
    }
}
