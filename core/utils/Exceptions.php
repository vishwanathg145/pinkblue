<?php
/**
 * Created by PhpStorm.
 * User: vish
 * Date: 31/1/18
 * Time: 5:03 PM
 */
namespace core\utils;
class Exceptions extends \Exception{
    protected $code;

    /**
     * Exceptions constructor.
     * @param $errorMsg
     * @param int $code
     */
    public function __construct($errorMsg, $code=500)
    {
        parent::__construct($errorMsg, $code);
    }


}