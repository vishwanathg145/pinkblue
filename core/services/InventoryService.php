<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 1/2/18
 * Time: 1:08 AM
 */
namespace core\services;

use core\services\UserService;
use core\models\InventoryModel;
use core\utils\Exceptions;

class InventoryService{

    public function addProduct($userId,$data){
        if(!empty($data)){
            $prodModel=new InventoryModel($data);
            return $prodModel->addProduct($userId);
        }
        throw new Exceptions('Data Missing');
    }
}
