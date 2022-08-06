<?php

namespace ExampleComponentsCircled\C;

use ExampleComponentsCircled\A\CompA;

class CompC
{
    public function myFunc1()
    {
        $a = new CompA();
        return $a->myFunc2();
    }
}