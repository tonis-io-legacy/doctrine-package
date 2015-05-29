<?php

namespace Spiffy\DoctrinePackage;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Spiffy\Framework\AbstractPackage;
use Spiffy\Framework\Application;

class Package extends AbstractPackage
{
    public function bootstrap(Application $app)
    {
        AnnotationRegistry::registerLoader(
            function ($className) {
                return class_exists($className);
            }
        );
    }
}
