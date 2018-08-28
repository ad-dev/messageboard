<?php
use App\Factory;
use App\Registry;
use Code\Database;

// Inits application...

// Registers autoloader
require_once __DIR__ . '/autoload.php';
(new Autoloader())->register();

// Some constants
define('BASE_PATH', __DIR__);
define('TEMPLATES_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'templates');

// inits objects factory
Registry::getInstance()->set('factory', new Factory());

// connects to database
Registry::getInstance()
->set('db', new Database('localhost', 'root', 'rootdb', 'MessageBoard'));
