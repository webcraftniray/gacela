<?php

declare(strict_types=1);

namespace GacelaTest\Integration\MissingFile\MissingDependencyProviderFile;

use Gacela\AbstractFactory;

final class Factory extends AbstractFactory
{
    public function createDomainService(): void
    {
        $this->getProvidedDependency('non-existing-service');
    }
}
