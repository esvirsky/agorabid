<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * This class is responsible for caching data
 * for fast retrieval between requests
 * 
 * @author esvirsky
 */
class CacheLib
{
	const CACHE_FILE_NAME = "application_cache";
	
	private $_CI;
	private $_cache;
	
	public function CacheLib()
	{
		$this->_CI =& get_instance();
	}
	
	/**
	 * Checks if there is an object cached for this key
	 * 
	 * @param $key
	 * @return unknown_type
	 */
	public function existsCache($key)
	{
		if(!isset($this->_cache))
			$this->_loadCache();
			
		return isset($this->_cache[$key]) ? true : false;
	}
	
	/**
	 * Gets cached object by key
	 * 
	 * @param $key
	 * @return unknown_type
	 */
	public function getCache($key)
	{
		if(!isset($this->_cache))
			$this->_loadCache();
			
		return isset($this->_cache[$key]) ? $this->_cache[$key] : null;
	}
	
	/**
	 * Sets a cached object by key
	 * 
	 * @param $key
	 * @param $value
	 * @return unknown_type
	 */
	public function setCache($key, $value)
	{
		if(!isset($this->_cache))
			$this->_loadCache();
		
		$this->_cache[$key] = $value;
		$this->_saveCache();
	}
	
	/**
	 * Loads the cache from storage
	 * 
	 * @return unknown_type
	 */
	private function _loadCache()
	{
		$filePath = $this->_getCacheFilePath();
		if(!file_exists($filePath))
			file_put_contents($filePath, serialize(array()));
		
		$this->_cache = unserialize(file_get_contents($filePath));
	}
	
	/**
	 * Saves the cache to storage
	 * 
	 * @return unknown_type
	 */
	private function _saveCache()
	{
		file_put_contents($this->_getCacheFilePath(), serialize($this->_cache));
	}
	
	/**
	 * Gets the file path of the cache file
	 * 
	 * @return unknown_type
	 */
	private function _getCacheFilePath()
	{
		$path = $this->_CI->config->item('cache_path');
		$cachePath = ($path == '') ? BASEPATH.'cache/' : $path;
		return $cachePath . self::CACHE_FILE_NAME;
	}
}

?>
