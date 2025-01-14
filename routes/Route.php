<?php
namespace Routes;

use Database\DBConnection;

class Route
{
// variable qui stock le path et les actions.
    public $path;
    public $action;
    public $matches;

    public function __construct(string $path, string $action)
    {
        $this->path = trim($path, '/');
        $this->action = $action;
    }

    public function matches(string $url): bool
    {

        $path = preg_replace('#:([a-zA-Z0-9]+)#', '([^/]+)', $this->path);
        //  path to compare.
        $pathToMatche = "#^$path$#";

        if (preg_match($pathToMatche, $url, $matches)) {
            $this->matches = $matches;
            return true;
        } else {
            return false;
        }
    }

    public function execute()
    {
        $params = explode('@', $this->action);
       
        $controller = new $params[0](new DBConnection());
        $method = $params[1];
        return isset($this->matches[1]) ? $controller->$method($this->matches[1]) : $controller->$method();
    }
}