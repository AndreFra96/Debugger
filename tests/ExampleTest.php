<?php

declare(strict_types=1);

namespace AndreFra96\Debugger;

class ExampleTest extends \PHPUnit\Framework\TestCase
{
    public function testDebuggerConstructor(){
        $debugger = new Debugger();
        $debugger -> __toString();
        $this->assertTrue(true);
    }
    /**
     * Test that true does in fact equal true
     */
    public function testTrueIsTrue()
    {
        $this->assertTrue(true);
    }
}
