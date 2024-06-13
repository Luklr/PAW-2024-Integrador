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
        $hijo = self::$queryBuilder->table($type::$tableHijo)->select($filter, [':component_id' => $id]);
        if ($component && $hijo) {
            $data = array_merge($component[0], $hijo[0]);
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
                $arrayComponent["type"] = $type::$tableHijo;
                $id = self::$queryBuilder->table($this->table())->insert($arrayComponent);

                $arrayHijo = $model->toArrayHijo();
                $arrayHijo["component_id"] = $id;
                $idHijo = self::$queryBuilder->table($type::$tableHijo)->insert($arrayHijo);
            }
            if ($idHijo && $id) {
                $model = $this->getByIdAndType($id, $type);
                return $model;
            }
        } else {
            // Llama al método de la clase padre si no se proporciona el tipo
            return parent::create($data);
        }
    }

}