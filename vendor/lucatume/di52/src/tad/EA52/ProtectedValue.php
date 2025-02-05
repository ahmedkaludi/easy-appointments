<?php

class tad_EA52_ProtectedValue
{

    /**
     * @var mixed
     */
    protected $value;

    /**
     * tad_EA52_ProtectedValue constructor.
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}