<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Common helper functions. This file gets loaded every time. Can put
 * any required loading in here.
 * 
 * @author esvirsky
 * @copyright Edward Svirsky
 */

if(!function_exists('error'))
{
	/**
	 * This function handles errors all over the application - logging
	 * errors and displaying the proper messages.
	 *
	 * @param unknown_type $message
	 */
	function error($message = null)
	{
		throw $message == null ? new Exception() : new Exception($message);
	}
}

if(!function_exists('errorHandler'))
{
	/**
	 * This function get's called when there is an error
	 *
	 * @param unknown_type $exception
	 */
	function errorHandler($errno, $errstr, $errfile, $errline)
	{
		if($errno == E_USER_NOTICE || $errno == E_STRICT)
			return true;
		
		$CI =& get_instance();
		log_message("error", $errno . " :: " . $errstr . " :: " . $errfile . " :: " . $errline);
		show_error($CI->lang->line("default_error"));
	}
}
if(!function_exists('exceptionHandler'))
{
	/**
	 * This function handles exceptions system wide
	 *
	 * @param unknown_type $exception
	 */
	function exceptionHandler($exception)
	{
		$CI =& get_instance();
		log_message("error", $exception->__toString());
		show_error($CI->config->item("live") ? $CI->lang->line("default_error") : $exception->__toString());
	}
}

if(!function_exists('__autoload'))
{
	/**
	 * This function autoloads classes
	 *
	 * @param unknown_type $name class name
	 */
	function __autoload($name)
	{
		if(strpos($name, "Entity"))
			include_once(APPPATH . "entities/" . $name . EXT);
		else if(strpos($name, "Exception"))
			include_once(APPPATH . "exception/" . $name . EXT);
	}
}

if(!function_exists('toEntity'))
{
	/**
	 * Converts an IgnitedRecord model to an entity. Entities
	 * are simplified objects with extra functionality, that can
	 * be passed around the application
	 *
	 * @param IR_Record $model
	 */
	function toEntity($model)
	{
		if(empty($model))
			return null;

		$className = get_class($model->__instance);
		$entityName = str_replace("Model", "Entity", $className);
		$entity = new $entityName();
		$modelData = $model->get_data();
		foreach($modelData as $property => $value)
			$entity->$property = $value;

		if(isset($model->__instance->relations))
		{
			foreach($model->__instance->relations as $relationName => $relation)
			{
				if(isset($model->$relationName))
				{
					if(is_array($model->$relationName))
						$entity->$relationName = toEntities($model->$relationName);
					else
						$entity->$relationName = toEntity($model->$relationName);
				}
				else if(property_exists($model, $relationName)) // property exists but is null
				{
					if($relation->plural)
						$entity->$relationName = array();
					else
						$entity->$relationName = null;
				}
			}
		}	

		return $entity;
	}
}

if(!function_exists('toEntities'))
{
	/**
	 * Sames as toEntity but works on an array
	 *
	 * @param array of IR_Record $models
	 */
	function toEntities($models)
	{		
		$entities = array();
		foreach($models as $model)
			$entities[] = toEntity($model);
			
		return $entities;
	}
}

if(!function_exists('mapEntityToModel'))
{
	/**
	 * Maps an entity to a model. Maps only ints, floats and strings. Objects, not a deep clone.
	 * Changes the passed in model and returns that model.
	 *
	 * @param IR_Record $model
	 * @param Entity $entity
	 * @return the modified model
	 */
	function mapEntityToModel($model, $entity)
	{
		foreach($entity as $property => $value)
		{
			if(is_object($entity->$property) || is_array($entity->$property))
				continue;
			
			$model->$property = $value;
		}
		
		return $model;
	}
}

if(!function_exists('gotoView'))
{
	/**
	 * A convenience method for views
	 *
	 * @param $view
	 * @param $title
	 * @param $data
	 */
	function gotoView($view, $title, $data = array())
	{
		$CI =& get_instance();
		$CI->load->view($view, array("title" => $title) + $data);
	}
}

if(!function_exists('messageView'))
{
	/**
	 * A convenience method for the message view
	 *
	 * @param unknown_type 
	 */
	function messageView($title, $body)
	{
		$CI =& get_instance();
		$CI->load->view("general/message", array("title" => $title, "body" => $body));
	}
}

if(!function_exists('headerView'))
{
	/**
	 * A convenience method for the header view
	 *
	 * @param $view
	 * @param $title
	 * @param $description
	 */
	function headerView($title, $description)
	{
		$CI =& get_instance();
		$CI->load->view("header", array("title" => $title, "metaDescription" => $description));
	}
}

