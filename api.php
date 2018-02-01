<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 31/1/18
 * Time: 4:50 PM
 */

require(__DIR__ . '/common.php');

use core\Constants;
use core\utils\ResponseUtils;
use core\utils\Exceptions;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\services\UserService;

$app->post(
    '/users',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {

            $utilObj=new ResponseUtils();
            $data=json_decode($utilObj->fetchRequestKey($reqObj,'data'),1);
            $userObj=new UserService();
        return $utilObj->genrateResponse($resObj,$userObj->addUser($data));
    }
);

$app->post(
    '/users/login',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {

        $utilObj=new ResponseUtils();
        $username=$utilObj->fetchRequestKey($reqObj,'username');
        $password=$utilObj->fetchRequestKey($reqObj,'password');
        $userObj=new UserService();
        return $utilObj->genrateResponse($resObj,$userObj->login($username,$password));

    }
);


$app->post(
    '/products',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {

        $utilObj=new ResponseUtils();
        $data=json_decode($utilObj->fetchRequestKey($reqObj,'data'),1);
        $userId=$utilObj->fetchRequestKey($reqObj,'userId');
        $invObj=new \core\services\InventoryService();
        return $utilObj->genrateResponse($resObj,$invObj->addProduct($userId,$data));


    }
);


// Run application
$app->run();