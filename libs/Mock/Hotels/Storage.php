<?php
namespace Mock\Hotels;

/**
 * Мок-хранилище для отелей и номеров
 * 
 * 
 * @author premier13
 *
 */
class Storage implements \Hotels\StorageInterface
{
    /**
     *
     * @var array
     */
    protected $_data = array(
        "hotels" => array(),
        "rooms"  => array(),
    );
    
    protected $_data_path = null;
    
    public function __construct($options = array())
    {
        if (
            isset($options["path"])
            and
            file_exists($options["path"])
            and
            is_dir($options["path"])
        ) {
            $this->_data["hotels"] = json_decode(file_get_contents($options["path"]."/hotels.json"), true);
            $this->_data["rooms"]  = json_decode(file_get_contents($options["path"]."/rooms.json"), true);
        } else {
            echo "<pre>";
            echo print_r($options, true);
            echo "</pre>";
        }
    }
    
    
    /**
     *
     * @param string $id
     * @return array
     */
    public function findHotelById($id)
    {
        if (isset($this->_data["hotels"][$id])) {
            return $this->_data["hotels"][$id];
        } else {
            return null;
        }
    }
    
    /**
     *
     * @param string $id
     * @return array
     */
    public function findHotelRoomById($id)
    {
        if (isset($this->_data["rooms"][$id])) {
            return $this->_data["rooms"][$id];
        } else {
            return null;
        }
    }
}
