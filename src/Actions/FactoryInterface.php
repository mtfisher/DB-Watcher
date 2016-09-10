<?php
namespace MF\Dbwatcher\Actions;

interface FactoryInterface
{
    /**
     * @param string $class
     * @param array $arguments
     * @return Action
     */
    public function create($class, array $arguments);
}