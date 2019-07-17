<?php
namespace Hotels\External;

/**
 * Интерфейс для унифицированных API клиентов к внешним сервисам
 * 
 * @author premier13
 *
 */
interface ClientInterface
{
    /**
     * Получить цены на все номера в указанном отеле
     *
     * @param \Hotels\Hotel $hotel
     * @param \DateTime     $from
     * @param \DateTime     $to
     * @return double
     */
    public function getPricesForHotel($hotel, $from, $to);
    
    /**
     * Получить цену на указанный номер
     *
     * @param \Hotels\Room $room
     * @param \DateTime    $from
     * @param \DateTime    $to
     * @return array
     */
    public function getPriceForRoom($room, $from, $to);
}
