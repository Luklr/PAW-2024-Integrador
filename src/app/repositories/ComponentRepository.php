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
        $filter = "component_id = :component_id";
        $specificComponent = self::$queryBuilder->table($type::$tableChild)->select($filter, [':component_id' => $id]);
        if ($component && $Child) {
            $specificComponentInstance = new $type($specificComponent);
            $componentInstance = new Component($component);
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
                // echo "<pre>";
                // var_dump($type::$tableChild);
                // var_dump($arrayChild);
                // die;
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