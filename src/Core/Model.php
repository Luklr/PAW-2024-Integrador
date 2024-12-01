<?php

namespace Paw\Core;

use Paw\Core\Traits\Loggeable;

abstract class Model {
    use Loggeable;
    
    static public string $table;
    
    protected array $fields = [];
    
    public function __construct(array $values) {
        $this->set($values);
    }
    
    /**
     * Set multiple fields of the model using an associative array.
     *
     * @param array $values An associative array of field names and their values.
     * @return void
     */
    
    public function set(array $values)
    {
        foreach (array_keys($this->fields) as $field) {
            if (!isset($values[$field])) {
                continue;
            }
            $method = "set" . ucfirst($field);
            $this->$method($values[$field]);
        }
    }

    public function toArray(): array
    {
        $data = [];
        foreach (array_keys($this->fields) as $field) {
            $method = "get" . ucfirst($field);
            $data[$field] = $this->$method();
        }
        return $data;
    }

}