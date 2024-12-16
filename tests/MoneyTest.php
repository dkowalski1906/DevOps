<?php
use PHPUnit\Framework\TestCase;
require_once ('Money.php');

class MoneyTest extends TestCase
{
    public function testAddEuros()
    {
        $m1 = new Money(20, 'EUR');
        $m2 = new Money(10, 'EUR');
        $expected = new Money(30, 'EUR');
        $this->assertEquals($expected, $m1->add($m2));
    }
}