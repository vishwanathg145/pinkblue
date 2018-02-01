<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 31/1/18
 * Time: 4:58 PM
 */
namespace core\utils;
use \Psr\Http\Message\ResponseInterface as Response;
class ResponseUtils{

    public function decodeData($data){
        if(!empty($data)){
            return json_decode($data,1);
        }
        throw new Exceptions("No data found for decode");
    }
    public function encodeData($data){
            return json_encode($data);
    }

    public function genrateResponse(Response $resObj, $data)
    {
        $resObj->getBody()->write($this->encodeData($data));
        // Returning with Default Http Status 200 Ok
        return $resObj->withHeader('Content-type', 'application/json')->withStatus(200);
    }

    public function fetchRequestKey($req, $key)
    {
        return $req->getParam($key);
    }


}