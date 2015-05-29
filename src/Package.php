<?php

namespace Tonis\DoctrinePackage;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Tonis\Mvc\Package\AbstractPackage;
use Tonis\Mvc\Tonis;

class Package extends AbstractPackage
{
    public function bootstrap(Tonis $tonis)
    {
        AnnotationRegistry::registerLoader(
            function ($className) {
                return class_exists($className);
            }
        );
    }
}
