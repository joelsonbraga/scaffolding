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
    }

    public function run()
    {


        if (!empty($this->resources)) {
            $this->path = './generated/Resource/' . ucwords($this->className) ;
            if (!is_dir($this->path)) {

                mkdir($this->path, 0777, true);
            }

            (new GenerateResource( $this->className, $this->path, $this->fields));
            (new GenerateCollection( $this->className, $this->path, $this->fields));
        }

    }

}
?>