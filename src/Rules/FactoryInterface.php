<?php
namespace MF\Dbwatcher\Rules;

interface FactoryInterface
{
    /**
     * @param string $class
     * @param array $conditions
     * @return Rule
     */
    public function create($class, array $conditions);
}