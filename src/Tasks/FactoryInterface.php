<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 04/09/16
 * Time: 15:21
 */

namespace MF\Dbwatcher\Tasks;


interface FactoryInterface
{
    /**
     * Create a task from a node in the main config
     * @param array $data
     * @return TaskInterface
     */
    public function create(array $data);
}