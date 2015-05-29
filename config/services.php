<?php

return [
    // cache
    'doctrine.cache.array' => ['Doctrine\Common\Cache\ArrayCache'],
    'doctrine.cache.file' => ['Doctrine\Common\Cache\FileCache', ['cache/doctrine/file']],
    'doctrine.cache.filesystem' => ['Doctrine\Common\Cache\FilesystemCache', ['cache/doctrine/filesystem']]
];
