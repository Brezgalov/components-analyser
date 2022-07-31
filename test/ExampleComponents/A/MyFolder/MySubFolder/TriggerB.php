<?php

namespace ExampleComponents\A\MyFolder;

use ExampleComponents\B\CompB;

class TriggerB
{
    public function run()
    {
        return new CompB();
    }
}