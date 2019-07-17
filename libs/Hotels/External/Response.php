<?php
namespace Hotels\External;

/**
 * Ответ от сервиса API
 * 
 * @author premier13
 *
 */
class Response
{
    /**
     * Код ошибки
     * @var string
     */
    protected $_error;
    /**
     * Рабочая нагрузка
     *
     * @var mixed
     */
    protected $_payload;
    
    
    
    
    public function __construct()
    {
    }
    /**
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return strlen($this->_error);
    }
    /**
     *
     * @return boolean
     */
    public function isError()
    {
        return !strlen($this->_error);
    }
    /**
     *
     * @return mixed
     */
    public function getPayload()
    {
        return $this->_payload;
    }
}
