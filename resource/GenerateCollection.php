<?php


/**
 * Class GenerateResource
 */
class GenerateCollection
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
     * GenerateResource constructor.
     * @param string $className
     * @param string $fields
     * @param string $path
     */
    public function __construct(string $className, string $fields, string $path)
    {
        $this->className = ucwords($className);
        $this->fields = explode(',', $fields);
        $this->path = $path;

        $this->make();
    }

    public function make()
    {
        $midToArray = null;

        foreach ($this->fields as $key => $field) {

            $field = trim($field);
            $midToArray .= "\t\t\t\t'{$field}' => \$item->{$field},\n";
        }

        $toArray = "\tpublic function toArray(): array\n";
        $toArray.= "\t{\n\t\t\$collection = \$this->resource->toArray();\n";
        $toArray.= "\t\t\$collection['data'] = \$this->collection->map(function (\$item, \$key) {\n";
        $toArray.= "\t\t\treturn [\n";
        $toArray.= $midToArray;
        $toArray.="\t\t\t];\n";
        $toArray.="\t\t});\n\n";
        $toArray.="\t\treturn \$collection;\n";
        $toArray.="\t}\n";


        $str = "<?php\n\n";
        $str .= "namespace App\Http\Resources\\$this->className;\n\n";
        $str .= "use Illuminate\Http\Resources\Json\ResourceCollection;\n\n";
        $str .= "class {$this->className}Collection  extends ResourceCollection\n";
        $str .= "{\n";
        $str .= $toArray;
        $str .= "}";

        $fileName = $this->path . '/' . $this->className . 'Collection.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }
}