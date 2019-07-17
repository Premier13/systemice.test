<?php
namespace Hotels;

/**
 * Интерфейс для хралищ отелей и номеров
 * 
 * 
 * @author premier13
 *
 */
interface StorageInterface
{
    /**
     *
     * @param string $id
     * @return Hotel
     */
    public function findHotelById($id);
    
    /**
     *
     * @param string $id
     * @return Room
     */
    public function findHotelRoomById($id);
}
