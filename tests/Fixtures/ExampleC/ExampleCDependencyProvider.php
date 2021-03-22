<?php

declare(strict_types=1);

namespace GacelaTest\Fixtures\ExampleC;

use Gacela\AbstractDependencyProvider;
use Gacela\Container\Container;
use GacelaTest\Fixtures\ExampleA\ExampleAFacade;
use GacelaTest\Fixtures\ExampleB\ExampleBFacade;

final class ExampleCDependencyProvider extends AbstractDependencyProvider
{
    public const FACADE_A = 'FACADE_A';
    public const FACADE_B = 'FACADE_B';

    public function provideModuleDependencies(Container $container): void
    {
        $this->addFacadeA($container);
        $this->addFacadeB($container);
    }

    private function addFacadeA(Container $container): void
    {
        $container->set(self::FACADE_A, new ExampleAFacade());
    }

    private function addFacadeB(Container $container): void
    {
        $container->set(self::FACADE_B, new ExampleBFacade());
    }
}