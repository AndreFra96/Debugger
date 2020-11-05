<?php

declare(strict_types=1);

namespace AndreFra96\Debugger;

class ExampleTest extends \PHPUnit\Framework\TestCase
{
    public function testDebuggerConnection(){
        $debugger = new Debugger();
        $debugger->connect("localhost","root","","db_ordini");
        $this->assertTrue($debugger->connectionOk());
    }
    
    /**
     * Test that true does in fact equal true
     */
    public function testTrueIsTrue()
    {
        $this->assertTrue(true);
    }
}
