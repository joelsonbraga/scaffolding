<?php


class GenerateIndexRequest
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
    public function __construct(string $className, string $path, string $fields)
    {
        $this->className = ucwords($className);
        $this->fields    = explode(',', $fields);
        $this->path      = $path;

        print_r($this);

        $this->make();
    }


    public function make(): void
    {
        $rules       = null;
        $midMensagem = null;

        foreach ($this->fields as $key => $field) {
            $field = trim($field);
            if ($field != 'id' && $field != 'uuid') {
                $rules .= "\t\t\t'{$field}' => [\n\t\t\t\t'required',\n\t\t\t],\n";
                $midMensagem .= "\t\t\t'{$field}.required' => __('A {$field} is required.'),\n";
            }
        }

        $publicFunctionRules   = "\tpublic function rules()\n\t{\n\t\treturn [\n{$rules}\t\t];\n\t}\n\n";
        $publicFunctionMensage = "\tpublic function messages()\n\t{\n\t\treturn [\n{$midMensagem}\t\t];\n\t}\n\n";

        $str = "<?php\n\n";
        $str .= "namespace App\Http\Requests\\$this->className;\n\n";
        $str .= "use Illuminate\Foundation\Http\FormRequest;\n\n";
        $str .= "class Index{$this->className}Request extends FormRequest\n";
        $str .= "{\n\n";
        $str .= "\tpublic function authorize()\n";
        $str .= "\t{\n";
        $str .= "\t\treturn true;\n";
        $str .= "\t}\n\n";
        $str .= $publicFunctionRules;
        $str .= $publicFunctionMensage;
        $str .= "}\n\n";


        $fileName = $this->path . '/' . 'Index' . $this->className . 'Request.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }

}