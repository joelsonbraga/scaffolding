<?php


/**
 * Class GenerateEntity
 */
class GenerateEntity
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
        $attributes    = null;
        $getSet        = null;
        $midConstructor = null;
        $midToArray    = null;

        foreach ($this->fields as $key => $field) {
            $field = trim($field);
            $attributes .= "private \${$field};\n";

            $name = str_replace('_', '', ucwords($field, '_'));

            $getSet .= "public function get{$name}()\n{\nreturn \$this->{$field};\n}\n";
            if ($field === 'id') {
                $getSet .= "public function set{$name}(?int \${$field}): void\n{\n\$this->{$field} = \${$field};\n}\n\n";
            } else {
                $getSet .= "public function set{$name}(?string \${$field}): void\n{\n\$this->{$field} = \${$field};\n}\n\n";
            }

            $midConstructor .= "\$this->set{$name}(\$mixedData['{$field}'] ?? null);\n";
            $midToArray .= "'{$field}' => \$this->get{$name}(),\n";
        }

        $construct ="\npublic function __construct(array \$mixedData)\n{\n{$midConstructor}\n}\n\n";
        $toArray = "public function toArray(): array\n{\n return [\n{$midToArray}];\n}\n\n";

        $str = "<?php\n\n";
        $str.= "namespace App\\Repositories\\$this->className;\n\n";
        $str.= "class {$this->className}Entity\n";
        $str.= "{\n\n";
        $str.= $attributes;
        $str.= $construct;
        $str.= $toArray;
        $str.= $getSet;
        $str.= "}\n\n";
        $str.= "?>";

        $fileName = $this->path . '/' . $this->className . 'Entity.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

    }

}