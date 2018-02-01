<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 31/1/18
 * Time: 4:58 PM
 */
namespace core\utils;
use \Psr\Http\Message\ResponseInterface as Response;
use core\utils\Exceptions;
class ResponseUtils{
/*
 * Method to decode the data
 */
    public function decodeData($data){
        if(!empty($data)){
            return json_decode($data,1);
        }
        throw new Exceptions("No data found for decode",400);
    }

    /**
     * Method to encode the data
     *
     * @param $data
     * @return string
     */
    public function encodeData($data){
            return json_encode($data);
    }

    /**
     * Method to construct the response object with Json encoded
     *
     * @param Response $resObj
     * @param $data
     * @param int $status
     * @return static
     */
    public function genrateResponse(Response $resObj, $data,$status=200)
    {
        $resObj->getBody()->write($this->encodeData($data));

        return $resObj->withHeader('Content-type', 'application/json')->withStatus($status);
    }

    /**
     * Method to fetch the given key from request object
     *
     * @param $req
     * @param $key
     * @return mixed
     */
    public function fetchRequestKey($req, $key)
    {
        return $req->getParam($key);
    }


    /**
     * Method to generate the response for exceptions
     * @param Response $resObj
     * @param \core\utils\Exceptions $ex
     * @return ResponseUtils
     */
    public function generateResponseForException(Response $resObj,Exceptions $ex){

        return $this->genrateResponse($resObj,$ex->getMessage(),$ex->getCode());
    }


}