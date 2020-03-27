<?php


class GenerateInterfaceRepository
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $fields;
    /**
     * @var string
     */
    private $path;

    /**
     * GenerateEntity constructor.
     * @param string $className
     * @param string $fields
     * @param string $path
     */
    public function __construct(string $className, string $fields, string $path)
    {
        $this->className = ucwords($className);
        $this->fields    = explode(',', $fields);
        $this->path      = $path;

        $this->make();
    }

    public function make()
    {
        $entity      = $this->className . 'Entity';
        $entityParam = strtolower(substr($entity, 0, 1)) . substr($entity, 1);

        $str = "<?php\n\n";
        $str.= "namespace App\Repositories\\$this->className;\n\n";

        $str.= "use App\Models\\$this->className;\n";
        $str.= "use Illuminate\Pagination\LengthAwarePaginator;\n\n";

        $str.= "interface {$this->className}RepositoryInterface\n";
        $str.= "{\n\n";
        $str.= "public function create({$entity} \$$entityParam): {$this->className};\n";
        $str.= "public function update({$entity} \$$entityParam): {$this->className};\n";
        $str.= "public function delete(string \$uuid): bool;\n";
        $str.= "public function findById(string \$uuid): {$this->className};\n";
        $str.= "public function findAll(array \$filter = null, string \$sortBy = 'name', string \$orientation = 'asc'): LengthAwarePaginator;\n";
        $str.= "}\n\n";
        $str.= "?>";

        $fileName = $this->path . '/' . $this->className . 'RepositoryInterface.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }

}