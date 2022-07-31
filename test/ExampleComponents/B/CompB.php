<?php

namespace ExampleComponents\B;

use ExampleComponents\C\CompC;

class CompB
{
    public function myFunc1()
    {
        $c = new CompC();
        return $c->myFunc1();
    }
}