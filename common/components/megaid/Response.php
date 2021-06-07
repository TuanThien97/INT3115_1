<?php
namespace common\components\megaid;

use Yii;

/**
 * MegaID Response class
 */
class Response {
    /**
     * properties
     */
    public $isOk = false;
    private $error, $code, $status, $message, $data;

    /**
     * set OK status
     * @param bool $status
     */
    public function setIsOk($status)
    {
        $this->isOk = $status;
    }

    /**
     * set error
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * get error
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * set data
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * get error
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * set error code
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * get error code
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * set status
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * get status
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * set message
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * get message
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

}