<?php

declare(strict_types=1);

namespace GacelaTest\Integration\ModuleWithoutDependencies\WithPrefix;

use Gacela\AbstractFacade;

/**
 * @method WithPrefixFactory getFactory()
 */
final class WithPrefixFacade extends AbstractFacade implements WithPrefixFacadeInterface
{
    public function greet(string $name): array
    {
        return $this->getFactory()
            ->createServiceA()
            ->greet($name);
    }
}