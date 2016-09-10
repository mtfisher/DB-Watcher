<?php
namespace MF\Dbwatcher\Tests\Mock\Rules;

use MF\Dbwatcher\Rules\FactoryInterface;

class Factory implements FactoryInterface
{
    public $conditions = [];
    public $class = '';

    public function create($class, array $conditions)
    {
        $this->class = $class;
        $this->conditions = $conditions;
        return new BlankRule();
    }
}