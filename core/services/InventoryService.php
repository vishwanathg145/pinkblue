<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 1/2/18
 * Time: 1:08 AM
 */
namespace core\services;

use core\Constants;
use core\models\InventoryModel;
use core\utils\DBUtils;
use core\utils\Exceptions;

class InventoryService{
    /**
     * Method to add new product
     *
     * @param $userId
     * @param $data
     * @return mixed
     * @throws Exceptions
     */
    public function addProduct($userId,$data){
        if(!empty($data)){
            $prodModel=new InventoryModel($data);
            return $prodModel->addProduct($userId);
        }
        throw new Exceptions('Data Missing');
    }

    /**
     * Method to update the product
     *
     * @param $userId
     * @param $productId
     * @param $data
     * @return mixed
     * @throws Exceptions
     */
    public function updateProduct($userId,$productId,$data){
        if($userId&&$productId&&!empty($data)){
        $invObj=new InventoryModel($data);
        $invObj->updateProduct($userId,$productId);
        return $productId;
        }
        throw new Exceptions('Data Missing',400);
    }

    /**
     * Method to retrieve the pending products
     *
     * @param $userId
     * @return mixed
     * @throws Exceptions
     */
    public function retrievePendingProducts($userId){
        if($userId){
            $roles=(new UserService())->getUserRoles($userId);
            $roles=array_column($roles,'role_id');
            if(in_array(Constants::ROLE_STORE_MANAGER,$roles)){
            return DBUtils::getInstance()->results("SELECT id AS ProductId,name AS ProductName,vendor AS Vendor,price AS Price,batch_no AS BatchNo,batch_date AS BatchDate,quantity AS Quantity, CASE action WHEN 1 then 'Pending for Adding' When 2 then 'Pending for update' WHEN 3 THEN 'Pending for Remove' END as Action FROM products WHERE status =". Constants::STATUS_PENDING." AND action!=".Constants::ACTION_OK);
            }else{
                throw new Exceptions('You do not have permission to access this data',403);
            }
        }
        throw new Exceptions('User Id Misssing',400);
    }

    /**
     * Method to retrieve the approved products
     *
     * @return mixed
     */
    public function retrieveApprovedProducts(){
                return DBUtils::getInstance()->results("SELECT id AS ProductId,name AS ProductName,vendor AS Vendor,price AS Price,batch_no AS BatchNo,batch_date AS BatchDate,quantity AS Quantity FROM products WHERE status =". Constants::STATUS_APPROVED." AND action=".Constants::ACTION_OK);
    }

    /**
     * Method to approve the product
     *
     * @param $userId
     * @param $productId
     * @return bool
     * @throws Exceptions
     */
    public function approveProducts($userId,$productId){
        if($userId&&$productId){
            $roles=(new UserService())->getUserRoles($userId);
            $roles=array_column($roles,'role_id');
            if(in_array(Constants::ROLE_STORE_MANAGER,$roles)){
             $productDetail=DBUtils::getInstance()->retrieveOne("SELECT id,action FROM products WHERE id=$productId AND status=".Constants::STATUS_PENDING);
             if(empty($productDetail)){
                 throw new Exceptions('Prouct Not found',404);
             }
             return $this->processApprove($productDetail);
            }else{
                throw new Exceptions('You do not have permission to access this data',403);
            }
        }
        throw new Exceptions('User Id or Product Id is Missing',400);
    }

    /**
     * Method to proces the approval of product
     *
     * @param $data
     * @return bool
     * @throws Exceptions
     */
    public function processApprove($data){
        if(!empty($data)){
            switch($data['action']){
                case Constants::ACTION_OK:
                    // Do nothing
                    break;
                case Constants::ACTION_ADD:
                case Constants::ACTION_UPDATE:
                    $query="UPDATE products SET status=".Constants::STATUS_APPROVED.", action=".Constants::ACTION_OK." WHERE id=".$data['id'];
                    break;
                case Constants::ACTION_REMOVE:
                    $query="DELETE FROM products WHERE id=".$data['id'];
                    break;
                default: throw new Exceptions('Invalid Operation',500);
            }
            DBUtils::getInstance()->executeQuery($query);
            return true;
        }
    }

    /**
     * Method to delete the product
     *
     * @param $userId
     * @param $productId
     * @return bool
     * @throws Exceptions
     */
    public function deleteProduct($userId,$productId){
        if($userId&&$productId){
            $productDetail=DBUtils::getInstance()->retrieveOne("SELECT id,action FROM products WHERE id=$productId AND status=".Constants::STATUS_APPROVED);
            if(empty($productDetail)){
                throw new Exceptions('Product Not Found');
            }
            $status=Constants::STATUS_PENDING;
            $action=Constants::ACTION_REMOVE;
            $roles=(new UserService())->getUserRoles($userId);
            $roles=array_column($roles,'role_id');
            if(in_array(Constants::ROLE_STORE_MANAGER,$roles)){
                $query="DELETE FROM products WHERE id=".$productDetail['id'];
            }else{
                $query=$query="UPDATE products SET status=".Constants::STATUS_PENDING.", action=".Constants::ACTION_REMOVE." WHERE id=".$productDetail['id'];
            }
            DBUtils::getInstance()->executeQuery($query);
            return true;

        }
        throw new Exceptions('Missing user Id or Product Id',400);
    }

    /**
     * Method to retrieve the detail of given product
     * @param $productId
     * @return array
     * @throws Exceptions
     */
    public function retrieveProduct($productId){
        if($productId){
             return DBUtils::getInstance()->retrieveOne("SELECT id AS ProductId,name AS ProductName,vendor AS Vendor,price AS Price,batch_no AS BatchNo,batch_date AS BatchDate,quantity AS Quantity, CASE action WHEN 1 then 'Pending for Adding' When 2 then 'Pending for update' WHEN 3 THEN 'Pending for Remove' END as Action, CASE status WHEN 1 then 'Approved' When 2 then 'Pending' END as Status FROM products WHERE id=".$productId);
        }
        throw new Exceptions('Product Id Missing',400);
    }
}
