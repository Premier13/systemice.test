<?php
namespace Hotels;

/**
 * Дополнительный класс (служба) для упрощения отладки
 *      (что бы не иметь дело напрямую с \Mock\Storage)
 *
 * 
 * @package Hotels
 * @author premier13
 *
 */
class Service
{
    /**
     * @var self
     */
    private static $instance;
    
    /**
     *
     * @var StorageInterface
     */
    protected $_storage = null;
    /**
     *
     * @var array
     */
    protected $_storage_configuration = array();
    
    
    /**
     *
     * @var Hotel[]
     */
    protected $_hotels = array();
    /**
     *
     * @var Room[]
     */
    protected $_rooms = array();
    
    
    
    /**
     *
     */
    private function __construct()
    {
    }
    /**
     *
     */
    private function __clone()
    {
    }
    /**
     *
     */
    private function __wakeup()
    {
    }
    
    
    /**
     *
     * @return Service
     */
    public static function getInstance(): Service
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }
    
    /**
     *
     * @return \Mock\Hotels\Storage
     */
    public function &getStorage()
    {
        if (!$this->_storage) {
            $this->_storage = new \Mock\Hotels\Storage($this->_storage_configuration);
        }
        
        return $this->_storage;
    }
    
    
    public function &configureStorage($options=array())
    {
        $this->_storage_configuration = $options;
        
        return $this;
    }
    
    
    
    /**
     *
     * @param string $id
     * @return Hotel|NULL
     */
    public function &getHotelById($id)
    {
        if (!isset($this->_hotels[$id])) {
            $record = $this->getStorage()->findHotelById($id);
            
            $this->_hotels[$id] = new Hotel($record);
        }
        
        return $this->_hotels[$id];
    }
    /**
     *
     * @param string $id
     * @return Room|NULL
     */
    public function &getRoomById($id)
    {
        if (!isset($this->_rooms[$id])) {
            $record = $this->getStorage()->findHotelRoomById($id);
            
            $this->_rooms[$id] = new Room($record);
        }
        
        return $this->_rooms[$id];
    }
}
