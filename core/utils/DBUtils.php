<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 31/1/18
 * Time: 4:58 PM
 */
namespace core\utils;
use \PDO as PDO;

Class DBUtils{
    private $conn = false;
    private static $instance = false;

    private function __construct() {}
    public static function getInstance() {
        if( self::$instance === false ) {
            self::$instance = new DBUtils();
            self::$instance->connect();
        }
        return self::$instance;
    }

    /**
     * Method the create new Connection
     */
    private function connect() {
        $database = "pinkblue";
        $username = "root";
        $password = "";
        $this->conn = new PDO("mysql:host=localhost;dbname=$database", $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Method to execute query and return response
     *
     * @param $query
     * @param array $bind_params
     * @return mixed
     */
    public function query($query, $bind_params=[]) {
        $statement = $this->conn->prepare($query);
        $statement->execute($bind_params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Method to execute given query
     *
     * @param $query
     * @param array $bind_params
     */
    public function executeQuery($query, $bind_params=[]){
        $statement = $this->conn->prepare($query);
        $statement->execute($bind_params);
    }

    public function results($query, $bind_params=[]) {
        return $this->query($query, $bind_params);
    }
/*
 * Method to retrieve one row
 */
    public function retrieveOne($query, $bind_params=[]) {
        $results = $this->query($query . " LIMIT 0,1", $bind_params);
        return isset($results[0]) ? $results[0] : [];
    }

    /**
     * Method to insert new row
     *
     * @param $tableName
     * @param $data
     * @return mixed
     */
    public function insert($tableName, $data) {
        $bindValues = [];
        $insertColumns = [];
        $bindParams = [];
        foreach($data as $key=>$value) {
            $bindValues[] = "?";
            $bindParams[] = $value;
            $insertColumns[] = $key;
        }
        $bindValueString = "(".implode(",", $bindValues).")";
        $insertColumnString = "(".implode(",", $insertColumns).")";
        $statement = $this->conn->prepare("INSERT INTO $tableName $insertColumnString VALUES $bindValueString");
        $statement->execute($bindParams);

        return $this->conn->lastInsertId();
    }
}