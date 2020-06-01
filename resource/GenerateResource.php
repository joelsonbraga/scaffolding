<?php


/**
 * Class GenerateResource
 */
class GenerateResource
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
        $this->fields = explode(',', $fields);
        $this->path = $path;

        $this->make();
    }

    public function make()
    {
        $midToArray = null;

        foreach ($this->fields as $key => $field) {

            $field = trim($field);
            $midToArray .= "\t\t\t\t'{$field}' => \$this->{$field},\n";
        }

        $toArray = "\tpublic function toArray(\$request): array\n\t{\n";
        $toArray .= "\t\treturn [\n\t\t\t'data' => [\n{$midToArray}\t\t\t],\n\t\t];\n\t}\n";

        $str = "<?php\n\n";

        $str .= "namespace App\Http\Resources\\$this->className;\n\n";
        $str .= "use Illuminate\Http\Resources\Json\JsonResource;;\n\n";

        $str .= "class {$this->className}Resource extends JsonResource\n";
        $str .= "{\n";
        $str .= $toArray;
        $str .= "}";

        $fileName = $this->path . '/' . $this->className .'Resource.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }

}