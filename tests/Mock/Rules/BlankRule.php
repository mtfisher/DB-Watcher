<?php
namespace MF\Dbwatcher\Tests\Mock\Rules;

use MF\Dbwatcher\Rules\Rule;

class BlankRule implements Rule
{
    private $return;
    /**
     * BlankRule constructor.
     * @param bool $return
     */
    public function __construct($return = true)
    {
        $this->return = $return;
    }

    /**
     * @param array $conditionArgs Conditions for rule to match
     * @return $this
     */
    public function setConditions(array $conditionArgs)
    {
        return $this;
    }

    /**
     * Validates if data matches rule
     *
     * @param array $maxwellData Data from maxwell
     * @return bool
     */
    public function validate(array $maxwellData)
    {
        return $this->return;
    }
}