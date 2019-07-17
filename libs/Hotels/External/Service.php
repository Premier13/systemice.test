<?php
namespace Hotels\External;

/**
 * Служба для упрощённого доступа к внешним сервисам API и централизованного выполнения методов
 * 
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
     * Связь идентификаторов служб и соответствующих клиентов API
     * Подлежит перемещению либо в конфиг, либо в хранилище (БД)
     *
     * @var array
     */
    protected $_serviceIdToClass = array(
        "domik"   => Domik\Client::class,
        "strana"  => Strana\Client::class,
    );
    /**
     *
     * @var ClientInterface[]
     */
    protected $_clients = array();
    /**
     * Приоритет служб на случай того, если не ищем лучшую цены
     * (список идентификаторов)
     *
     * @var string[]
     */
    protected $_priority = array(
        "domik",
        "strana",
    );
    
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
     * @param string $id
     * @throws \Exception
     * @return ClientInterface
     */
    public function &getExternalClientById($id)
    {
        if (!isset($this->_clients[$id])) {
            if (!isset($this->_serviceIdToClass[$id])) {
                throw new \Exception("Unknown external service id! [{$id}]");
            }
            $this->_clients[$id] = new $this->_serviceIdToClass[$id]();
        }
        
        return $this->_clients[$id];
    }
    
    
    /**
     * Получить варианты стоимости проживания в указаном номере
     *
     * Если идетификаторы внешних служб не указаны - будут проверены все доступные
     *
     *
     * @param \Hotels\Room  $room       Номер
     * @param string        $from       Дата заезда
     * @param string        $to         Дата выезда
     * @param array         $services   Массив идентификаторов внешних служб
     * @return array
     */
    public function getBookingPricesForRoom($room, $from, $to, $services = [])
    {
        $result = array();
        //
        if (!$services) {
            $services = $this->_priority;
        }
        
        foreach ($services as $sid) {
            $result[$sid] = $this->getExternalClientById($sid)
                ->getPriceForRoom($room, $from, $to)
            ;
        }
        //
        $journalRecord = new \Mock\Hotels\Requests\Journals\Prices\Record();
        $journalRecord->roomId    = $room->getId();
        $journalRecord->hotelId   = $room->getHotel()->getId();
        $journalRecord->prices    = $result;
        $journalRecord->dateFrom  = $from;
        $journalRecord->dateTo    = $to;
        $journalRecord->save();
        //
        return $result;
    }
    
    
    
    /**
     * Получить лучшую стоимость для проживания в указаном номере
     *
     * Если идетификаторы внешних служб не указаны - будут проверены все доступные
     *
     * @param \Hotels\Room  $room       Номер
     * @param string        $from       Дата заезда
     * @param string        $to         Дата выезда
     * @param array         $services   Массив идентификаторов внешних служб
     * @return array
     */
    public function findBestBookingPriceForRoom($room, $from, $to, $services = [])
    {
        $result  = PHP_INT_MAX;
        //$service = NULL;
        //
        $prices = $this->getBookingPricesForRoom($room, $from, $to);
        
        foreach ($prices as $value) {
            if ($value and $result > $value) {
                $result  = $value;
            }
        }
        //
        if ($result == PHP_INT_MAX) {
            $result = null;
        }
        //
        $journalRecord = new \Mock\Hotels\Requests\Journals\Prices\Record();
        $journalRecord->roomId    = $room->getId();
        $journalRecord->hotelId   = $room->getHotel()->getId();
        $journalRecord->bestPrice = $result;
        $journalRecord->dateFrom  = $from;
        $journalRecord->dateTo    = $to;
        $journalRecord->save();
        
        //
        return $result;
    }
    
    
    /**
     * Получить стоимость проживания в указаном номере  по первому же доступному сервису
     *
     * Если идетификаторы внешних служб не указаны - будут проверены все доступные
     *
     *
     * @param \Hotels\Room  $room       Номер
     * @param string        $from       Дата заезда
     * @param string        $to         Дата выезда
     * @param array         $services   Массив идентификаторов внешних служб
     * @return array
     */
    public function getFirstBookingPriceForRoom($room, $from, $to, $services = [])
    {
        $result = null;
        //
        if (!$services) {
            $services = $this->_priority;
        }
        
        foreach ($services as $sid) {
            $result = $this->getExternalClientById($sid)
                ->getPriceForRoom($room, $from, $to)
            ;
            if ($result) {
                break;
            }
        }
        //
        $journalRecord = new \Mock\Hotels\Requests\Journals\Prices\Record();
        $journalRecord->roomId    = $room->getId();
        $journalRecord->hotelId   = $room->getHotel()->getId();
        $journalRecord->bestPrice = $result;
        $journalRecord->dateFrom  = $from;
        $journalRecord->dateTo    = $to;
        $journalRecord->save();
        
        //
        return $result;
    }
}
