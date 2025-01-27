<?php

declare(strict_types=1);

namespace GacelaTest\Feature\Framework\ModuleWithoutDependencies\WithoutPrefix\Service;

final class HelloName
{
    public function greet(string $name): array
    {
        return ["Hello, $name from WithoutPrefix."];
    }
}
