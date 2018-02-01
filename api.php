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

// Add user
$app->post(
    '/users',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {
        try {
            $utilObj = new ResponseUtils();
            $data    = json_decode($utilObj->fetchRequestKey($reqObj, 'data'), 1);
            $userObj = new UserService();
            return $utilObj->genrateResponse($resObj, $userObj->addUser($data), 201);
        } catch (Exceptions $ex) {
            return (new ResponseUtils())->generateResponseForException($resObj, $ex);
        }
    }
);
// Login API
$app->post(
    '/users/login',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {
        try {
            $utilObj  = new ResponseUtils();
            $username = $utilObj->fetchRequestKey($reqObj, 'username');
            $password = $utilObj->fetchRequestKey($reqObj, 'password');
            $userObj  = new UserService();
            return $utilObj->genrateResponse($resObj, $userObj->login($username, $password));
        } catch (Exceptions $ex) {
            return (new ResponseUtils())->generateResponseForException($resObj, $ex);
        }
    }
);
// Role attachment API
$app->post(
    '/users/{id}/roles/attach',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {
        try {
            $utilObj = new ResponseUtils();
            $roleId  = $utilObj->fetchRequestKey($reqObj, 'roleId');
            $userId  = $reqObj->getAttribute('id');
            $userObj = new UserService();
            return $utilObj->genrateResponse($resObj, $userObj->attachRole($userId, $roleId));
        } catch (Exceptions $ex) {
            return (new ResponseUtils())->generateResponseForException($resObj, $ex);
        }
    }
);
// Add Product API
$app->post(
    '/products',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {
        try {
            $utilObj = new ResponseUtils();
            $data    = json_decode($utilObj->fetchRequestKey($reqObj, 'data'), 1);
            $userId  = $utilObj->fetchRequestKey($reqObj, 'userId');
            $invObj  = new \core\services\InventoryService();
            return $utilObj->genrateResponse($resObj, $invObj->addProduct($userId, $data), 201);

        } catch (Exceptions $ex) {
            return (new ResponseUtils())->generateResponseForException($resObj, $ex);
        }
    }
);

// Update product API
$app->put(
    '/products/{id}',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {
        try {
            $utilObj   = new ResponseUtils();
            $data      = json_decode($utilObj->fetchRequestKey($reqObj, 'data'), 1);
            $userId    = $utilObj->fetchRequestKey($reqObj, 'userId');
            $productId = $reqObj->getAttribute('id');
            $invObj    = new \core\services\InventoryService();
            return $utilObj->genrateResponse($resObj, $invObj->updateProduct($userId, $productId, $data), 200);
        } catch (Exceptions $ex) {
            return (new ResponseUtils())->generateResponseForException($resObj, $ex);
        }
    }
);
// Product approve API
$app->post(
    '/products/approve',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {
        try {
            $utilObj   = new ResponseUtils();
            $userId    = $utilObj->fetchRequestKey($reqObj, 'userId');
            $productId = $utilObj->fetchRequestKey($reqObj, 'productId');
            $invObj    = new \core\services\InventoryService();
            return $utilObj->genrateResponse($resObj, $invObj->approveProducts($userId, $productId));
        } catch (Exceptions $ex) {
            return (new ResponseUtils())->generateResponseForException($resObj, $ex);
        }
    }
);
// pending products api
$app->get(
    '/products/pending',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {
        try {
            $utilObj = new ResponseUtils();
            $userId  = $utilObj->fetchRequestKey($reqObj, 'userId');
            $invObj  = new \core\services\InventoryService();
            return $utilObj->genrateResponse($resObj, $invObj->retrievePendingProducts($userId));
        } catch (Exceptions $ex) {
            return (new ResponseUtils())->generateResponseForException($resObj, $ex);
        }
    }
);
// approved products api
$app->get(
    '/products/approved',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {
        try {
            $utilObj = new ResponseUtils();
            $invObj  = new \core\services\InventoryService();
            return $utilObj->genrateResponse($resObj, $invObj->retrieveApprovedProducts());
        } catch (Exceptions $ex) {
            return (new ResponseUtils())->generateResponseForException($resObj, $ex);
        }
    }
);
// Retrieving perticular product
$app->get(
    '/products/{id}',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {
        try {
            $utilObj   = new ResponseUtils();
            $productId = $reqObj->getAttribute('id');
            $invObj    = new \core\services\InventoryService();
            return $utilObj->genrateResponse($resObj, $invObj->retrieveProduct($productId));
        } catch (Exceptions $ex) {
            return (new ResponseUtils())->generateResponseForException($resObj, $ex);
        }
    }
);

// Product delete API
$app->delete(
    '/products/{id}',
    function (Request $reqObj, Response $resObj, $args = []) use ($app) {
        try {
            $utilObj   = new ResponseUtils();
            $userId    = $utilObj->fetchRequestKey($reqObj, 'userId');
            $productId = $reqObj->getAttribute('id');
            $invObj    = new \core\services\InventoryService();
            return $utilObj->genrateResponse($resObj, $invObj->deleteProduct($userId, $productId));

        } catch (Exceptions $ex) {
            return (new ResponseUtils())->generateResponseForException($resObj, $ex);
        }
    }
);

// Run application
$app->run();