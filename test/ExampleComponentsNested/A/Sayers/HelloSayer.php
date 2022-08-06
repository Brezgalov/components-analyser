<?php

namespace ExampleComponentsNested\A\Sayers;

use ExampleComponentsNested\B\Words\Greetings\Hello;

class HelloSayer
{
    public function sayHello()
    {
        echo (string)(new Hello());
    }
}