if(!function_exists('hashArrayByObjectProperty'))
{
	/**
	 * Hashes an array of objects by one of their properties
	 *
	 * @param unknown_type
	 * @return
	 */
	function hashArrayByObjectProperty($array, $property)
	{
		$ret = array();
		foreach($array as $obj)
			$ret[$obj->$property] = $obj;
			
		return $ret;
	}
}

if(!function_exists('compareFloats'))
{
	/**
	 * Compares two floats
	 *
	 * @param unknown_type
	 * @param unknown_type
	 * @param unknown_type Delta is the difference between the floats that is ingored
	 * @return true if equal; false otherwise
	 */
	function compareFloats($float1, $float2, $delta)
	{
		$diff = abs($float1 - $float2);
		return $diff <= $delta;
	}
}

if(!function_exists('isZero'))
{
	/**
	 * Compares a float to a zero
	 *
	 * @param unknown_type
	 * @return true if zero; false otherwise
	 */
	function isZero($float)
	{
		return abs($float) < .00001;
	}
}

if(!function_exists('filterObject'))
{
	/**
	 * Takes an object and properties, and returns a new object
	 * that is filtered by those properties
	 *
	 * @param $object - object to filter
	 * @param $properties array - properties to filter
	 * @param $include boolean - wether to include or exclude those properties
	 * @return the filetered object
	 */
	function filterObject($object, $properties, $include = true)
	{
		$objs = filterObjects(array($object), $properties, $include);
		return $objs[0];
	}
}

if(!function_exists('filterObjects'))
{
	/**
	 * Takes objects and properties, and returns new objects
	 * that are filtered by those properties
	 *
	 * @param $objects array - objects to filter
	 * @param $properties array - properties to filter
	 * @param $include boolean - wether to include or exclude those properties
	 * @return an array of filtered generic entity objects
	 */
	function filterObjects($objects, $properties, $include = true)
	{
		$newObjects = array();
		foreach($objects as $object)
		{
			$newObject = new GenericEntity();
			foreach($properties as $property)
			{
				if($include)
					if(in_array($property, $properties) && isset($object->$property))
						$newObject->$property = $object->$property;
				else
					if(!in_array($property, $properties))
						$newObject->$property = $object->$property;
			}
			$newObjects[] = $newObject;
		}
		
		return $newObjects;
	}
}

if(!function_exists('getObjectProperties'))
{
	/**
	 * Takes an array of objects and a property name, and returns an array
	 * of those properties
	 *
	 * @param $objects array - objects to filter
	 * @param $property string
	 * @return an array of properties
	 */
	function getObjectProperties($objects, $property)
	{	
		$newObjects = array();
		foreach($objects as $object)
			$newObjects[] = $object->$property;
		
		return $newObjects;
	}
}

if(!function_exists('formatDateForView'))
{
	/**
	 * Formats a timestamp for display
	 *
	 * @param $timestamp
	 * @return date string
	 */
	function formatDateForView($timestamp)
	{
		return date("m/d/y g:ia", $timestamp);
	}
}

if(!function_exists('cleanXss'))
{
	/**
	 * Cleans XSS in an associative array
	 *
	 * @param $array
	 * @return date string
	 */
	function cleanXss($array)
	{
		$CI =& get_instance();
		$new = array();
		foreach($array as $key => $value)
			$new[$key] = $CI->input->xss_clean($value);
			
		return $new;
	}
}

if(!function_exists('shortenString'))
{
	/**
	 * Shortens str to length characters. Adds "...." if there are more characters than length
	 * 
	 * @param $str
	 * @param $length
	 * @return unknown_type
	 */
	function shortenString($str, $length)
	{
		return substr($str, 0, $length) . (strlen($str) > $length ? "...." : "");
	}
}

$CI =& get_instance();
if($CI->config->item("live"))
{
	ini_set("display_errors", "0");
	ini_set('display_startup_errors', 'Off');
	error_reporting(0);
	set_error_handler("errorHandler");
}

set_exception_handler("exceptionHandler");
if($CI->config->item("maintenance") && $_SERVER['REMOTE_ADDR'] != $CI->config->item("maintenance_exception_ip"))
{
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	header('Status: 503 Service Temporarily Unavailable');
	header('Retry-After: 7200'); // in seconds
	include_once(APPPATH . "views/general/maintenance.php");
	exit();
}
