<?php


/**
 * Class GenerateEntity
 */
class GenerateUpdateRequest
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

        $this->make();
    }

    public function make()
    {

        $rules = null;
        $midMensagem = null;
        $class = strtolower($this->className);

        foreach ($this->fields as $key => $field) {
            $field = trim($field);

            if ($field != 'id' && $field != 'uuid') {
                $rules .= "\t\t\t'{$field}' => [\n\t\t\t\t'required',\n\t\t\t],\n";
                $midMensagem .= "\t\t\t'{$field}.required' => __('A {$field} is required.'),\n";
            }
        }

        $publicFunctionRules    = "\tpublic function rules()\n\t{\n\t\treturn [\n{$rules}\t\t];\n\t}\n\n";
        $publicFunctionMensagem = "\tpublic function messages()\n\t{\n\t\treturn [\n{$midMensagem}\t\t];\n\t}\n\n";

        $str = "<?php\n\n";
        $str .= "namespace App\Http\Requests\\$this->className;\n\n";
        $str .= "use Illuminate\Foundation\Http\FormRequest;\n";
        $str .= "use Illuminate\Validation\Rule;\n\n";

        $str .= "class Update{$this->className}Request extends FormRequest\n";
        $str .= "{\n";
        $str .= "\tpublic function authorize()\n";
        $str .= "\t{\n";
        $str .= "\t\treturn true;\n";
        $str .= "\t}\n\n";
        $str .= $publicFunctionRules;
        $str .= $publicFunctionMensagem;
        $str .= "}";

        $fileName = $this->path . '/' . 'Update'.$this->className . 'Request.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

        return true;

    }

}