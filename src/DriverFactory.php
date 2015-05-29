<?php

namespace Spiffy\DoctrinePackage;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Spiffy\Inject\Injector;
use Spiffy\Inject\ServiceFactory;

final class DriverFactory implements ServiceFactory
{
    /**
     * @var array
     */
    private $spec;

    /**
     * @param array $spec
     */
    public function __construct(array $spec)
    {
        $this->spec = $spec;
    }

    /**
     * @param Injector $i
     * @throws \RuntimeException
     * @return \Doctrine\Common\Persistence\Mapping\Driver\MappingDriver
     */
    public function createService(Injector $i)
    {
        $spec = $this->spec;

        if (is_string($spec)) {
            return $i->nvoke($spec['service']);
        }

        if (!isset($spec['class'])) {
            throw new \RuntimeException('Every driver configuration must specify a class');
        }

        $type = isset($spec['type']) ? $spec['type'] : null;

        switch ($type) {
            case 'file':
                return $this->createFileDriver($spec);
            case 'annotation':
                return $this->createAnnotationDriver($spec);
        }
        throw new \RuntimeException('Missing or invalid type: expected "annotation" or "file"');
    }

    /**
     * @param array $spec
     * @throws \RuntimeException
     */
    private function createAnnotationDriver(array $spec)
    {
        $class = $spec['class'];

        if (!isset($spec['paths'])) {
            throw new \RuntimeException('Missing paths for annotation driver');
        }

        return new $class(
            new CachedReader(new AnnotationReader(), new ArrayCache()),
            (array) $spec['paths']
        );
    }

    /**
     * @param array $spec
     */
    private function createFileDriver(array $spec)
    {
        return new $spec['class'];
    }
}
