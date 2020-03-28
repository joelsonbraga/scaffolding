<?php


class GenerateResources
{
    /**
     * @var array
     */
    private $requests;
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $fields;


    /**
     * GenerateException constructor.
     * @param array $resquests
     * @param string $className
     * @param string $path
     */
    public function __construct(array $resquests, string $className, string $path, $fields)
    {

        $this->requests = $resquests;
        $this->className = ucwords($className);
        $this->path = $path;
        $this->fields = explode(',', $fields);

        $this->make();
    }

    public function make()
    {
        if (isset($this->requests['collection'])) {
            $this->index();
        }
        if (isset($this->requests['resource'])) {
            $this->store();
        }
        if (isset($this->requests['store'])) {
            $this->store();
        }
    }


    private function index(): bool
    {

        $midToArray = null;

        foreach ($this->fields as $key => $field) {
            $field = trim($field);


            $midToArray .= "'{$field}' => \$item->{$field},\n";
        }

        $toArray = "public function toArray(): array\n{\n 
                " . '$collection = $this->resource->toArray()' . ";
                " . '$collection[' . "'data'" . '] = $this->collection->map(function ($item, $key) {' . "  
                return [
                {$midToArray}
                        ];
        });
        return " . '$collection;' . "
        \n}\n\n" . "";

        $str = "<?php\n\n";
        $str .= "namespace App\Http\Resources\\$this->className;\n\n";
        $str .= "use Illuminate\Http\Resources\Json\ResourceCollection;\n\n";
        $str .= "class {$this->className}Collection  extends ResourceCollection\n";
        $str .= "{\n\n";
        $str .= $toArray;
        $str .= "}\n\n";
        $str .= "?>";

        $fileName = $this->path . '/' . $this->className . 'Collection.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

        return true;
    }

    private function store(): bool
    {

        $midToArray = null;

        foreach ($this->fields as $key => $field) {
            $field = trim($field);


            $midToArray .= "'{$field}' => \$this->{$field},\n";
        }

        $toArray = "public function toArray(): array\n{\n 
                return [
                "."'data'" ." => [
                            {$midToArray}
                             ]
                        ];
                return " . '$collection;' . "
        \n}\n\n" . "";

        $str = "<?php\n\n";
        $str .= "namespace App\Http\Resources\\$this->className;\n\n";
        $str .= "use Illuminate\Http\Resources\Json\ResourceCollection;\n\n";
        $str .= "class {$this->className}Collection  extends JsonResource\n";
        $str .= "{\n\n";
        $str .= $toArray;
        $str .= "}\n\n";
        $str .= "?>";

        $fileName = $this->path . '/' . $this->className . 'Resources.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

        return true;
    }

    private function update(): bool
    {
        $rules = null;
        $midMensagem = null;
        $class = strtolower($this->className);

        foreach ($this->fields as $key => $field) {
            $field = trim($field);

            $rules .= "'{$field}' => [ \n'required',\n Rule::unique('nameTable')->ignore(" . '$this->uuid' . ", 'uuid')],\n";
            $midMensagem .= "'{$field}.required' =>          __('A {$field} is required.'),\n";


        }

        $PublicFunctionRules = "public function rules()\n{\n return [\n{$rules}];\n}\n\n";
        $PublicFunctionMensagem = "public function messages()\n{\n return [\n{$midMensagem}];\n}\n\n";
        $str = "<?php\n\n";
        $str .= "namespace App\Http\Requests\\$this->className;\n\n";
        $str .= "use Illuminate\Foundation\Http\FormRequest;\n";
        $str .= "use Illuminate\Validation\Rule;\n";

        $str .= "class Update{$this->className}Request extends FormRequest\n";
        $str .= "{\n\n";
        $str .= "    public function authorize()\n";
        $str .= "    {\n";
        $str .= "     return true;";
        $str .= "\n\n}\n\n";
        $str .= $PublicFunctionRules;
        $str .= $PublicFunctionMensagem;
        $str .= "}\n\n";
        $str .= "?>";

        $fileName = $this->path . '/' . 'Update' . $this->className . 'Request.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

        return true;
    }


}