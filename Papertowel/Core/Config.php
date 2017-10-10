<?php

namespace Papertowel\Core;

class Config
{
    private $config;

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config.php';
    }

    public function getDefaultLanguage() {
        return $this->config['default_language'];
    }

    public function getDatabaseConfig() {
        return $this->config['db'];
    }

    /**
     * @param $key
     * @return mixed
     * @deprecated
     */
    public function get($key) {
        return $this->config[$key];
    }

}