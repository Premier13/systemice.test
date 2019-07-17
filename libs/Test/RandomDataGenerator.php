<?php
namespace Test;

/**
 *
 *
 * @package   Debug
 * @author    Premier13 <alex@yandexolog.ru>
 *
 */
class RandomDataGenerator
{
    public static $HotelNames = array(
        "Prefixes" => [
            "",
            "Art",
            "",
            "",
            "",
        ],
        "Brands" => [
            "",
            "Lotte",
            "Balchug",
            "Hartwell",
            "Radisson",
            
            "Hilton",
            "Marriott",
            "Mercure",
            "Sheraton",
            
            "Crowne",
        ],
        "Names" => [
            "Viewpoint",
            "Plaza",
            "Collection",
            "Residence",
            "Luxury",
            "President",
        ],
        "Afixes" => [
            "",
        ],
        "Cities" => [
            "NY",
            "Moscow",
            "Rome",
            "Berlin",
            "Tokyo",
        ],
        
        "Adjective" => [
            "Beautiful",
            "Awesome",
            "Luxury",
            "Elite",
            "Incredible",
            "Unique",
            "Famous",
            "Excellent",
            "Superb",
            "Superior",
            "Beauteous",
            "Startling",
            "Amazing",
        ],
        "Streets" => [
            "Central square",
            "Central street",
            "Main square",
            "Main street",
        ],
    );
    
    public static $HotelRooms = array(
        "Names" => array(
            "Standard",
            "Deluxe",
            "Luxury",
            "Premium",
            "President",
            "Superior",
        ),
    );
    
    public static $HotelServices = array(
        "Names" => array(
            "Swimming pool,",
            "Fitness center",
            "SPA",
        ),
    );
    
    
    public static $GeoCoords = [
        "NY"        => ["lat" => "40.71427" ,"lon" => "-74.00597",],
        "Moscow"    => ["lat" => "55.75222" ,"lon" => "37.61556",],
        "Rome"      => ["lat" => "41.89193" ,"lon" => "12.51133",],
        "Berlin"    => ["lat" => "52.52437" ,"lon" => "13.41053",],
        "Tokyo"     => ["lat" => "35.6895"  ,"lon" => "139.69171",],
    ];
    
    public static $PhoneNos = [
        "NY"        => "+1 (646) ###-##-##",
        "Moscow"    => "+7 (495) ###-##-##",
        "Rome"      => "+39 (066) ###-##-##",
        "Berlin"    => "+49 (30) ####-####",
        "Tokyo"     => "+81 (3) #-####-####",
    ];
    
    /**
     *
     * @return string
     */
    public static function generateRandomPerson()
    {
        return sprintf(
            "%s %s",
            self::$HumanM['FirstNames'][mt_rand(0, count(self::$HumanM['FirstNames'])-1)],
            self::$HumanM['LastNames'][mt_rand(0, count(self::$HumanM['LastNames'])-1)]
        );
    }
    
