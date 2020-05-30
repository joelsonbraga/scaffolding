<?php

require_once __DIR__ . '/GenerateCollection.php';
require_once __DIR__ . '/GenerateResource.php';


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
    private $resources;

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
        $this->className = ($request['className'] ?? null);
        $this->fields = ($request['fields'] ?? null);
        $this->resources = ($request['resources'] ?? null);

        $this->path = '../generated/Resource/' . ucwords($this->className);
    }

    public function run()
    {
        if (!empty($this->resources)) {
            if (!is_dir($this->path)) {
                mkdir($this->path, 0777, true);
            }

            if (isset($this->resources['resource'])) {
                (new GenerateResource($this->className, $this->fields, $this->path));
            }
            if (isset($this->resources['collection'])) {
                (new GenerateCollection($this->className, $this->fields, $this->path));
            }
        }

    }

}

?>