<?php

namespace Tonis\DoctrinePackage;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Tonis\Web\Package\AbstractPackage;
use Tonis\Web\App;

class Package extends AbstractPackage
{
    public function bootstrap(App $tonis)
    {
        AnnotationRegistry::registerLoader(
            function ($className) {
                return class_exists($className);
            }
        );
    }
}
