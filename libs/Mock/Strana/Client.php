<?php
namespace Mock\Strana;

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
        "room_is_busy"        => "Номер занят",
        
        "invalid_date_range"  => "Некорректный диапазон дат!",
    );
    
    protected $_data = array(
        "s1" => array(
            "economy" => array(
                "PerNight" => "120.00",
                "occupied" => true,
            ),
            "standard" => array(
                "PerNight" => "99.00",
                "occupied" => false,
            ),
            "business" => array(
                "PerNight" => "899.00",
                "occupied" => false,
            ),
            "deluxe" => array(
                "PerNight" => "3899.00",
                "occupied" => true,
            ),
        ),
        "s2" => array(
            "dormitory12" => array(
                "PerNight" => "20.00",
                "occupied" => false,
            ),
            "dormitory8" => array(
                "PerNight" => "59.00",
                "occupied" => false,
            ),
            "dormitory4" => array(
                "PerNight" => "89.00",
                "occupied" => false,
            ),
            "1bedroom" => array(
                "PerNight" => "150.00",
                "occupied" => true,
            ),
            "2bedroom" => array(
                "PerNight" => "320.00",
                "occupied" => true,
            ),
        ),
        "s3" => array(
            "standard" => array(
                "PerNight" => "420.00",
                "occupied" => false,
            ),
            "economy" => array(
                "PerNight" => "199.00",
                "occupied" => false,
            ),
            "business" => array(
                "PerNight" => "1899.00",
                "occupied" => false,
            ),
            "deluxe" => array(
                "PerNight" => "2899.00",
                "occupied" => false,
            ),
            "president" => array(
                "PerNight" => "5899.00",
                "occupied" => true,
            ),
        ),
        "s4" => array(
            "business" => array(
                "PerNight" => "2999.00",
                "occupied" => true,
            ),
            "deluxe" => array(
                "PerNight" => "3999.00",
                "occupied" => false,
            ),
            "president" => array(
                "PerNight" => "9999.00",
                "occupied" => true,
            ),
        ),
    );
    
    
    
    protected function _randomErrorId()
    {
        return array_rand($this->_errors);
    }
    
    
    
    protected function _getErrorMessageById($id)
    {
        return $this->_errors[$id] ?? null;
    }
    
    
    protected function _getErrorStructById($id)
    {
        return [
            "code"    => $id,
            "message" => $this->_errors[$id] ?? null,
        ];
    }
    
    /**
     * Получить <b>итоговую стоимость</b> номера в указанном отеле за весь период проживания
     *
     * error (array) - в случае ошибки
     *
     * @param string $baseUrl
     * @param int    $roomName    название номера
     * @param string $hotel       id отеля в системе Страна
     * @param string $dateStart   дата заезда формата d.m.Y
     * @param string $dateEnd     дата выезда формата d.m.Y
     * @return \stdClass          {"price" result_price,"error" string}
     */
    public function getLivingPriceRoomPrice($baseUrl, $roomName, $hotel, $dateStart, $dateEnd)
    {
        $result = new \stdClass();
        //
        $result->price = "";
        $result->error = "";
        //
        if (mt_rand(0, 10) >= 9) {
            $result->error = $this->_getErrorStructById($this->_randomErrorId());
        } else {
            $from = \DateTime::createFromFormat("d.m.Y", $dateStart);
            $to   = \DateTime::createFromFormat("d.m.Y", $dateEnd);
            
            $ndays  = $from->diff($to)->format("%r%d");
            
            if (!$ndays or $ndays < 0) {
                //throw new \Exception("Invalid booking date range!");
                
                $result->error = $this->_getErrorStructById("invalid_date_range");
            }
            
            if (!isset($this->_data[$hotel])) {
                $result->error = $this->_getErrorStructById("hotel_not_found");
            } elseif (!isset($this->_data[$hotel][$roomName])) {
                $result->error = $this->_getErrorStructById("room_not_found");
            } elseif ($this->_data[$hotel][$roomName]["occupied"]) {
                $result->error = $this->_getErrorStructById("room_is_busy");
            } else {
                $result->price = $this->_data[$hotel][$roomName]["PerNight"] * $ndays;
            }
        }
        
        
        //
        return $result;
    }
}
