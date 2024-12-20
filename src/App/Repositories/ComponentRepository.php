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

    public function addStockById(int $id, int $quantity) {
        $component = $this->getByIdAndType($id);
        $filter = "id = :id";
        $data = ["stock" => ($component->getStock() + $quantity)];
        $c = self::$queryBuilder->table($this->table())->update($data, $filter, [':id' => $id]);
    }

    public function deleteById(int $id) {
        $filter = "id = :id";
        $component = self::$queryBuilder->table($this->table())->select($filter, [':id' => $id]);
        $type = ($component[0])["type"];
        
        $type = "Paw\\App\\Models\\Components\\" . ucfirst($type);
        $filter = "component_id = :component_id";
        $sc = self::$queryBuilder->table("\"" . $type::$tableChild . "\"")->delete($filter, [':component_id' => $id]);
        $filter = "id = :id";
        $c = self::$queryBuilder->table($this->table())->delete($filter, [':id' => $id]);
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

                $idSpecificComponent = self::$queryBuilder->table("\"" . $type::$tableChild . "\"")->insert($arraySpecificComponent);
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
        $filterComponent = [];
        $paramsComponent = [];
        $filterSpecificComponent = [];
        $paramsSpecificComponent = [];
        if ($query) {
            $filterComponent[] = "LOWER(description) LIKE :query";
            $paramsComponent[':query'] = '%' . strtolower($query) . '%';
        }
        if ($socket) {
            $filterSpecificComponent[] = "socket = :socket";
            $paramsSpecificComponent[':socket'] = $socket;
        }
        
        return $this->getPageAyPc($offset, $itemsPerPage, $type, 
        $filterSpecificComponent, $paramsSpecificComponent, $filterComponent, $paramsComponent);
    }

    public function getPageAyPcVideoCard(int $itemsPerPage, int $page, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "videoCard";
        $filterComponent = [];
        $paramsComponent = [];
        $filterSpecificComponent = [];
        $paramsSpecificComponent = [];
        if ($query) {
            $filterComponent[] = "LOWER(description) LIKE :query";
            $paramsComponent[':query'] = '%' . strtolower($query) . '%';
        }
        return $this->getPageAyPc($offset, $itemsPerPage, $type, 
        $filterSpecificComponent, $paramsSpecificComponent, $filterComponent, $paramsComponent);
    }

    public function getPageAyPcMotherboard(int $itemsPerPage, int $page, string $socket = null, int $memory_slots = null, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "motherboard";
        $filterComponent = [];
        $paramsComponent = [];
        $filterSpecificComponent = [];
        $paramsSpecificComponent = [];
        if ($query) {
            $filterComponent[] = "LOWER(description) LIKE :query";
            $paramsComponent[':query'] = '%' . strtolower($query) . '%';
        }
        if ($socket) {
            $filterSpecificComponent[] = "socket = :socket";
            $paramsSpecificComponent[':socket'] = $socket;
        }
        if ($memory_slots) {
            $filterSpecificComponent[] = "memory_slots = :memory_slots";
            $paramsSpecificComponent[':memory_slots'] = $memory_slots;
        }
        
        return $this->getPageAyPc($offset, $itemsPerPage, $type, 
        $filterSpecificComponent, $paramsSpecificComponent, $filterComponent, $paramsComponent);
    }

    public function getPageAyPcMemory(int $itemsPerPage, int $page, int $modules = null, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "memory";
        $filterComponent = [];
        $paramsComponent = [];
        $filterSpecificComponent = [];
        $paramsSpecificComponent = [];
        if ($query) {
            $filterComponent[] = "LOWER(description) LIKE :query";
            $paramsComponent[':query'] = '%' . strtolower($query) . '%';
        }
        if ($modules) {
            $filterSpecificComponent[] = "modules >= :modules";
            $paramsSpecificComponent[':modules'] = $modules;
        }

        return $this->getPageAyPc($offset, $itemsPerPage, $type, 
        $filterSpecificComponent, $paramsSpecificComponent, $filterComponent, $paramsComponent);
    }

    public function getPageAyPcPowerSupply(int $itemsPerPage, int $page, string $typeCase = null, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "powerSupply";
        $filterComponent = [];
        $paramsComponent = [];
        $filterSpecificComponent = [];
        $paramsSpecificComponent = [];
        if ($query) {
            $filterComponent[] = "LOWER(description) LIKE :query";
            $paramsComponent[':query'] = '%' . strtolower($query) . '%';
        }
        if ($typeCase) {
            $filterSpecificComponent[] = "type = :type";
            $paramsSpecificComponent[':type'] = $typeCase;
        }

        return $this->getPageAyPc($offset, $itemsPerPage, $type, 
        $filterSpecificComponent, $paramsSpecificComponent, $filterComponent, $paramsComponent);
    }

    public function getPageAyPcInternalHardDrive(int $itemsPerPage, int $page, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "internalHardDrive";
        $filterComponent = [];
        $paramsComponent = [];
        $filterSpecificComponent = [];
        $paramsSpecificComponent = [];
        if ($query) {
            $filterComponent[] = "LOWER(description) LIKE :query";
            $paramsComponent[':query'] = '%' . strtolower($query) . '%';
        }
        return $this->getPageAyPc($offset, $itemsPerPage, $type, 
        $filterSpecificComponent, $paramsSpecificComponent, $filterComponent, $paramsComponent);
    }

    public function getPageAyPcCpuFan(int $itemsPerPage, int $page, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "cpuFan";
        $filterComponent = [];
        $paramsComponent = [];
        $filterSpecificComponent = [];
        $paramsSpecificComponent = [];
        if ($query) {
            $filterComponent[] = "LOWER(description) LIKE :query";
            $paramsComponent[':query'] = '%' . strtolower($query) . '%';
        }
        return $this->getPageAyPc($offset, $itemsPerPage, $type, 
        $filterSpecificComponent, $paramsSpecificComponent, $filterComponent, $paramsComponent);
    }

    public function getPageAyPcCasePc(int $itemsPerPage, int $page, string $typePowerSupply = null, string $query = null)
    {
        $offset = $itemsPerPage * $page;
        $type = "casePc";
        $filterComponent = [];
        $paramsComponent = [];
        $filterSpecificComponent = [];
        $paramsSpecificComponent = [];
        if ($query) {
            $filterComponent[] = "LOWER(description) LIKE :query";
            $paramsComponent[':query'] = '%' . strtolower($query) . '%';
        }
        
        if ($typePowerSupply) {
            $filterSpecificComponent[] = "type = :type";
            $paramsSpecificComponent[':type'] = $typePowerSupply;
        }
        
        return $this->getPageAyPc($offset, $itemsPerPage, $type, 
        $filterSpecificComponent, $paramsSpecificComponent, $filterComponent, $paramsComponent);
    }

    private function getPageAyPc(int $offset, int $itemsPerPage, string $type, 
    $filterSpecificComponent = null, $paramsSpecificComponent = null, $filterComponent = null, $paramsComponent = null)
    {
        $filterQuerySpecificComponent = $filterSpecificComponent ? implode(' AND ', $filterSpecificComponent) : null; 
        $results = self::$queryBuilder->table("\"" . $type . "\"")
            ->selectPage($filterQuerySpecificComponent, $paramsSpecificComponent, $itemsPerPage, $offset);
        if (!$results) {
            return null;
        }

        $components = [];
        
        if($filterComponent){
            $filterComponent[] = "id = :id";
            $filterQueryComponent = $filterComponent ? implode(' AND ', $filterComponent) : null;

            foreach($results as $result){
                $component = self::$queryBuilder->table($this->table())
                ->select($filterQueryComponent, [":query" => $paramsComponent[':query'],':id' => $result["component_id"]]);
                $components = array_merge($components, $component);
            }
        } else {
            $filterComponent = "id = :id";

            foreach($results as $result){
                $component = self::$queryBuilder->table($this->table())
                ->select($filterComponent, [':id' => $result["component_id"]]);
                $components = array_merge($components, $component);
            }
        }

        return $components;
    }
}