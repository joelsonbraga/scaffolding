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
        $str.= "use App\Repositories\\$this->className\Exceptions\\{$this->className}FoundException;\n";
        $str.= "use App\Repositories\\$this->className\Exceptions\Create{$this->className}ErrorException;\n";
        $str.= "use App\Repositories\\$this->className\Exceptions\Delete{$this->className}ErrorException;\n";
        $str.= "use App\Repositories\\$this->className\Exceptions\Update{$this->className}ErrorException;\n";
        $str.= "use Illuminate\Database\Query\Builder;\n";
        $str.= "use Illuminate\Database\QueryException;\n";
        $str.= "use Illuminate\Pagination\LengthAwarePaginator;\n\n\n";

        $str.= "class {$this->className}Repository implements {$this->className}RepositoryInterface\n";
        $str.= "{\n\n";

        $str.= "private \${$modelParam};\n";
        $str.= "private \$perPage = 25;\n\n";

        /**
         * Construct
         */
        $str.= "public function __construct({$this->className} \${$modelParam})\n";
        $str.= "{\n";
        $str.= "    \$this->{$modelParam} = \${$modelParam};\n";
        $str.= "}\n\n";

        /**
         * Create method
         */
        $str.= "public function create({$entity} \${$entityParam}): {$this->className}\n";
        $str.= "{\n";
        $str.= "    try {\n\n";

        foreach ($this->fields as $key => $field) {
            $field = trim($field);
            if ($field != 'id') {
                $getName = str_replace('_', '', ucwords($field, '_'));

                $str .= "        \$this->{$modelParam}->{$field} = \${$entityParam}->get{$getName}();\n";
            }
        }
        $str.= "\n";
        $str.= "        \$this->{$modelParam}->save();\n";
        $str.= "    } catch (QueryException | \Throwable \$e) {\n";
        $str.= "        throw new Create{$this->className}ErrorException(\$e->getMessage(), 500);\n";
        $str.= "    }\n\n";
        $str.= "    return \$this->{$modelParam};\n";
        $str.= "}\n\n";

        /**
         * Update method
         */
        $str.= "public function update({$entity} \${$entityParam}): {$this->className}\n";
        $str.= "{\n";
        $str.= "    try {\n\n";
        $str.= "        \$$modelParam = \$this->findById(\${$entityParam}->getUuid());\n\n";

        foreach ($this->fields as $key => $field) {
            $field = trim($field);
            if ($field != 'id') {
                $getName = str_replace('_', '', ucwords($field, '_'));

                $str .= "        \${$modelParam}->{$field} = \${$entityParam}->get{$getName}();\n";
            }
        }
        $str.= "\n";
        $str.= "        \${$modelParam}->save();\n";
        $str.= "    } catch (QueryException | \Throwable \$e) {\n";
        $str.= "        throw new Update{$this->className}ErrorException(\$e->getMessage(), 500);\n";
        $str.= "    }\n\n";
        $str.= "    return \${$modelParam};\n";
        $str.= "}\n\n";

        /**
         * Delete method
         */
        $str.= "public function delete(string \$uuid): bool\n";
        $str.= "{\n";
        $str.= "    try {\n\n";
        $str.= "        return \$this->{$modelParam}->where('uuid', \$uuid)->first()->delete();\n";
        $str.= "    } catch (QueryException | \Throwable \$e) {\n";
        $str.= "        throw new Delete{$this->className}ErrorException(\$e->getMessage(), 500);\n";
        $str.= "    }\n\n";
        $str.= "}\n\n";

        /**
         * Find by id method
         */
        $str.= "public function findById(string \$uuid): {$this->className}\n";
        $str.= "{\n";
        $str.= "    try {\n\n";
        $str.= "        return \$this->{$modelParam}->where('uuid', \$uuid)->first();\n";
        $str.= "    } catch (QueryException | \Throwable \$e) {\n";
        $str.= "        throw new {$this->className}NotFoundException(\$e->getMessage());\n";
        $str.= "    }\n\n";
        $str.= "}\n\n";

        /**
         * Find all id method
         */
        $str.= " public function findAll(array \$filter = null, string \$sortBy = 'name', string \$orientation = 'asc'): LengthAwarePaginator\n";
        $str.= "{\n";
        $str.= "    try {\n\n";

        $str.= "        \${$modelParam} =  \$this->{$modelParam}\n";
        $str.= "        ->where(function(Builder \$query) use (\$filter) {\n";
        $str.= "            if (!empty(\$filter)) {\n";
                                foreach ($this->fields as $key => $field) {
                                    $field = trim($field);

                                    if ($field != 'id') {
                                        $str.= "                \$query->where('{$field}', \$filter['{$field}'] ?? null);\n";
                                    }
                                }
        $str.= "            }\n";
        $str.= "        })\n";
        $str.= "        ->orderBy(\$sortBy, \$orientation)\n";
        $str.= "        ->paginate(\$this->perPage);\n\n";

        $str.= "    } catch (QueryException | \Throwable \$e) {\n";
        $str.= "        throw new {$this->className}NotFoundException(\$e->getMessage());\n";
        $str.= "    }\n\n";
        $str.= "    return \${$modelParam};\n";
        $str.= "}\n\n";

        $str.= "}\n\n";
        $str.= "?>";


        $fileName = $this->path . '/' . $this->className . 'Repository.php';
        $fp = fopen($fileName, 'w');
        fwrite($fp, $str);
        fclose($fp);

    }
}