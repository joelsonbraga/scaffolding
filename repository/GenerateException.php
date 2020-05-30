<?php


class GenerateException
{
    /**
     * @var array
     */
    private $repositoryExceptions;
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $path;

    /**
     * GenerateException constructor.
     * @param array $repositoryExceptions
     * @param string $className
     * @param string $path
     */
    public function __construct(array $repositoryExceptions, string $className, string $path)
    {
        $this->repositoryExceptions = $repositoryExceptions;
        $this->className = ucwords($className);
        $this->path      = $path;

        $this->make();
    }

    public function make()
    {
        if (isset($this->repositoryExceptions['create'])) {
            $this->create();
        }
        if (isset($this->repositoryExceptions['update'])) {
            $this->update();
        }
        if (isset($this->repositoryExceptions['delete'])) {
            $this->delete();
        }
        if (isset($this->repositoryExceptions['not_foud'])) {
            $this->notFound();
        }
    }

    private function create(): bool
    {
        $str = "<?php\n\n";

        $str .= "namespace App\Repositories\\$this->className\Exceptions;\n\n";

        $str .= "use App\Repositories\BaseException;\n";
        $str .= "use Throwable;\n\n";

        $str .= "class Create{$this->className}ErrorException extends BaseException\n";
        $str .= "{\n";
        $str .= "    public function __construct(string \$message, int \$code = 500, Throwable \$previous = null)\n";
        $str .= "    {\n";
        $str .= "        parent::__construct(__('Unable to create {$this->className}.'  . \$message), \$code, \$previous);\n";
        $str .= "    }\n";
        $str .= "}";

        $fileName = $this->path . '/Create' . $this->className . 'ErrorException.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

        return true;
    }

    private function update(): bool
    {
        $str = "<?php\n\n";

        $str .= "namespace App\Repositories\\$this->className\Exceptions;\n\n";

        $str .= "use App\Repositories\BaseException;\n";
        $str .= "use Throwable;\n\n";

        $str .= "class Update{$this->className}ErrorException extends BaseException\n";
        $str .= "{\n";
        $str .= "    public function __construct(string \$message = '', \$code = 500, Throwable \$previous = null)\n";
        $str .= "    {\n";
        $str .= "        parent::__construct(__('Unable to update {$this->className}.'  . \$message), \$code, \$previous);\n";
        $str .= "    }\n";
        $str .= "}";

        $fileName = $this->path . '/Update' . $this->className . 'ErrorException.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

        return true;
    }

    private function delete(): bool
    {
        $str = "<?php\n\n";

        $str .= "namespace App\Repositories\\$this->className\Exceptions;\n\n";

        $str .= "use App\Repositories\BaseException;\n";
        $str .= "use Throwable;\n\n";

        $str .= "class Delete{$this->className}ErrorException extends BaseException\n";
        $str .= "{\n";
        $str .= "    public function __construct(string \$message, \$code = 500, Throwable \$previous = null)\n";
        $str .= "    {\n";
        $str .= "        parent::__construct(__('Unable to delete {$this->className}.'  . \$message), \$code, \$previous);\n";
        $str .= "    }\n";
        $str .= "}";

        $fileName = $this->path . '/Delete' . $this->className . 'ErrorException.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

        return true;
    }

    private function notFound(): bool
    {
        $str = "<?php\n\n";

        $str .= "namespace App\Repositories\\$this->className\Exceptions;\n\n";

        $str .= "use App\Repositories\BaseException;\n";
        $str .= "use Throwable;\n\n";

        $str .= "class {$this->className}NotFoundException extends BaseException\n";
        $str .= "{\n";
        $str .= "    public function __construct(string \$message, \$code = 404, Throwable \$previous = null)\n";
        $str .= "    {\n";
        $str .= "        parent::__construct(__('{$this->className} not found.'  . \$message), \$code, \$previous);\n";
        $str .= "    }\n";
        $str .= "}";

        $fileName = $this->path . '/' . $this->className . 'NotFoundException.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

        return true;
    }
}