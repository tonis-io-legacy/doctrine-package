<?php

namespace Tonis\DoctrinePackage;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Persistence\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Persistence\Mapping\Driver\FileDriver;
use Interop\Container\ContainerInterface;
use Tonis\Di\ServiceFactoryInterface;

final class DriverFactory implements ServiceFactoryInterface
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
     * @param ContainerInterface $di
     * @throws \RuntimeException
     * @return \Doctrine\Common\Persistence\Mapping\Driver\MappingDriver
     */
    public function createService(ContainerInterface $di)
    {
        $spec = $this->spec;

        if (is_string($spec)) {
            return $di->get($spec['service']);
        }

        if (!isset($spec['class'])) {
            throw new \RuntimeException('Every driver configuration must specify a class');
        }

        $parents = class_parents($spec['class']);

        if (isset($parents[AnnotationDriver::class])) {
            return $this->createAnnotationDriver($spec);
        } elseif (isset($parents[FileDriver::class])) {
            return $this->createFileDriver($spec);
        }

        throw new \RuntimeException(sprintf('Could not determine type from class: "%s"', $spec['class']));
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
