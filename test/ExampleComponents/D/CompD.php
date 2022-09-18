<?php

namespace ExampleComponents\D;

// unused dependency counts anyway
use ExampleComponents\B\CompB;

class CompD
{
    public function getConfig()
    {
        return require __DIR__ . '/../C/config.php';
    }

    public function getCompCClass()
    {
        // @todo: make it count. Dependency not visible yet
        // keep full namespace for tests
        return \ExampleComponents\C\CompC::class;
    }
}