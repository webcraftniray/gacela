<?php

declare(strict_types=1);

namespace Gacela;

use Gacela\ClassResolver\Config\ConfigNotFoundException;
use Gacela\ClassResolver\Config\ConfigResolver;

trait ConfigResolverAwareTrait
{
    private ?AbstractConfig $config = null;

    protected function getConfig(): AbstractConfig
    {
        if ($this->config === null) {
            $this->config = $this->resolveConfig();
        }

        return $this->config;
    }

    /**
     * @throws ConfigNotFoundException
     */
    private function resolveConfig(): AbstractConfig
    {
        $resolver = new ConfigResolver();
        return $resolver->resolve($this);
    }
}
