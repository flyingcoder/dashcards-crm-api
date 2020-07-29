<?php

namespace App\Http\Controllers;

use App\Configuration;
use App\Events\GlobalEvent;
use App\Policies\ConfigurationPolicy;
use App\Traits\HasConfigTrait;

class ConfigurationController extends Controller
{
	use HasConfigTrait;
	
	protected $types = ['integer', 'string', 'float', 'boolean', 'object', 'array'];

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
    	return response()->json($this->getAllConfigs(), 200);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getByKey($key)
    {
    	$config =  Configuration::where('key', $key)->firstOrFail();
    	$config->value = $this->castValue($config);

    	return $config;
    }

    /**
     * @return mixed
     */
    public function saveByKey()
    {
    	(new ConfigurationPolicy)->update();

    	request()->validate([
    			'key' => 'required|string', 
    			'type' => 'sometimes|in:'.implode(',', $this->types),
    			'value' => 'required'
    		]);

    	if ($this->isKeyExists(request()->key)) {
    		$config = Configuration::where('key',request()->key )->firstOrFail();
    		$config->type = request()->type ?? 'string';
	    	$config->value = $this->storeValue(request()->type ?? 'string', request()->value);
	    	$config->save();
    	} else {
    		$config = Configuration::create([
    				'key' => request()->key,
    				'type' => request()->type ?? 'string',
	    			'value' => $this->storeValue(request()->type ?? 'string', request()->value)
    			]);
    	}
    	
    	broadcast(new GlobalEvent(array_merge(['type' => 'configs'], $this->getAllConfigs())));

    	$config->value = $this->castValue($config);

    	return $config;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkSave()
    {
    	(new ConfigurationPolicy)->update();
    	
    	request()->validate([
	    		'data' => 'required|array',
    			'data.key' => 'required|string', 
    			'data.type' => 'sometimes|in:'.implode(',', $this->types),
    			'data.value' => 'required'
    		]);

    	foreach (request()->data as $item) {
	    	if ($this->isKeyExists($item->key)) {
	    		$config = Configuration::where('key', $item->key )->firstOrFail();
	    		$config->type = $item->type ?? 'string';
		    	$config->value = $this->storeValue($item->type ?? 'string', $item->value);
		    	$config->save();
	    	} else {
	    		$config = Configuration::create([
	    				'key' => $item->key,
	    				'type' => $item->type ?? 'string',
		    			'value' => $this->storeValue($item->type ?? 'string', $item->value)
	    			]);
	    	}
    	}
    	
    	broadcast(new GlobalEvent(array_merge(['type' => 'configs'], $this->getAllConfigs())));

    	return response()->json(['message' => 'Success'], 200);
    }
}
