<?php
namespace Mock\Domik;

/**
 * Предполагается, что используется сторонняя библиотека (т.е. от самого сервиса)
 *
 *
 * @author premier13
 *
 */
class Client
{
    /**
     * Массив кодов и сообщений ошибок (для эмуляции)
     *
     * @var array
     */
    protected $_errors = array(
        "403" => "Ошибка доступа!",
        "404" => "Номер или отель не найден!",
        "404" => "Номер или отель не найден!",
        "500" => "Внутренняя ошибка сервиса!",
        "503" => "Сервис перегружен!",
        
        "hotel_not_found"     => "Отель не найден",
        "room_not_found"      => "Номер не найден",
        "room_not_available"  => "Номер не доступен",
        
        "invalid_date_range"  => "Некорректный диапазон дат!",
    );
    
    
    protected $_data = array(
        "1" => array(
            "1" => array(
                "PerNight" => "120.00",
                "occupied" => false,
            ),
            "2" => array(
                "PerNight" => "299.00",
                "occupied" => false,
            ),
            "3" => array(
                "PerNight" => "899.00",
                "occupied" => true,
            ),
            "4" => array(
                "PerNight" => "3899.00",
                "occupied" => true,
            ),
        ),
        "2" => array(
            "5" => array(
                "PerNight" => "20.00",
                "occupied" => false,
            ),
            "6" => array(
                "PerNight" => "59.00",
                "occupied" => false,
            ),
            "7" => array(
                "PerNight" => "89.00",
                "occupied" => false,
            ),
            "8" => array(
                "PerNight" => "150.00",
                "occupied" => true,
            ),
            "9" => array(
                "PerNight" => "320.00",
                "occupied" => true,
            ),
        ),
        "3" => array(
            "10" => array(
                "PerNight" => "420.00",
                "occupied" => false,
            ),
            "11" => array(
                "PerNight" => "199.00",
                "occupied" => true,
            ),
            "12" => array(
                "PerNight" => "1999.00",
                "occupied" => false,
            ),
            "13" => array(
                "PerNight" => "2599.00",
                "occupied" => false,
            ),
            "14" => array(
                "PerNight" => "5899.00",
                "occupied" => true,
            ),
        ),
        
    );
    
    
    protected function _randomErrorId()
    {
        return array_rand($this->_errors, 1);
    }
    
    protected function _getErrorMessageById($id)
    {
        return $this->_errors[$id] ?? null;
    }
    
    /**
     * Получить <b>стоимость</b> номера в сутки в указанном отеле
     *
     * error (string) - в случае ошибки
     *
     *
     * @param string $baseUrl
     * @param int    $roomId      id категории номера
     * @param string $hotel       id отеля в системе Домик
     * @param string $dateStart   дата заезда формата Y-m-d
     * @param string $dateEnd     дата выезда формата Y-m-d
     * @return \stdClass          {"price" float,"error" string}
     */
    public function getRoomPrice($baseUrl, $roomId, $hotel, $dateStart, $dateEnd)
    {
        $result = new \stdClass();
        //
        $result->price = "";
        $result->error = "";
        //
        if (mt_rand(0, 10) >= 9) {
            $result->error = $this->_randomErrorId();
        } else {
            $from = \DateTime::createFromFormat("Y-m-d", $dateStart);
            $to   = \DateTime::createFromFormat("Y-m-d", $dateEnd);
            
            $ndays  = $from->diff($to)->format("%r%d");
            
            if (!$ndays or $ndays < 0) {
                //throw new \Exception("Invalid booking date range!");
                
                $result->error = $this->_getErrorStructById("invalid_date_range");
            } elseif (!isset($this->_data[$hotel])) {
                $result->error = "hotel_not_found";
            } elseif (!isset($this->_data[$hotel][$roomId])) {
                $result->error = "room_not_found";
            } elseif ($this->_data[$hotel][$roomId]["occupied"]) {
                $result->error = "room_not_available";
            } else {
                $result->price = $this->_data[$hotel][$roomId]["PerNight"];
            }
        }
        
        
        //
        return $result;
    }
}
