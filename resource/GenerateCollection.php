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
            $midToArray .= "'{$field}' => \$item->{$field},\n";
        }

        $toArray = "public function toArray(): array
        { \n \$collection = \$this->resource->toArray();
             \$collection['data'] = \$this->collection->map(function (\$item, \$key){
              return [
                         {$midToArray}
                     ];       
              });
        return \$collection;
       }
       ";

        $str = "<?php\n\n";
        $str .= "namespace App\Http\Resources\\$this->className;\n\n";
        $str .= "use Illuminate\Http\Resources\Json\ResourceCollection;\n\n";
        $str .= "class {$this->className}Collection  extends ResourceCollection\n";
        $str .= "{\n";
        $str .= $toArray;
        $str .= "}\n\n";
        $str .= "?>";

        $fileName = $this->path . '/' . $this->className . 'Collection.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

        return true;


    }

}