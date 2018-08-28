<?php
/**
 * Autoloader class
 * @author ad
 *
 */
class Autoloader
{

	// Registered namespace prefixes
	private $namespaces_map = [
		'Code' => __DIR__ . '/src/',
		'App' => __DIR__ . '/App/'
	];

	public function register()
	{
		$namespaces_map = $this->namespaces_map;
		spl_autoload_register(function ($class) use ($namespaces_map) {
			foreach ($namespaces_map as $namespace => $base_dir) {
				$first_separator_pos = strpos($class, '\\');
				if ($first_separator_pos !== false) {
					// remove registered namespace prefix
					$prefix = substr($class, 0, $first_separator_pos);
					
					if ($prefix !== $namespace) {
						// skip if class dont use namespace prefix $namespace
						continue;
					}
					$class = substr($class, $first_separator_pos);
				}
				// path to class file (including file name but without .php prefix)
				$class_path = str_replace('\\', '/', $class);
				require_once trim($base_dir, '') . $class_path . '.php';
			}
		});
	}
}