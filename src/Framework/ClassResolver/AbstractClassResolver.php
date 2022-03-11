<?php

declare(strict_types=1);

namespace Gacela\Framework\ClassResolver;

use Gacela\Framework\ClassResolver\ClassNameFinder\ClassNameFinderInterface;
use Gacela\Framework\ClassResolver\GlobalInstance\AnonymousGlobal;
use Gacela\Framework\ClassResolver\InstanceCreator\InstanceCreator;
use Gacela\Framework\Config;
use Gacela\Framework\Config\GacelaFileConfig\GacelaConfigFileInterface;
use function is_array;

abstract class AbstractClassResolver
{
    /** @var array<string,null|object> */
    private static array $cachedInstances = [];

    private ?ClassNameFinderInterface $classNameFinder = null;

    private ?GacelaConfigFileInterface $gacelaFileConfig = null;

    private ?InstanceCreator $instanceCreator = null;

    abstract public function resolve(object $callerClass): ?object;

    abstract protected function getResolvableType(): string;

    public function doResolve(object $callerClass): ?object
    {
        $classInfo = ClassInfo::fromObject($callerClass, $this->getResolvableType());
        $cacheKey = $classInfo->getCacheKey();

        $resolvedClass = $this->resolveCached($cacheKey);
        if (null !== $resolvedClass) {
            return $resolvedClass;
        }

        $resolvedClassName = $this->findClassName($classInfo);
        if (null === $resolvedClassName) {
            return null;
        }

        self::$cachedInstances[$cacheKey] = $this->createInstance($resolvedClassName);

        return self::$cachedInstances[$cacheKey];
    }

    private function resolveCached(string $cacheKey): ?object
    {
        return AnonymousGlobal::getByKey($cacheKey)
            ?? self::$cachedInstances[$cacheKey]
            ?? null;
    }

    private function findClassName(ClassInfo $classInfo): ?string
    {
        return $this->getClassNameFinder()->findClassName(
            $classInfo,
            $this->getPossibleResolvableTypes()
        );
    }

    private function getClassNameFinder(): ClassNameFinderInterface
    {
        if (null === $this->classNameFinder) {
            $this->classNameFinder = (new ClassResolverFactory())
                ->createClassNameFinder();
        }

        return $this->classNameFinder;
    }

    /**
     * Allow overriding gacela suffixes resolvable types.
     *
     * @return list<string>
     */
    private function getPossibleResolvableTypes(): array
    {
        $suffixTypes = $this->getGacelaConfigFile()->getSuffixTypes();

        $resolvableTypes = $suffixTypes[$this->getResolvableType()] ?? $this->getResolvableType();

        return is_array($resolvableTypes) ? $resolvableTypes : [$resolvableTypes];
    }

    private function createInstance(string $resolvedClassName): ?object
    {
        if (null === $this->instanceCreator) {
            $this->instanceCreator = new InstanceCreator($this->getGacelaConfigFile());
        }

        return $this->instanceCreator->createByClassName($resolvedClassName);
    }

    private function getGacelaConfigFile(): GacelaConfigFileInterface
    {
        if (null === $this->gacelaFileConfig) {
            $this->gacelaFileConfig = Config::getInstance()
                ->getFactory()
                ->createGacelaConfigFileFactory()
                ->createGacelaFileConfig();
        }

        return $this->gacelaFileConfig;
    }
}
