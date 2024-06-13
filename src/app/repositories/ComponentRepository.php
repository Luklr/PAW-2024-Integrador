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
        $Child = self::$queryBuilder->table($type::$tableChild)->select($filter, [':component_id' => $id]);
        if ($component && $Child) {
            $data = array_merge($component[0], $Child[0]);
            return new $type($data);
        }
        return null;
    }

    public function create(array $data, string $type = null)
    {
        if ($type !== null) {
            if (!class_exists($type)) {
                $type = "Paw\\App\\Models\\Components\\$type";
            }
            $model = new $type($data);
            if ($model) {
                $arrayComponent = $model->toArray();
                $arrayComponent["type"] = $type::$tableChild;
                $id = self::$queryBuilder->table($this->table())->insert($arrayComponent);

                $arrayChild = $model->toArrayChild();
                $arrayChild["component_id"] = $id;
                $idChild = self::$queryBuilder->table($type::$tableChild)->insert($arrayChild);
            }
            if ($idChild && $id) {
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