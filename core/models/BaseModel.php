<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 31/1/18
 * Time: 10:32 PM
 */
namespace core\models;
class BaseModel{
    public function __construct($data)
    {
        foreach($data as $key=>$value){
            if(in_array($key,array_keys(get_class_vars(get_class($this))))){
                $this->{$key}=$value;
            }
        }
    }

    public function validate(){

    }

    public function save(){

    }
}