<?php

namespace MF\Dbwatcher\Tasks;

class Parser implements ParserInterface
{
    /**
     * The file path to parse
     * @var string
     */
    private $filePath;

    /**
     * The task factory
     * @var FactoryInterface
     */
    private $taskFactory;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    private $yamlParser;

    /**
     * Parser constructor.
     * @param $fileName
     * @param FactoryInterface $taskFactory
     */
    public function __construct($filePath, FactoryInterface $taskFactory)
    {
        $this->taskFactory = $taskFactory;
        $this->filePath = $filePath;
        $this->yamlParser = new \Symfony\Component\Yaml\Yaml();

        if(!file_exists($filePath)) {
            throw new Exceptions\ParserFileCouldNotBeFound("{$filePath} could not be found");
        }
    }

    public function parse()
    {
        $tasks = [];

        $config = $this->yamlParser->parse(file_get_contents($this->filePath));

        foreach ($config as $key=>$value) {
            $tasks[] = $this->taskFactory->create($value);
        }

        return $tasks;
    }
}