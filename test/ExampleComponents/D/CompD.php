<?php

namespace ExampleComponents\D;

use ExampleComponents\A\CompA;

class CompD
{
    public function getConfig()
    {
        return require __DIR__ . '/../C/config.php';
    }

    public function getCompCClass()
    {
        // keep full namespace for tests
        return \ExampleComponents\C\CompC::class;
    }
}