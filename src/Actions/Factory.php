<?php
namespace MF\Dbwatcher\Actions;

class Factory implements FactoryInterface
{
    const DEFAULT_CLASS_PATH = '\\MF\\Dbwatcher\\Actions\\';

    /**
     * @var \DI\FactoryInterface
     */
    private $diContainer;

    public function __construct(\DI\FactoryInterface $diContainer)
    {
        $this->diContainer = $diContainer;
    }

    /**
     * Gets an action parameter
     * @param $class
     * @param array $arguments
     * @return Action
     * @throws Exceptions\FactoryClassNotFound
     * @throws Exceptions\FactoryClassNotValid
     */
    public function create($class, array $arguments = [])
    {
        if(substr($class, 0, 1) !== '\\') {
            $class = self::DEFAULT_CLASS_PATH . $class;
        }

        if(!class_exists($class, true)) {
            throw new Exceptions\FactoryClassNotFound("$class not found");
        }

        /**
         * @var Action $obj
         */
        $obj = $this->diContainer->make($class);

        if($obj instanceof Action) {
            return $obj->setActionArgs($arguments);
        }

        throw new Exceptions\FactoryClassNotValid("$class does not implement \\MF\\Dbwatcher\\Actions\\Action");
    }
}