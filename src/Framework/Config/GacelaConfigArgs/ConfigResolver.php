<?php

declare(strict_types=1);

namespace Gacela\Framework\Config\GacelaConfigArgs;

use Gacela\Framework\Config\ConfigReader\PhpConfigReader;
use Gacela\Framework\Config\ConfigReaderInterface;
use Gacela\Framework\Config\GacelaFileConfig\GacelaConfigItem;

final class ConfigResolver
{
    /** @var list<GacelaConfigItem> */
    private array $configItems = [];

    /**
     * @param class-string<ConfigReaderInterface>|ConfigReaderInterface $reader Define the reader class which will read and parse the config files
     * @param string $path define the path where Gacela will read all the config files
     * @param string $pathLocal define the path where Gacela will read the local config file
     */
    public function add(
        $reader,
        string $path = GacelaConfigItem::DEFAULT_PATH,
        string $pathLocal = GacelaConfigItem::DEFAULT_PATH_LOCAL
    ): self {
        $readerInstance = new PhpConfigReader();

        if (is_string($reader)) {
            /** @psalm-suppress MixedMethodCall */
            $readerInstance = new $reader();
            assert($readerInstance instanceof ConfigReaderInterface);
        }

        $this->configItems[] = new GacelaConfigItem($path, $pathLocal, $readerInstance);

        return $this;
    }

    /**
     * @return list<GacelaConfigItem>
     */
    public function resolve(): array
    {
        if (empty($this->configItems)) {
            return [GacelaConfigItem::withDefaults()];
        }

        return $this->configItems;
    }
}
