<?php


/**
 * Class GenerateEntity
 */
class GenerateController
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
    public function __construct(string $className, string $path, ?string $fields = null)
    {
        $this->className = ucwords($className);
        $this->fields    = explode(',', $fields);
        $this->path      = $path;

        $this->make();
    }

    public function make()
    {

        $pluralModel = $this->className;

        $setVaribleFormatedObject = strtolower(substr($this->className, 0, 1)) . substr($this->className, 1);
        $modelParam = strtolower(substr($this->className, 0, 1)) . substr($this->className, 1);
        $entity      = $this->className . 'Entity';
        $entityParam = strtolower(substr($entity, 0, 1)) . substr($entity, 1);

        /**
         * Create  public function __construct()
         */
        $construct  = "\tpublic function __construct({$this->className}RepositoryInterface \${$modelParam}Repository)\n";
        $construct .= "\t{\n";
        $construct .= "\t\t\$this->{$modelParam}Repository = \${$modelParam}Repository;\n";
        $construct .= "\t}\n\n";

        /**
         * Create  public function index()
         */
        $publicFunctionIndex  = "\tpublic function index(Index{$this->className}Request \$request)\n";
        $publicFunctionIndex .= "\t{\n";
        $publicFunctionIndex .= "\t\ttry {\n";
        $publicFunctionIndex .= "\t\t\t\${$entityParam} = new {$entity}(\$request->validated());\n";
        $publicFunctionIndex .= "\t\t\t\$$modelParam = \$this->{$modelParam}Repository->findAll({$entityParam});\n";
        $publicFunctionIndex .= "\t\t\treturn response()->json(new {$this->className}Collection(\$$modelParam));\n";
        $publicFunctionIndex .= "\t\t} catch ({$this->className}NotFoundException \$e) {\n";
        $publicFunctionIndex .= "\t\t\treturn response()->json(\$e->getResponse(), \$e->getCode());\n";
        $publicFunctionIndex .= "\t\t}\n";
        $publicFunctionIndex .= "\t}\n\n";

        /**
         * Create  public function store()
         */
        $publicFunctionStore = "\tpublic function store(Store{$this->className}Request \$request)\n";
        $publicFunctionStore .= "\t{\n";
        $publicFunctionStore .= "\t\ttry {\n";
        $publicFunctionStore .= "\t\t\t\${$entityParam} = new {$entity}(\$request->validated());\n";
        $publicFunctionStore .= "\t\t\t\${$modelParam} = \$this->{$this->className}Repository->create(\${$entityParam});\n\n";
        $publicFunctionStore .= "\t\t\treturn response()->json(new {$this->className}Resource(\${$modelParam}));\n";
        $publicFunctionStore .= "\t\t} catch (Create{$this->className}ErrorException \$e) {\n";
        $publicFunctionStore .= "\t\t\treturn response()->json(\$e->getResponse(), \$e->getCode());\n";
        $publicFunctionStore .= "\t\t}\n";
        $publicFunctionStore .= "\t}\n\n";

        /**
         * Create  public function show()
         */
        $publicFunctionShow  = "\tpublic function show(string \$uuid)\n";
        $publicFunctionShow .= "\t{\n";
        $publicFunctionShow .= "\t\ttry {\n";
        $publicFunctionShow .= "\t\t\t\${$modelParam} = \$this->{$modelParam}Repository->findById(\$uuid);\n";
        $publicFunctionShow .= "\t\t\treturn response()->json(new {$this->className}Resource(\${$modelParam}));\n";
        $publicFunctionShow .= "\t\t} catch (Create{$this->className}ErrorException \$e) {\n";
        $publicFunctionShow .= "\t\t\treturn response()->json(\$e->getResponse(), \$e->getCode());\n";
        $publicFunctionShow .= "\t\t}\n";
        $publicFunctionShow .= "\t}\n\n";

        /**
         * Create  public function Update()
         */
        $publicFunctionUpdate = "\tpublic function update(Update{$this->className}Request \$request, string \$uuid)\n";
        $publicFunctionUpdate .= "\t{\n";
        $publicFunctionUpdate .= "\t\ttry {\n";
        $publicFunctionUpdate .= "\t\t\t\$validated = \$request->validated();\n";
        $publicFunctionUpdate .= "\t\t\t\$validated['uuid'] = \$uuid;\n\n";
        $publicFunctionUpdate .= "\t\t\t\${$modelParam}Entity = new {$this->className}Entity(\$validated);\n";
        $publicFunctionUpdate .= "\t\t\t\${$modelParam} = \$this->{$modelParam}Repository->update(\${$modelParam}Entity);\n\n";
        $publicFunctionUpdate .= "\t\t\treturn response()->json(new {$this->className}Resource(\${$modelParam}));\n";
        $publicFunctionUpdate .= "\t\t} catch (Update{$this->className}ErrorException \$e) {\n";
        $publicFunctionUpdate .= "\t\t\treturn response()->json(\$e->getResponse(), \$e->getCode());\n";
        $publicFunctionUpdate .= "\t\t}\n";
        $publicFunctionUpdate .= "\t}\n\n";

        /**
         * Create  public function destroy()
         */
        $publicFunctionDestroy  = "\tpublic function destroy(string \$uuid)\n";
        $publicFunctionDestroy .= "\t{\n";
        $publicFunctionDestroy .= "\t\ttry {\n";
        $publicFunctionDestroy .= "\t\t\t\${$modelParam} = \$this->{$modelParam}Repository->delete(\$uuid);\n";
        $publicFunctionDestroy .= "\t\t\t\$response = [\n";
        $publicFunctionDestroy .= "\t\t\t\t'success' => [\n";
        $publicFunctionDestroy .= "\t\t\t\t'message' => __('{$modelParam} was successfully excluded.'),\n";
        $publicFunctionDestroy .= "\t\t\t\t],\n";
        $publicFunctionDestroy .= "\t\t\t];\n";
        $publicFunctionDestroy .= "\t\t\treturn response()->json(\$response);\n";
        $publicFunctionDestroy .= "\t\t} catch (Delete{$this->className}ErrorException \$e) {\n";
        $publicFunctionDestroy .= "\t\t\treturn response()->json(\$e->getResponse(), \$e->getCode());\n";
        $publicFunctionDestroy .= "\t\t}\n";
        $publicFunctionDestroy .= "\t}\n\n";


        $str = "<?php\n\n";
        $str .= "namespace App\Http\Controllers\v1;\n\n";

        $str .= "use App\Http\Requests\\$this->className\Index{$this->className}Request;\n";
        $str .= "use App\Http\Requests\\$this->className\Store{$this->className}Request;\n";
        $str .= "use App\Http\Requests\\$this->className\Update{$this->className}Request;\n";
        $str .= "use App\Http\Resources\\$this->className\\{$this->className}Collection;\n";
        $str .= "use App\Http\Resources\\$this->className\\{$this->className}Resource;\n";
        $str .= "use App\Repositories\\$this->className\\{$this->className}Entity;\n";
        $str .= "use App\Repositories\\$this->className\\{$this->className}RepositoryInterface;\n";
        $str .= "use App\Repositories\\$this->className\Exceptions\\{$this->className}NotFoundException;\n";
        $str .= "use App\Repositories\\$this->className\Exceptions\Create{$this->className}ErrorException;\n";
        $str .= "use App\Repositories\\$this->className\Exceptions\Delete{$this->className}ErrorException;\n";
        $str .= "use App\Repositories\\$this->className\Exceptions\Update{$this->className}ErrorException;\n\n";

        $str .= "class {$this->className}Controller  extends Controller\n";
        $str .= "{\n\n";

        /**
         * Private atribute
         */
        $str .= "\tprivate \${$modelParam}Repository;\n\n";
        /**
         * Construct
         */
        $str .= $construct;
        /**
         * PublicFunctionIndex
         */
        $str .= $publicFunctionIndex;
        /**
         * PublicFunctionStore
         */
        $str .= $publicFunctionStore;
        /**
         * PublicFunctionShow
         */
        $str .= $publicFunctionShow;
        /**
         * PublicFunctionShow
         */
        $str .= $publicFunctionUpdate;
        /**
         * PublicFunctionShow
         */
        $str .= $publicFunctionDestroy;
        $str .= "}\n\n";

        $fileName = $this->path . '/' . $this->className . 'Controller.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }
}