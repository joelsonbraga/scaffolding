<?php

require_once __DIR__ . '/GenerateController.php';


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
    private $controller;

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
        $this->controller = ($request['controller'] ?? null);
    }

    public function run()
    {


        if (!empty($this->controller)) {
            $this->path = './generated/Controller/' . ucwords($this->className) ;
            if (!is_dir($this->path)) {

                mkdir($this->path, 0777, true);
            }

            (new GenerateController( $this->className, $this->path, $this->fields));
        }

    }

}
?>