<?php
namespace Hotels\External\Domik;

/**
 * Клиент к сервису Домик
 * @author premier13
 *
 */
class Client implements \Hotels\External\ClientInterface
{
    /**
     *
     * @var \Mock\Domik\Client
     */
    protected $_adaptee;
    /**
     * Формат даты используемый в вызовах API
     *
     * @var string
     */
    const DATE_FORMAT = "Y-m-d";
    /**
     * Внутренний строковый индентификатор службы
     * @var string
     */
    const INTERNAL_SERVICE_ID  = "domik";
    
    
    /**
     * Временный кеш (для сессии)
     * Что бы не усложнять тут тест. работу ещё и использованием отдельного кеширования (memcached, etc,)
     * Используется упрощённый вариант (но, только для этих целей!)
     *
     *
     * @var array
     */
    protected $_tmpCache = array();
    
    
    
    public function __construct()
    {
        $this->_adaptee = new \Mock\Domik\Client();
    }
    
    
    
    
    /**
     *
     * @param \Hotels\Room $room
     * @param \DateTime    $from
     * @param \DateTime    $to
     *
     * @return double
     */
    public function getPriceForRoom($room, $from, $to)
    {
        $cache_key_id = md5(serialize([
            self::INTERNAL_SERVICE_ID,
            $room->getId(),
            $from->getTimestamp(),
            $to->getTimestamp(),
        ]));
        
        if (key_exists($cache_key_id, $this->_tmpCache)) {
            return $this->_tmpCache[$cache_key_id];
        }
        
        
        
        $result = null;
        $ndays  = $from->diff($to)->format("%r%d");
        
        if (!$ndays or $ndays < 0) {
            throw new \Exception("Invalid booking date range!");
        }
        
        $response = $this->_adaptee->getRoomPrice(
            "/api/rooms/",
            $room->getExternalId(self::INTERNAL_SERVICE_ID),
            $room->getHotel()->getExternalId(self::INTERNAL_SERVICE_ID),
            $from->format(self::DATE_FORMAT),
            $to->format(self::DATE_FORMAT)
        );
        
        if (!strlen($response->error)) {
            $result = $response->price;
        } else {
        }
        
        $this->_tmpCache[$cache_key_id] = $result;
        
        return $result;
    }
    
    
    /**
     *
     * @param \Hotels\Hotel $hotel
     * @param \DateTime     $from
     * @param \DateTime     $to
     *
     * @return array
     */
    public function getPricesForHotel($hotel, $from, $to)
    {
        $result = array();
        $ndays  = $to->diff($from)->days;
        
        if (!$ndays or $ndays < 0) {
            throw new \Exception("Invalid booking date range!");
        }
        
        
        $rooms = $hotel->getRooms();
        
        foreach ($rooms as $room) {
            $result[$room->getId()] = null;
            
            $response = $this->_adaptee->getRoomPrice(
                "/api/rooms/",
                $room->getExternalId(self::INTERNAL_SERVICE_ID),
                $hotel->getExternalId(self::INTERNAL_SERVICE_ID),
                $from->format(self::DATE_FORMAT),
                $to->format(self::DATE_FORMAT)
            );
            
            if (!strlen($response->error)) {
                $result[$room->getId()] = $response->price;
            }
        }
        
        return $result;
    }
}
