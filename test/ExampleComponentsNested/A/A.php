<?php

namespace ExampleComponentsNested\A;

use ExampleComponentsNested\A\Sayers\HelloSayer;

class A
{
    public function echoHello()
    {
        (new HelloSayer())->sayHello();
    }

    /**
     * @todo: parsers should find this as dependency from C or trigger and alert as an option
     *
     * @return mixed
     */
    public function getMissingCFileContents()
    {
        return require __DIR__ . '/../C/Files/MyMissingFile.php';
    }

    /**
     * @todo: parsers should find this as dependency from C
     *
     * @return mixed
     */
    public function getExistingCFileContents()
    {
        return require __DIR__ . '/../C/Files/MyExistingFile.php';
    }
}