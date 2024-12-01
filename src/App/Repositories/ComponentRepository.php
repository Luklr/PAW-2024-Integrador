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
        if (!$id) 
            return null;
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

    public function getStockById(int $id) 
    {
        if (!$id) 
            return null;
        $filter = "id = :id";
        $component = self::$queryBuilder->table($this->table())->select($filter, [':id' => $id]);
        return $component[0]["stock"];
    }

    public function reduceStockById(int $id, int $quantity) {
        $component = $this->getByIdAndType($id);
        $filter = "id = :id";
        $data = ["stock" => ($component->getStock() - $quantity)];
        $c = self::$queryBuilder->table($this->table())->update($data, $filter, [':id' => $id]);
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

    public function getPage(int $itemsPerPage, int $page, string $query = null, string $type = null)
    {
        $offset = $itemsPerPage * $page;

        $filters = [];
        $params = [];

        if ($query) {
            $filters[] = "LOWER(description) LIKE :query";
            $params[':query'] = '%' . strtolower($query) . '%';
        }

        if ($type) {
            $filters[] = "type = :type";
            $params[':type'] = $type;
        }

        $filterQuery = $filters ? implode(' AND ', $filters) : null;

        $results = self::$queryBuilder->table($this->table())->selectPage($filterQuery, $params, $itemsPerPage, $offset);
        if ($results) {
            return $results;
        }
        return $results;
    }

    public function getPageAyPcCpu(int $itemsPerPage, int $page, string $socket = null, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "cpu";
        $filters = [];
        $params = [];
        if ($query) {
            $filters[] = "LOWER(description) LIKE :query";
            $params[':query'] = '%' . strtolower($query) . '%';
        }
        if ($socket) {
            $filters[] = "socket = :socket";
            $params[':socket'] = $socket;
        }
        $filterQuery = $filters ? implode(' AND ', $filters) : null;

        return $this->getPageAyPc($offset, $itemsPerPage, $type, $filterQuery, $params);
    }

    public function getPageAyPcVideoCard(int $itemsPerPage, int $page, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "videoCard";
        $filters = [];
        $params = [];
        if ($query) {
            $filters[] = "LOWER(description) LIKE :query";
            $params[':query'] = '%' . strtolower($query) . '%';
        }
        $filterQuery = $filters ? implode(' AND ', $filters) : null;

        return $this->getPageAyPc($offset, $itemsPerPage, $type, $filterQuery, $params);
    }

    public function getPageAyPcMotherboard(int $itemsPerPage, int $page, string $socket = null, int $memory_slots = null, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "motherboard";
        $filters = [];
        $params = [];
        if ($query) {
            $filters[] = "LOWER(description) LIKE :query";
            $params[':query'] = '%' . strtolower($query) . '%';
        }
        if ($socket) {
            $filters[] = "socket = :socket";
            $params[':socket'] = $socket;
        }
        if ($memory_slots) {
            $filters[] = "memory_slots = :memory_slots";
            $params[':memory_slots'] = $memory_slots;
        }
        $filterQuery = $filters ? implode(' AND ', $filters) : null;

        return $this->getPageAyPc($offset, $itemsPerPage, $type, $filterQuery, $params);
    }

    public function getPageAyPcMemory(int $itemsPerPage, int $page, int $modules = null, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "memory";
        $filters = [];
        $params = [];
        if ($query) {
            $filters[] = "LOWER(description) LIKE :query";
            $params[':query'] = '%' . strtolower($query) . '%';
        }
        if ($modules) {
            $filters[] = "modules >= :modules";
            $params[':modules'] = $modules;
        }
        $filterQuery = $filters ? implode(' AND ', $filters) : null;

        return $this->getPageAyPc($offset, $itemsPerPage, $type, $filterQuery, $params);
    }

    public function getPageAyPcPowerSupply(int $itemsPerPage, int $page, string $typeCase = null, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "powerSupply";
        $filters = [];
        $params = [];
        if ($query) {
            $filters[] = "LOWER(description) LIKE :query";
            $params[':query'] = '%' . strtolower($query) . '%';
        }
        if ($typeCase) {
            $filters[] = "type = :type";
            $params[':type'] = $typeCase;
        }
        $filterQuery = $filters ? implode(' AND ', $filters) : null;

        return $this->getPageAyPc($offset, $itemsPerPage, $type, $filterQuery, $params);
    }

    public function getPageAyPcInternalHardDrive(int $itemsPerPage, int $page, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "internalHardDrive";
        $filters = [];
        $params = [];
        if ($query) {
            $filters[] = "LOWER(description) LIKE :query";
            $params[':query'] = '%' . strtolower($query) . '%';
        }
        $filterQuery = $filters ? implode(' AND ', $filters) : null;

        return $this->getPageAyPc($offset, $itemsPerPage, $type, $filterQuery, $params);
    }

    public function getPageAyPcCpuFan(int $itemsPerPage, int $page, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "cpuFan";
        $filters = [];
        $params = [];
        if ($query) {
            $filters[] = "LOWER(description) LIKE :query";
            $params[':query'] = '%' . strtolower($query) . '%';
        }
        $filterQuery = $filters ? implode(' AND ', $filters) : null;

        return $this->getPageAyPc($offset, $itemsPerPage, $type, $filterQuery, $params);
    }

    public function getPageAyPcCasePc(int $itemsPerPage, int $page, string $typePowerSupply = null, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "casePc";
        $filters = [];
        $params = [];
        if ($query) {
            $filters[] = "LOWER(description) LIKE :query";
            $params[':query'] = '%' . strtolower($query) . '%';
        }
        if ($typePowerSupply) {
            $filters[] = "type = :type";
            $params[':type'] = $typePowerSupply;
        }
        $filterQuery = $filters ? implode(' AND ', $filters) : null;

        return $this->getPageAyPc($offset, $itemsPerPage, $type, $filterQuery, $params);
    }

    private function getPageAyPc(int $offset, int $itemsPerPage, string $type, $filterQuery = null, $params = null)
    {
        $results = self::$queryBuilder->table("\"" . $type . "\"")->selectPage($filterQuery, $params, $itemsPerPage, $offset);
        if (!$results) {
            return null;
        }

        $components = [];
        $filter = "id = :id";

        foreach($results as $result){
            $component = self::$queryBuilder->table($this->table())->select($filter, [':id' => $result["component_id"]]);
            $components = array_merge($components, $component);
        }

        return $components;
    }
}