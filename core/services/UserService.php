<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 31/1/18
 * Time: 10:58 PM
 */

namespace core\services;

use core\models\UsersModel;
use core\Constants;
use core\utils\DBUtils;
use core\utils\Exceptions;

class UserService
{
    /**
     * Method to add new user
     *
     * @param $data
     * @return mixed
     * @throws Exceptions
     */
    public function addUser($data)
    {
        if (!empty($data)) {
            $userModel = new UsersModel($data);
            $userModel->validateUser();
            return $userModel->addUser();
        }
        throw new Exceptions('Data Missing');
    }

    /**
     * Method to retrieve the roles of user
     *
     * @param $userId
     * @return mixed
     * @throws Exceptions
     */
    public function getUserRoles($userId)
    {
        if (!is_null($userId)) {
            $res = DBUtils::getInstance()->results("SELECT role_id FROM user_roles WHERE user_id=$userId");
            if (empty($res)) {
                throw new Exceptions('No Roles found for user');
            }
            return $res;
        }
        throw new Exceptions('User Id missing');
    }

    /**
     * Method to login
     *
     * @param $userName
     * @param $password
     * @return mixed
     * @throws Exceptions
     */
    public function login($userName, $password)
    {
        if ($userName && $password) {
            $res = DBUtils::getInstance()
                ->retrieveOne("SELECT id,username,password FROM users WHERE username=?", [$userName]);
            if (empty($res)) {
                throw new Exceptions('Invalid username or password');
            }

            if (!password_verify($password, $res[Constants::PASSWORD])) {
                throw new Exceptions('Invalid username or password');
            }
            return $res['id'];
        }
        throw new Exceptions('Data Missing');
    }

    /**
     * Method to attach the role to user
     *
     * @param $userId
     * @param $roleId
     * @return mixed
     * @throws Exceptions
     */
    public function attachRole($userId, $roleId)
    {
        if ($userId && $roleId) {
            (new UsersModel())->attachRole($userId, $roleId);
            return $userId;
        }
        throw new Exceptions('User Id or Role Id is missing', 400);
    }
}