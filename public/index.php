<?php


require __DIR__ . '/../vendor/autoload.php';

$kernel = new Framework\Core\kernel();

$kernel->makeService('Router')->handle();