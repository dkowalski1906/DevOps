<?php
class Money
{
    public $amount;
    public $currency;

    public function __construct($am, $cu)
    {
        $this->amount = $am;
        $this->currency = $cu;
    }

    public function __toString()
    {
        return $this->amount . " " . $this->currency;
    }

    public function add(Money $that)
    {
        if ($this->currency !== $that->currency) {
        throw new Exception("Devises différentes");
        }
        return new Money($this->amount + $that->amount, $this->currency);
    }
}