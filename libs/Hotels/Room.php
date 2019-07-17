<?php
namespace Hotels;

/**
 * Номер
 * 
 * @package Hotels
 * @author premier13
 *
 */
class Room
{
    public $id      = null;
    public $name     = null;
    
    public $hotelId  = null;
    
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
            $this->hotelId  = $data["hotelId"];
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
     * @return Hotel
     */
    public function getHotel()
    {
        return Service::getInstance()->getHotelById($this->hotelId);
    }
    
    
    /**
     *
     * @param \DateTime $from
     * @param \DateTime $to
     * @return array[]
     */
    public function getBookingPrices($from, $to)
    {
        return  \Hotels\External\Service::getInstance()
            ->getBookingPricesForRoom($this, $from, $to)
        ;
    }
    
    /**
     *
     * @param \DateTime $from
     * @param \DateTime $to
     * @return array[]
     */
    public function findBestBookingPrice($from, $to)
    {
        return  \Hotels\External\Service::getInstance()
            ->findBestBookingPriceForRoom($this, $from, $to)
        ;
    }
    
    
    
    /**
     * Сомнительная полезность, но требования Т.З. можно растрактовать как требующее и его (данный метод)
     *
     *
     * @param \DateTime $from
     * @param \DateTime $to
     * @return array[]
     */
    public function getFirstBookingPrice($from, $to)
    {
        return  \Hotels\External\Service::getInstance()
            ->getFirstBookingPriceForRoom($this, $from, $to)
        ;
    }
}
