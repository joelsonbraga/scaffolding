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
            $attributes .= "\tprivate \${$field};\n";

            $name = str_replace('_', '', ucwords($field, '_'));

            $getSet .= "\tpublic function get{$name}(): ?string\n\t{\n\t\treturn \$this->{$field};\n\t}\n";
            if ($field === 'id') {
                $getSet .= "\tpublic function set{$name}(?int \${$field}): void\n\t{\n\t\t\t\$this->{$field} = \${$field};\n\t}\n\n";
            } else {
                $getSet .= "\tpublic function set{$name}(?string \${$field}): void\n\t{\n\t\t\$this->{$field} = \${$field};\n\t}\n\n";
            }

            $midConstructor .= "\t\t\$this->set{$name}(\$mixedData['{$field}'] ?? null);\n";
            $midToArray .= "\t\t\t'{$field}' => \$this->get{$name}(),\n";
        }

        $construct ="\n\tpublic function __construct(array \$mixedData)\n\t{\n{$midConstructor}\t}\n\n";
        $toArray = "\tpublic function toArray(): array\n\t{\n\t\treturn [\n{$midToArray}\t\t];\n\t}\n\n";

        $str = "<?php\n\n";
        $str.= "namespace App\\Repositories\\$this->className;\n\n";
        $str.= "class {$this->className}Entity\n";
        $str.= "{\n\n";
        $str.=      $attributes;
        $str.=      $construct;
        $str.=      $toArray;
        $str.=      $getSet;
        $str.= "}\n\n";

        $fileName = $this->path . '/' . $this->className . 'Entity.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }
}