<?php

namespace Paw\App\Repositories;

use Paw\Core\Database\QueryBuilder;
use Paw\App\Models\Components\Component;
use Paw\App\Models\Components\Motherboard;

class ComponentRepository extends Repository
{
    protected static $instance = null;

    //protected static $model = User::class;
    public function model() {
        return Component::class; 
    }

    public function getByIdAndType($id, string $type = null)
    {
        $filter = "id = :id";
        $component = self::$queryBuilder->table($this->table())->select($filter, [':id' => $id]);
        $type = ($component[0])["type"];
        
        $type = "Paw\\App\\Models\\Components\\" . ucfirst($type);
        $filter = "component_id = :component_id";
        $specificComponent = self::$queryBuilder->table("\"" . $type::$tableChild . "\"")->select($filter, [':component_id' => $id]);
        
        //son arrays de arrays, por eso hay que especificar $component[0]
        if ($component && $specificComponent) {
            $specificComponentInstance = new $type($specificComponent[0]);
            $componentInstance = new Component($component[0]);
            $componentInstance->setSpecificComponent($specificComponentInstance);
            return $componentInstance;
        }
        return null;
    }

    public function create(array $data, string $type = null)
    {
        if ($type !== null) {
            if (!class_exists($type)) {
                $type = "Paw\\App\\Models\\Components\\$type";
            }
            $specificComponent = new $type($data);
            $component = new Component($data);

            if ($component && $specificComponent){
                $arrayComponent = $component->toArray();
                $arrayComponent["type"] = $type::$tableChild;
                $id = self::$queryBuilder->table($this->table())->insert($arrayComponent);

                $arraySpecificComponent = $specificComponent->toArray();
                $arraySpecificComponent["component_id"] = $id;

                $idSpecificComponent = self::$queryBuilder->table($type::$tableChild)->insert($arraySpecificComponent);
            }
            if ($idSpecificComponent && $id) {
                $model = $this->getByIdAndType($id, $type);
                return $model;
            }
        } else {
            // Llama al método de la clase padre si no se proporciona el tipo
            return parent::create($data);
        }
    }

    public function getPage(int $itemsPerPage, int $page)
    {
        $offset = $itemsPerPage * $page;
        $results = self::$queryBuilder->table($this->table())->selectPage(null, [], $itemsPerPage, $offset);
        if ($results) {
            return $results;
        }
        return $results;
    }
}