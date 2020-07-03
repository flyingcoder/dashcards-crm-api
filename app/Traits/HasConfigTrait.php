<?php

namespace App\Traits;

use App\Configuration;

trait HasConfigTrait
{
    public function getAllConfigs()
    {
        $configs = Configuration::all();
        $mapArray  = [];
        foreach ($configs as $config) {
            $mapArray[$config->key] = $this->castValue($config);
        }
        return $mapArray;
    }

	public function isKeyExists($key)
	{
		return Configuration::where('key', $key)->exists();
	}

	public function castValue($config)
    {
    	if ($config->type == 'array') {
    		$value = json_decode($config->value, true);
    	} elseif ($config->type == 'object') {
    		$value = json_decode($config->value);
    	} elseif ($config->type == 'integer') {
    		$value = (int) $config->value;
    	} elseif ($config->type == 'float') {
    		$value = (float) $config->value;
    	} elseif ($config->type == 'boolean') {
    		$value = (bool) $config->value;
    	} else {
    		$value = $config->value;
    	}
    	return $value;
    }

    public function storeValue($type = null, $value)
    {
    	if (is_null($type)) {
    		$type = 'string';
    	}
    	if ($type == 'array' || $type == 'object') {
    		$value = json_encode($value);
    	} else {
    		$value = $value;
    	}
    	return $value;
    }
}