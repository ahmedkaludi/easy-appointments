<?php
if (!function_exists('ea52_findFile')) {
	function ea52_findFile($class)
	{
		if (0 !== strpos($class, 'tad_EA52_')) {
			return false;
		}

		return dirname(__FILE__) . '/src/' . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
	}
}

if (!function_exists('ea52_autoload')) {
	function ea52_autoload($class)
	{
		$file = ea52_findFile($class);
		if ($file) {
			include $file;

			return true;
		}

		return false;
	}
}

spl_autoload_register('ea52_autoload');
