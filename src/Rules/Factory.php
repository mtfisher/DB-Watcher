<?php
namespace MF\Dbwatcher\Rules;

class Factory implements FactoryInterface
{
    const DEFAULT_CLASS_PATH = '\\MF\\Dbwatcher\\Rules\\';

    /**
     * @var \DI\FactoryInterface
     */
    private $diContainer;

    /**
     * Factory constructor.
     * @param \DI\FactoryInterface $diContainer
     */
    public function __construct(\DI\FactoryInterface $diContainer)
    {
        $this->diContainer = $diContainer;
    }

    /**
     * @param $class
     * @param array $arguments
     * @return Rule
     * @throws Exceptions\FactoryClassNotFound
     * @throws Exceptions\FactoryClassNotValid
     */
    public function create($class, array $conditions)
    {
        if(substr($class, 0, 1) !== '\\') {
            $class = self::DEFAULT_CLASS_PATH . $class;
        }

        if(!class_exists($class, true)) {
            throw new Exceptions\FactoryClassNotFound("$class not found");
        }

        /**
         * @var Rule $obj
         */
        $obj = $this->diContainer->make($class);

        if($obj instanceof Rule) {
            return $obj->setConditions($conditions);
        }

        throw new Exceptions\FactoryClassNotValid("$class does not implement \\MF\\Dbwatcher\\Rules\\Rule");
    }
}