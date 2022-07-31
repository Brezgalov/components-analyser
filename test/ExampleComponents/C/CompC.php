<?php

namespace ExampleComponents\C;

use ExampleComponents\A\CompA;

class CompC
{
    public function myFunc1()
    {
        $a = new CompA();
        return $a->myFunc2();
    }
}