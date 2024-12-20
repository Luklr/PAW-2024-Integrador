<?php

namespace Paw\Core\Traits;

use Monolog\Logger;
use Paw\Core\LoggerFactory;

trait Loggeable{
    public $logger;
    public function setLogger(?Logger $logger = null) {
        if ($logger === null) {
            $this->logger = LoggerFactory::getLogger();
        } else {
            $this->logger = $logger;
        }
    }
}