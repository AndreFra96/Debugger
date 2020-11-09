<?php

declare(strict_types=1);

namespace AndreFra96\Debugger;

class MyDebugger
{
    private $tests;

    function __construct()
    {
        $tests = array();
    }

    function loadTestsFromFile($file)
    {
        try {
            $connection = fopen($file, "r");
            while (!feof($file)) {
                $line = fgets($file);
                echo $line . "<br>";
            }
        } catch (\ErrorException $e) {
            throw new \AndreFra96\Debugger\FileNotFoundException();
        } finally {
            fclose($connection);
        }
    }
}
