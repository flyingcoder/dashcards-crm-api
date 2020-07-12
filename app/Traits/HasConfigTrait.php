<?php

namespace App\Traits;

use App\Configuration;

trait HasConfigTrait
{
    /**
     *
     * @param $key
     * @return  \App\Configuration
     */
    public function getConfig($key)
    {
        return Configuration::where('key', $key)->firstOrfail();
    }

    /**
     *
     * @return array
     */
    public function getAllConfigs()
    {
        $configs = Configuration::all();
        $mapArray = [];
        foreach ($configs as $config) {
            $mapArray[$config->key] = $this->castValue($config);
        }
        return $mapArray;
    }

    /**
     *
     * @param $key
     * @param null $default
     * @return \App\Configuration
     */
    public function getConfigByKey($key, $default = null)
    {
        $config = Configuration::where('key', $key)->first();
        if ($config) {
            return $this->castValue($config);
        }
        return $default;
    }

    /**
     *
     * @param $key
     * @param string $value
     * @param string $type
     * @return void
     */
    public function setConfigByKey($key, $value = "", $type = 'string')
    {
        if ($this->isKeyExists($key)) {
            $config = Configuration::where('key', $key)->first();
            $config->type = trim($type);
            $config->value = $this->storeValue($type, $value);
            $config->save();
        } else {
            Configuration::create([
                'key' => $key,
                'type' => $type,
                'value' => $this->storeValue($type, $value)
            ]);
        }
    }

    /**
     *
     * @param $key
     * @return mixed
     */
    public function isKeyExists($key)
    {
        return Configuration::where('key', $key)->exists();
    }

    /**
     *
     * @param $config
     * @return mixed
     */
    public function castValue($config)
    {
        if ($config->type == 'array') {
            $value = json_decode($config->value, true);
        } elseif ($config->type == 'object') {
            $value = json_decode($config->value);
        } elseif ($config->type == 'integer') {
            $value = (int)$config->value;
        } elseif ($config->type == 'float') {
            $value = (float)$config->value;
        } elseif ($config->type == 'boolean') {
            $value = (bool)$config->value;
        } else {
            $value = $config->value;
        }
        return $value;
    }

    /**
     *
     * @param string $type
     * @param $value
     * @return mixed
     */
    public function storeValue($type, $value)
    {
        if (is_null($type)) {
            $type = 'string';
        }
        if ($type == 'array' || $type == 'object') {
            $value = json_encode($value);
        }

        return $value;
    }
}