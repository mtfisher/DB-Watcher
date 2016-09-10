<?php

namespace MF\Dbwatcher\Tasks;


interface ParserInterface
{
    /**
     * Get array of tasks from the parser
     * @return array
     */
    public function parse();
}