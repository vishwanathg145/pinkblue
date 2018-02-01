<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 1/2/18
 * Time: 12:49 AM
 */

namespace core\models;

use core\Constants;
use core\models\BaseModel;
use core\services\UserService;
use core\utils\Exceptions;
use core\utils\DBUtils;

class InventoryModel extends BaseModel
{
    public $name, $vendor, $price, $batch_no, $batch_date, $quantity, $status;

    /**
     * InventoryModel constructor.
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
    }

    /**
     * Basic validation of product
     *
     * @throws Exceptions
     */
    public function validateProduct()
    {
        if (is_null($this->name) || is_null($this->vendor) || is_null($this->price) || is_null($this->quantity)) {
            throw new Exceptions('Product details missing', 400);
        }

    }

    /**
     * Method to add the new product
     *
     * @param $userId
     * @return mixed
     * @throws Exceptions
     */
    public function addProduct($userId)
    {
        $this->validateProduct();
        $roles  = (new UserService())->getUserRoles($userId);
        $roles  = array_column($roles, 'role_id');
        $status = Constants::STATUS_PENDING;
        $action = Constants::ACTION_ADD;
        if (in_array(Constants::ROLE_STORE_MANAGER, $roles)) {
            $status = Constants::STATUS_APPROVED;
            $action = Constants::ACTION_OK;
        }
        $productId = DBUtils::getInstance()->insert("products",
            [
                'name'       => $this->name,
                'vendor'     => $this->vendor,
                'price'      => $this->price,
                'batch_no'   => $this->batch_no,
                'batch_date' => $this->batch_date,
                'quantity'   => $this->quantity,
                'status'     => $status,
                'action'     => $action
            ]);
        return $productId;
    }

    /**
     * Method to update the product
     *
     * @param $userId
     * @param $productId
     * @return mixed
     * @throws Exceptions
     */
    public function updateProduct($userId, $productId)
    {
        $this->validateProduct();
        $roles  = (new UserService())->getUserRoles($userId);
        $roles  = array_column($roles, 'role_id');
        $status = Constants::STATUS_PENDING;
        $action = Constants::ACTION_UPDATE;
        if (in_array(Constants::ROLE_STORE_MANAGER, $roles)) {
            $status = Constants::STATUS_APPROVED;
            $action = Constants::ACTION_OK;
        }
        $query
            = "UPDATE products SET name='$this->name',vendor='$this->vendor',price=$this->price,batch_no=$this->batch_no, batch_date='$this->batch_date',quantity=$this->quantity,status=$status,action=$action WHERE id=$productId";
        DBUtils::getInstance()->executeQuery($query);
        return $productId;
    }
}