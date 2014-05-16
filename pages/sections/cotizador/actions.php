<?php

require_once __DIR__ . "/../../../config/smarty.php";
require_once __DIR__ . "/../../../config/autoloader.php";
use db\ClienteDB;

if (isset($_POST['action'])) {
    $action = new Action();
    $function = $_POST['action'];
    if (method_exists($action, $function))
        die($action->$function());
    else
        die('bad request.');
} else {
    die('bad request..');
}
echo json_encode($response);

class Action {

    public function __construct() {
        
    }

    public function buscarClientePorNit() {        
        echo json_encode(ClienteDB::getCliente($_POST['nit']));
    }
    
    public function buscarClientePorNombre() {        
        echo json_encode(ClienteDB::getClientePorNombre($_POST['empresa']));
    }

}
