<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 31/1/18
 * Time: 4:53 PM
 */
require_once(__DIR__ . "/vendor/autoload.php");

use Slim\App;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use core\Constants;
use core\utils\ResponseUtils;
use core\utils\DBUtils;
use core\utils\Exceptions;

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);


