<?php

namespace ExampleComponentsCircled\A\MyFolder;

use ExampleComponentsCircled\B\CompB;

class TriggerB
{
    public function run()
    {
        return new CompB();
    }
}