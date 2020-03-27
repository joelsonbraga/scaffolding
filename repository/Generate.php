<?php

require_once __DIR__. '/GenerateEntity.php';
require_once __DIR__. '/GenerateInterfaceRepository.php';
require_once __DIR__. '/GenerateException.php';
require_once __DIR__. '/GenerateRepository.php';

/**
 * Class Generate
 */
class Generate
{

    /**
     * @var
     */
    private $className;
    /**
     * @var
     */
    private $repositoryClasses;
    /**
     * @var
     */
    private $repositoryExceptions;
    /**
     * @var
     */
    private $fields;
    /**
     * @var string
     */
    private $path;

    /**
     * Generate constructor.
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->className            = ($request['className'] ?? null);
        $this->repositoryClasses    = ($request['repositoryClasses'] ?? null);
        $this->repositoryExceptions = ($request['repositoryExceptions'] ?? null);
        $this->fields               = ($request['fields'] ?? null);

        $this->path = '../generated/' . ucwords($this->className);
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    public function run()
    {
        if (!empty($this->repositoryClasses)) {
            if (isset($this->repositoryClasses['entity'])) {
                new GenerateEntity($this->className, $this->fields, $this->path);
            }
            if (isset($this->repositoryClasses['interface'])) {
                (new GenerateInterfaceRepository($this->className, $this->fields, $this->path));
            }
            if (isset($this->repositoryClasses['repository'])) {
                (new GenerateRepository($this->className, $this->fields, $this->path));
            }
        }

        if (!empty($this->repositoryExceptions)) {
            $this->path = '../generated/' . ucwords($this->className) . '/Exceptions';
            if (!is_dir($this->path)) {
                mkdir($this->path, 0777, true);
            }

            (new GenerateException($this->repositoryExceptions, $this->className, $this->path));
        }

    }

}