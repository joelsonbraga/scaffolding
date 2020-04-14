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
            $midToArray .= "'{$field}' => \$this->{$field},\n";
        }

        $toArray = "public function toArray(): array\n{
                return [
                'data' => [
                            {$midToArray}
                          ],
                       ];
        \n}\n";

        $str = "<?php\n\n";

        $str .= "namespace App\Http\Resources\\$this->className;\n\n";
        $str .= "use Illuminate\Http\Resources\Json\ResourceCollection;\n\n";

        $str .= "class {$this->className}Collection extends JsonResource\n";
        $str .= "{\n";
        $str .= $toArray;
        $str .= "}\n\n";
        $str .= "?>";

        $fileName = $this->path . '/' . $this->className .'Resources.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

        return true;

    }

}