    /**
     *
     * @return string
     */
    public static function generateRandomMerchandise()
    {
        return sprintf(
            "%s %s \"%s\"",
            \mb_strtolower(self::$Goods['Type'][mt_rand(0, count(self::$Goods['Type'])-1)], "utf-8"),
            self::$Goods['Brands'][mt_rand(0, count(self::$Goods['Brands'])-1)],
            self::$Goods['Model'][mt_rand(0, count(self::$Goods['Model'])-1)]
        );
    }
    
    
    /**
     *
     * @return string
     */
    public static function generateRandomHotelName()
    {
        return trim(preg_replace("/\s\s/uis", " ", sprintf(
            "%s %s %s %s",
            self::$HotelNames['Prefixes'][mt_rand(0, count(self::$HotelNames['Prefixes'])-1)],
            self::$HotelNames['Brands'][mt_rand(0, count(self::$HotelNames['Brands'])-1)],
            self::$HotelNames['Names'][mt_rand(0, count(self::$HotelNames['Names'])-1)],
            self::$HotelNames['Afixes'][mt_rand(0, count(self::$HotelNames['Afixes'])-1)],
            self::$HotelNames['Cities'][mt_rand(0, count(self::$HotelNames['Cities'])-1)]
            
        )));
    }
    
    
    
    
    public static function generateRandomHotelData($rooms_range=[3,20])
    {
        $result = array(
            "Name"        => null,
            "Description" => null,
            "Address"     => null,
            
            "RoomsCount"  => null,
            
            "ArbitaryFields" => array(
                
            ),
            
            "City"        => null,
        );
        
        
        $result["City"]        = self::$HotelNames['Cities'][mt_rand(0, count(self::$HotelNames['Cities'])-1)];
        $result["RoomsCount"]  = mt_rand($rooms_range[0], $rooms_range[1]);
        
        
        $result["Name"] = trim(preg_replace("/\s\s/uis", " ", sprintf(
            "%s %s %s %s %s",
            self::$HotelNames['Prefixes'][mt_rand(0, count(self::$HotelNames['Prefixes'])-1)],
            self::$HotelNames['Brands'][mt_rand(0, count(self::$HotelNames['Brands'])-1)],
            self::$HotelNames['Names'][mt_rand(0, count(self::$HotelNames['Names'])-1)],
            self::$HotelNames['Afixes'][mt_rand(0, count(self::$HotelNames['Afixes'])-1)],
            $result["City"]
            
        )));
        
        
        $result["Description"] = trim(preg_replace("/\s\s/uis", " ", sprintf(
            "%s %s-rooms hotel in %s",
            self::$HotelNames['Adjective'][mt_rand(0, count(self::$HotelNames['Adjective'])-1)],
            $result["RoomsCount"],
            $result["City"]
            
        )));
        
        
        $result["Address"] = trim(preg_replace("/\s\s/uis", " ", sprintf(
            "%s, %s, %s",
            $result["City"],
            self::$HotelNames['Streets'][mt_rand(0, count(self::$HotelNames['Streets'])-1)],
            mt_rand(1, 20)
            
        )));
        
        
        if (mt_rand(1, 10) > 3) {
            $result["ArbitaryFields"]["phone_no"] = preg_replace_callback(
                "/#/muis",
                function ($matches) {
                    return mt_rand(0, 9);
                },
                self::$PhoneNos[$result["City"]]
            );
        }
        
        if (mt_rand(1, 10) > 5) {
            $result["ArbitaryFields"]["lat"] = self::$GeoCoords[$result["City"]]["lat"];
            $result["ArbitaryFields"]["lon"] = self::$GeoCoords[$result["City"]]["lon"];
        }
        
        return $result;
    }
    
    
    public static function generateRandomHotelRoomData()
    {
        $result = array(
            "Name"        => null,
            "Description" => null,
        );
        //
        $nBeds = mt_rand(1, 5);
        //
        
        
        $result["Name"] = trim(preg_replace("/\s\s/uis", " ", sprintf(
            "%s (%s)",
            self::$HotelRooms['Names'][mt_rand(0, count(self::$HotelNames['Names'])-1)],
            $nBeds
            
        )));
        
        
        $result["Description"] = trim(preg_replace("/\s\s/uis", " ", sprintf(
            "%s room with  %s bed(s)",
            self::$HotelRooms['Names'][mt_rand(0, count(self::$HotelNames['Names'])-1)],
            $nBeds
            
        )));
        
        
        return $result;
    }
    
    
    
    
    public static function generateRandomHotelServiceData()
    {
        $result = array(
            "Name"        => null,
            "Description" => null,
        );
        //
        $nRnd = mt_rand(1, 10);
        //
        $_base_name = self::$HotelServices['Names'][mt_rand(0, count(self::$HotelServices['Names'])-1)];
        //
        
        $result["Name"] = trim(preg_replace("/\s\s/uis", " ", sprintf(
            "%s (%s)",
            $_base_name,
            $nRnd
            
        )));
        
        
        $result["Description"] = trim(preg_replace("/\s\s/uis", " ", sprintf(
            "%s included",
            $_base_name,
            $nRnd
            
        )));
        
        
        return $result;
    }
}
