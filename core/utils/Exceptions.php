<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 31/1/18
 * Time: 5:03 PM
 */
namespace core\utils;
class Exceptions extends \Exception{
    public function __construct($errorMsg, $code=0)
    {
        parent::__construct($errorMsg, $code);
    }

}