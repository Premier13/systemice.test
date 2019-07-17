<?php
namespace Hotels;

/**
 * Отель
 * 
 * @package Hotels
 * @author premier13
 *
 */
class Hotel
{
    public $id       = null;
    public $name     = null;
    
    public $external = array();
    
    /**
     *
     * @param array $data
     */
    public function __construct($data)
    {
        if ($data) {
            $this->id       = $data["id"];
            $this->name     = $data["name"];
            $this->external = $data["external"];
        }
    }
    /**
     *
     * @return boolean
     */
    public function isLoaded()
    {
        return strlen($this->id);
    }
    
    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     *
     * @param string $service_id
     * @return string
     */
    public function getExternalId($service_id=null)
    {
        if (isset($this->external[$service_id])) {
            return $this->external[$service_id];
        }
        
        return null;
    }
    /**
     *
     * @return Room[]
     */
    public function getRooms()
    {
        return array();
    }
}
