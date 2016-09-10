<?php
namespace MF\Dbwatcher\Tests\Mock\Tasks;

use MF\Dbwatcher\Tasks\FactoryInterface;

class Factory implements FactoryInterface
{
    public function create(array $data)
    {
        return new Task($data);
    }
}