<?php

namespace ExampleComponentsCircled\A;

use ExampleComponentsCircled\B\CompB;

class CompA
{
    public function myFunc1()
    {
        $b = new CompB();

        return $b->myFunc1();
    }

    public function myFunc2()
    {
        echo "pzdc";
    }
}