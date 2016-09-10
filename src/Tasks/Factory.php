<?php

namespace MF\Dbwatcher\Tasks;

class Factory implements FactoryInterface
{
    const ACTION_KEY = 'actions';
    const RULE_KEY = 'rules';

    private $actionFactory;
    private $ruleFactory;

    /**
     * Factory constructor.
     * @param \Mf\Dbwatcher\Actions\FactoryInterface $actionFactory
     * @param \MF\Dbwatcher\Rules\FactoryInterface $ruleFactory
     */
    public function __construct(
        \Mf\Dbwatcher\Actions\FactoryInterface $actionFactory,
        \MF\Dbwatcher\Rules\FactoryInterface $ruleFactory
    )
    {
        $this->actionFactory = $actionFactory;
        $this->ruleFactory = $ruleFactory;
    }

    /**
     * Create a task from a node in the main config
     * @param array $data
     * @return Task
     */
    public function create(array $data)
    {
        $task = new Task();

        if(array_key_exists(self::RULE_KEY, $data)) {
            foreach ($data[self::RULE_KEY] as $class=>$args) {
                $args = (is_null($args)? []: $args);
                $rule = $this->ruleFactory->create($class, $args);
                $task->addRule($rule);
            }
        }

        if(array_key_exists(self::ACTION_KEY, $data)) {
            foreach ($data[self::ACTION_KEY] as $class=>$args) {
                $args = (is_null($args)? []: $args);
                $action = $this->actionFactory->create($class, $args);
                $task->addAction($action);
            }
        }

        return $task;
    }
}