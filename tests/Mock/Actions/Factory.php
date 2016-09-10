<?php

namespace MF\Dbwatcher\Tests\Mock\Actions;

use MF\Dbwatcher\Actions\FactoryInterface;

class Factory implements FactoryInterface
{
    public $arguments = [];
    public $class = '';

    public function create($class, array $arguments)
    {
        $this->class = $class;
        $this->arguments = $arguments;
        return new BlankAction();
    }
}