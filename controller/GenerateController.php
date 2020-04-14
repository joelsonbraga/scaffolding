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
    public function __construct(string $className, string $path, string $fields)
    {
        $this->className = ucwords($className);
        $this->fields = explode(',', $fields);
        $this->path = $path;

        $this->make();
    }

    public function pluralVariables()
    {
        $dictionary = [
            'ProductBrand' => 'productBrands',
            'Customer' => 'customers',
        ];

        $pluralObjectName = $dictionary[$this->className];

        return $pluralObjectName;
    }

    public function make()
    {

        $pluralModel = $this->pluralVariables();

        $setVaribleFormatedObject = strtolower(substr($this->className, 0, 1)) . substr($this->className, 1);
        $modelParam = strtolower(substr($this->className, 0, 1)) . substr($this->className, 1);


        /**
         * Create  public function __construct()
         */
        $construct = "public function __construct({$this->className}RepositoryInterface \${$setVaribleFormatedObject}Repository)\n
        {
            \$this->{$modelParam}Repository = \${$setVaribleFormatedObject}Repository;
                
        \n}
        \n\n";

        /**
         * Create  public function index()
         */
        $publicFunctionIndex = "public function index() \n{
         try {
            \$$pluralModel = \$this->{$setVaribleFormatedObject}Repository->findAll();

            return response()->json(new {$this->className}Collection(\$$pluralModel));
        } catch ({$this->className}NotFoundException \$e) {
            return response()->json(\$e->getResponse(), \$e->getCode());
        }                
        \n}\n\n";

        /**
         * Create  public function store()
         */
        $publicFunctionStore = "public function store(Store{$this->className}Request \$request) \n{
          try {
            \${$setVaribleFormatedObject} = new {$this->className}Entity(\$request->validated());
            \${$setVaribleFormatedObject} = \$this->{$setVaribleFormatedObject}Repository->create(\${$setVaribleFormatedObject});

            return response()->json(new {$this->className}Resource(\${$setVaribleFormatedObject}));
        } catch (Create{$this->className}ErrorException \$e) {
            return response()->json(\$e->getResponse(), \$e->getCode());
        }            
        \n}\n\n";

        /**
         * Create  public function show()
         */
        $publicFunctionShow = "public function show(string \$uuid) \n{
          try {
            \${$setVaribleFormatedObject} = \$this->{$setVaribleFormatedObject}Repository->findById(\$uuid);

            return response()->json(new {$this->className}Resource(\${$setVaribleFormatedObject}));
        } catch (Create{$this->className}ErrorException \$e) {
            return response()->json(\$e->getResponse(), \$e->getCode());
        }            
        \n}\n\n";



        /**
         * Create  public function Update()
         */
        $publicFunctionUpdate = "public function Update(Update{$this->className}Request \$request, string \$uuid) \n{
          try {
            \$validated = \$request->validated();
            \$validated['uuid'] = \$uuid;

            \${$setVaribleFormatedObject}Entity = new {$this->className}Entity(\$validated);
            \${$setVaribleFormatedObject} = \$this->{$setVaribleFormatedObject}Repository->update(\${$setVaribleFormatedObject}Entity);

            return response()->json(new {$this->className}Resource(\${$setVaribleFormatedObject}));
        } catch (Update{$this->className}ErrorException \$e) {
            return response()->json(\$e->getResponse(), \$e->getCode());
        }    
        \n}\n\n";

        /**
         * Create  public function destroy()
         */
        $publicFunctionDestroy = "public function destroy(string \$uuid) \n{
           try {
            \${$setVaribleFormatedObject} = \$this->{$setVaribleFormatedObject}Repository->delete(\$uuid);
            \$response = [
                'success' => [
                    'message' => __('{$setVaribleFormatedObject} was successfully excluded.'),
                ],
            ];
            return response()->json(\$response);
        } catch (Delete{$this->className}ErrorException \$e) {
            return response()->json(\$e->getResponse(), \$e->getCode());
        }
    }
        \n}\n\n";




        $str = "<?php\n\n";
        $str .= "namespace App\Http\Resources\\$this->className;\n\n";
        $str .= "use Illuminate\Http\Resources\Json\ResourceCollection;\n\n";

        $str .= " use App\Http\Requests\\$this->className\Store{$this->className}Request;\n";
        $str .= " use App\Http\Requests\\$this->className\Update{$this->className}Request;\n";
        $str .= " use App\Http\Resources\\$this->className\\{$this->className}Collection;\n";
        $str .= " use App\Http\Resources\\$this->className\\{$this->className}Resource;\n";
        $str .= " use App\Repositories\\$this->className\\{$this->className}Entity;\n";
        $str .= " use App\Repositories\\$this->className\\{$this->className}RepositoryInterface;\n";
        $str .= " use App\Repositories\\$this->className\Exceptions\\{$this->className}NotFoundException;\n";
        $str .= " use App\Repositories\\$this->className\Exceptions\Create{$this->className}ErrorException;\n";
        $str .= " use App\Repositories\\$this->className\Exceptions\Delete{$this->className}ErrorException;\n";
        $str .= " use App\Repositories\\$this->className\Exceptions\Update{$this->className}ErrorException;\n";

        $str .= "class {$this->className}Controller  extends Controller\n";
        $str .= "{\n\n";

        /**
         * Private atribute
         */
        $str .= "private \${$modelParam}Repository;\n\n";
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

        $str .= "?>";

        $fileName = $this->path . '/' . $this->className . 'Controller.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

        return true;

    }

}