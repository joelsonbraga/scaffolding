<?php

require_once __DIR__ . '/GenerateStoreRequest.php';
require_once __DIR__ . '/GenerateUpdateRequest.php';


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
    private $resquests;

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
        $this->resquests = ($request['requests'] ?? null);
    }

    public function run()
    {


        if (!empty($this->resquests)) {
            $this->path = './generated/Request/' . ucwords($this->className) ;
            if (!is_dir($this->path)) {
                
                mkdir($this->path, 0777, true);
            }

            (new GenerateStoreRequest( $this->className, $this->path, $this->fields));
            (new GenerateUpdateRequest( $this->className, $this->path, $this->fields));
        }

    }

}