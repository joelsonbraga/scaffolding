<?php


class GenerateRepository
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

        $modelParam  = strtolower(substr($this->className, 0, 1)) . substr($this->className, 1);
        $entity      = $this->className . 'Entity';
        $entityParam = strtolower(substr($entity, 0, 1)) . substr($entity, 1);

        $str = "<?php\n";
        $str.= "declare(strict_types=1);\n\n";
        $str.= "namespace App\\Repositories\\$this->className;\n\n";

        $str.= "use App\Models\\$this->className;\n";
        $str.= "use App\Repositories\\$this->className\Exceptions\\{$this->className}NotFoundException;\n";
        $str.= "use App\Repositories\\$this->className\Exceptions\Create{$this->className}ErrorException;\n";
        $str.= "use App\Repositories\\$this->className\Exceptions\Delete{$this->className}ErrorException;\n";
        $str.= "use App\Repositories\\$this->className\Exceptions\Update{$this->className}ErrorException;\n";
        $str.= "use Illuminate\Database\QueryException;\n";
        $str.= "use Illuminate\Pagination\LengthAwarePaginator;\n\n\n";

        $str.= "class {$this->className}Repository implements {$this->className}RepositoryInterface\n";
        $str.= "{\n\n";

        $str.= "\tprivate \${$modelParam};\n";
        $str.= "\tprivate \$perPage = 25;\n\n";

        /**
         * Construct
         */
        $str.= "\tpublic function __construct({$this->className} \${$modelParam})\n";
        $str.= "\t{\n";
        $str.= "    \t\$this->{$modelParam} = \${$modelParam};\n";
        $str.= "\t}\n\n";

        /**
         * Create method
         */
        $str.= "\tpublic function create({$entity} \${$entityParam}): {$this->className}\n";
        $str.= "\t{\n";
        $str.= "    \ttry {\n";
        foreach ($this->fields as $key => $field) {
            $field = trim($field);
            if ($field != 'id' and $field != 'uuid') {
                $getName = str_replace('_', '', ucwords($field, '_'));

                $str .= "        \t\$this->{$modelParam}->{$field} = \${$entityParam}->get{$getName}();\n";
            }
        }
        $str.= "        \t\$this->{$modelParam}->save();\n";
        $str.= "    \t} catch (QueryException | \Throwable \$e) {\n";
        $str.= "        \tthrow new Create{$this->className}ErrorException(\$e->getMessage(), 500);\n";
        $str.= "    \t}\n\n";
        $str.= "    \treturn \$this->{$modelParam};\n";
        $str.= "\t}\n\n";

        /**
         * Update method
         */
        $str.= "\tpublic function update({$entity} \${$entityParam}): {$this->className}\n";
        $str.= "\t{\n";
        $str.= "    \ttry {\n";
        $str.= "        \t\$$modelParam = \$this->findById(\${$entityParam}->getUuid());\n\n";
        foreach ($this->fields as $key => $field) {
            $field = trim($field);
            if ($field != 'id' and $field != 'uuid') {
                $getName = str_replace('_', '', ucwords($field, '_'));

                $str .= "        \t\${$modelParam}->{$field} = \${$entityParam}->get{$getName}();\n";
            }
        }
        $str.= "        \t\${$modelParam}->save();\n";
        $str.= "    \t} catch (QueryException | \Throwable \$e) {\n";
        $str.= "        \tthrow new Update{$this->className}ErrorException(\$e->getMessage(), 500);\n";
        $str.= "    \t}\n\n";
        $str.= "    \treturn \${$modelParam};\n";
        $str.= "\t}\n\n";

        /**
         * Delete method
         */
        $str.= "\tpublic function delete(string \$uuid): bool\n";
        $str.= "\t{\n";
        $str.= "    \ttry {\n";
        $str.= "        \treturn \$this->{$modelParam}->where('uuid', \$uuid)->first()->delete();\n";
        $str.= "    \t} catch (QueryException | \Throwable \$e) {\n";
        $str.= "        \tthrow new Delete{$this->className}ErrorException(\$e->getMessage(), 500);\n";
        $str.= "    \t}\n";
        $str.= "\t}\n\n";

        /**
         * Find by id method
         */
        $str.= "\tpublic function findById(string \$uuid): {$this->className}\n";
        $str.= "\t{\n";
        $str.= "    \ttry {\n";
        $str.= "        \treturn \$this->{$modelParam}->where('uuid', \$uuid)->first();\n";
        $str.= "    \t} catch (QueryException | \Throwable \$e) {\n";
        $str.= "        \tthrow new {$this->className}NotFoundException(\$e->getMessage());\n";
        $str.= "    \t}\n";
        $str.= "\t}\n\n";

        /**
         * Find all id method
         */
        $str.= "\tpublic function findAll({$entity} \${$entityParam} = null, string \$sortBy = 'id', string \$orientation = 'asc'): LengthAwarePaginator\n";
        $str.= "\t{\n";
        $str.= "    \ttry {\n";

        $str.= "        \t\${$modelParam} =  \$this->{$modelParam}\n";
        $str.= "        \t->where(function(\$query) use (\${$entityParam}) {\n";
                            foreach ($this->fields as $key => $field) {
                                    $field = trim($field);

                                    if ($field != 'id') {
                                        $getName = str_replace('_', '', ucwords($field, '_'));

                                        $str.= "            \tif (!is_null(\${$entityParam}->get{$getName}())) {\n";
                                        $str.= "                \t\$query->where('{$field}', \${$entityParam}->get{$getName}());\n";
                                        $str.= "            \t}\n";
                                    }
                                }
        $str.= "        \t})\n";
        $str.= "        \t->orderBy(\$sortBy, \$orientation)\n";
        $str.= "        \t->paginate(\$this->perPage);\n\n";

        $str.= "    \t} catch (QueryException | \Throwable \$e) {\n";
        $str.= "        \tthrow new {$this->className}NotFoundException(\$e->getMessage());\n";
        $str.= "    \t}\n\n";
        $str.= "    \treturn \${$modelParam};\n";
        $str.= "\t}\n\n";

        $str.= "}";


        $fileName = $this->path . '/' . $this->className . 'Repository.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

        die('here');
    }
}