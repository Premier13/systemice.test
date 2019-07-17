<?php
namespace Mock\Hotels\Requests\Journals\Prices;

/**
 * Мок-запись в журнал запросв к внешним API
 * 
 * @author premier13
 *
 */
class Record
{
    /**
     * Дата запроса (timestamp)
     *
     *
     * @var int
     */
    public $timestamp = null;
    /**
     * Идентификатор отеля
     *
     * @var string
     */
    public $hotelId   = null;
    /**
     * Идентификатор номера
     *
     * @var string
     */
    public $roomId   = null;
    /**
     * Дата заезда
     *
     * @var \DateTime
     */
    public $dateStart = null;
    /**
     * Дата выеда
     *
     * @var \DateTime
     */
    public $dateFrom  = null;
    /**
     * Лучшая цена
     *
     *
     * @var NULL|double
     */
    public $bestPrice = null;
    /**
     * Массив цен от всех сервисов
     *
     * @var array
     */
    public $prices    = array();
    /**
     * Массив ошибок при их наличии (опционально)
     *
     * @var array
     */
    public $errors    = array();
    
    
    
    /**
     *
     */
    public function __construct()
    {
    }
    
    
    
    
    /**
     * Сохранение записи в хранилище журнала
     *
     *
     * @return boolean
     */
    public function save()
    {
        return true;
    }
}
