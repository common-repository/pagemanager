<?php
namespace Pagemanager;

class AutoLoader
{
    private $namespace;

    public function __construct($namespace = null)
    {
        $this->namespace = $namespace;
    }

    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    public function loadClass($className)
    {
        if ($this->namespace !== null) {
            $className = str_replace($this->namespace . '\\', '', $className);
        }
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

        $file = PAGEMANAGER_PLUGIN_SRC_PATH . $className . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
}