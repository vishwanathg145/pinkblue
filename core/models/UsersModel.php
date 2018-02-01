<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 31/1/18
 * Time: 8:51 PM
 */

namespace core\models;

use core\Constants;
use core\models\BaseModel;
use core\utils\Exceptions;
use core\utils\DBUtils;

class UsersModel extends BaseModel
{
    public $username, $password, $email;

    /**
     * UsersModel constructor.
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
    }

    /**
     * Validate user
     *
     * @throws Exceptions
     */
    public function validateUser()
    {
        $this->validateEmail();
        $this->validatePassword();
        $res = DBUtils::getInstance()
            ->results("SELECT id FROM users WHERE (email = ? OR username=?)", [$this->email, $this->username]);
        if (!empty($res)) {
            throw new Exceptions('User with same email or username exists', 400);
        }

    }

    /**
     * Validate Email format
     *
     * @return bool
     * @throws Exceptions
     */
    public function validateEmail()
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            return true;
        } else {
            throw new Exceptions('Invalid Email Format', 400);
        }
    }

    /**
     * Validate Password
     *
     * @throws Exceptions
     */
    public function validatePassword()
    {
        if (empty($this->password)) {
            throw new Exceptions('Password Missing', 400);
        }
    }

    /**
     * Method to add new user
     *
     * @return mixed
     * @throws Exceptions
     */
    public function addUser()
    {
        $userId = DBUtils::getInstance()->insert("users",
            [
                'username' => $this->username,
                'password' => password_hash($this->password, PASSWORD_DEFAULT),
                'email'    => $this->email,
            ]);
        // add default role
        $this->attachRole($userId, Constants::ROLE_DEPARTMENT_MANAGER);
        return $userId;
    }

    /**
     * Method to attach role to user
     *
     * @param $userId
     * @param $roleId
     * @return mixed
     * @throws Exceptions
     */
    public function attachRole($userId, $roleId)
    {
        if ($userId && $roleId) {
            DBUtils::getInstance()->insert("user_roles",
                [
                    'user_id' => $userId,
                    'role_id' => $roleId,
                ]);
            return $userId;
        }
        throw new Exceptions('Role Id or user Id missing', 400);
    }
}