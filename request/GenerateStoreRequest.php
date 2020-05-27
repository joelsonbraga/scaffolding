<?php


/**
 * Class GenerateEntity
 */
class GenerateStoreRequest
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

        foreach ($this->fields as $key => $field) {
            $field = trim($field);

            $rules .= "'{$field}' => [ \n'required',\n],\n";
            $midMensagem .= "'{$field}.required' =>          __('A {$field} is required.'),\n";


        }

        $PublicFunctionRules = "public function rules()\n{\n return [\n{$rules}];\n}\n\n";
        $PublicFunctionMensagem = "public function messages()\n{\n return [\n{$midMensagem}];\n}\n\n";
        $str = "<?php\n\n";
        $str .= "namespace App\Http\Requests\\$this->className;\n\n";
        $str .= "use Illuminate\Foundation\Http\FormRequest;\n";
        $str .= "class Store{$this->className}Request extends FormRequest\n";
        $str .= "{\n\n";
        $str .= "    public function authorize()\n";
        $str .= "    {\n";
        $str .= "     return true;";
        $str .= "\n\n}\n\n";
        $str .= $PublicFunctionRules;
        $str .= $PublicFunctionMensagem;
        $str .= "}\n\n";
        $str .= "?>";

        $fileName = $this->path . '/' . 'Store'.$this->className . 'Request.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);
        return true;


    }

}