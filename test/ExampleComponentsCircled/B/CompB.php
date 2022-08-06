<?php

namespace ExampleComponentsCircled\B;

use ExampleComponentsCircled\C\CompC;

class CompB
{
    public function myFunc1()
    {
        $c = new CompC();
        return $c->myFunc1();
    }
}