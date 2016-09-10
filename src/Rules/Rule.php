<?php
namespace MF\Dbwatcher\Rules;

interface Rule
{
    /**
     * @param array $conditionArgs Conditions for rule to match
     * @return $this
     */
    public function setConditions(array $conditionArgs);

    /**
     * Validates if data matches rule
     *
     * @param array $maxwellData Data from maxwell
     * @return bool
     */
    public function validate(array $maxwellData);
}