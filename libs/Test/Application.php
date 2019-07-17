<?php
namespace Test;

/**
 *
 * @package   Test
 * @author    Premier13 <alex@yandexolog.ru>
 *
 */
class Application
{
    /**
     *
     * @var \PDO
     */
    protected $_db = null;
    /**
     *
     * @var array
     */
    protected $_config   = array();
    /**
     *
     * @var array
     */
    protected $_required_tables = array(
        "Hotels",
        "Hotels_Services",
        "Hotels_Rooms",
        "Images",
        
        
        "Hotels_ServiceToHotel",
        "Hotels_ImageToHotel",
        "Hotels_ImageToRoom",
        
        "Hotels_ArbitaryFields",
    );
    /**
     *
     * @var array
     */
    protected $_truncable_tables = array(
        /*


        "Hotels_ServiceToHotel",
        "Hotels_ImageToHotel",
        "Hotels_ImageToRoom",

        "Hotels_ArbitaryFields",

        "Hotels_Rooms",
        "Hotels_Services",
        "Hotels",


        "Images",
        */
    );
    
    
    /**
     *
     * @var array
     */
    protected $_delete_all_tables = array(
        
        
        
        "Hotels_ServiceToHotel",
        "Hotels_ImageToHotel",
        "Hotels_ImageToRoom",
        
        "Hotels_ArbitaryFields",
        
        "Hotels_Rooms",
        "Hotels_Services",
        "Hotels",
        
        
        "Images",
    );
    
    
    
    public function __construct($config=array())
    {
        if (!$config) {
            throw new \Exception("Empty application configuration");
        } else {
            $this->_config = $config;
        }
    }
    
    public function initConnection()
    {
        $this->_db = new \PDO(
            sprintf(
                //'%s:host=%s;dbname=%s'
                '%s:host=%s;',
                $this->_config["db"]["type"],
                $this->_config["db"]["host"]
                //,$this->_config["db"]["database"]
            ),
            $this->_config["db"]["user"],
            $this->_config["db"]["password"],
            array(
                \PDO::ATTR_PERSISTENT => $this->_config["db"]["persistent"]
            )
        );
        $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
        $this->_db->exec("SET NAMES 'utf8'");
        $this->_db->exec("SET CHARACTER SET 'utf8'");
        $this->_db->exec("SET SESSION collation_connection = 'utf8_general_ci'");
        return $this;
    }
    
    /**
     *
     * @return \PDO
     */
    public function &getConnection()
    {
        return $this->_db;
    }
    
    
    
    
    /**
     * Проверка наличия необходимой базы данных
     *
     * @return NULL|boolean
     */
    public function checkDatabasePresence()
    {
        $result = null;
        
        $stmt = $this->getConnection()->prepare("SHOW DATABASES LIKE ?;");
        if ($stmt->execute(array(
            $this->_config["db"]["database"],
        ))) {
            while ($stmt->fetch()) {
                $result++;
            }
        } else {
            $result = false;
        }
        //
        $stmt->closeCursor();
        //
        return $result;
    }
    /**
     *
     * @return boolean
     */
    public function checkDatabaseStructure()
    {
        $stmt = $this->getConnection()->query("SHOW TABLES;");
        
        $tables   = array();
        
        $missing  = array();
        
        foreach ($stmt as $row) {
            $tables[] = $row[0];
        }
        
        $stmt->closeCursor();
        
        
        $missing = array_diff($this->_required_tables, $tables);
        
        return !count($missing);
    }
    
    
    /**
     *
     * @param string $name
     * @return boolean
     */
    public function switchDatabase($name=null)
    {
        if ($name === null) {
            $name = $this->_config["db"]["database"];
        }
        
        return $this->getConnection()->exec(sprintf("USE %s;", $name)) === 0;
    }
    /**
     *
     * @return boolean
     */
    public function createDatabase()
    {
        $sql = sprintf(
            "CREATE SCHEMA `%s` DEFAULT CHARACTER SET %s COLLATE %s ;",
            $this->_config["db"]["database"],
            "utf8",
            "utf8_general_ci"
        );
        return $this->getConnection()->exec($sql) === 0;
    }
    /**
     *
     * @param string $sql_dir
     * @return boolean
     */
    public function createTables($sql_dir=null)
    {
        return($this->getConnection()->exec(file_get_contents($sql_dir."/mysql/create_tables.sql")) === 0);
    }
    
    
    
    /**
     *
     *
     *
     * @return boolean
     */
    public function truncateAll()
    {
        $result = 0;
        
        foreach ($this->_truncable_tables as $name) {
            $sql = sprintf(
                "TRUNCATE `%s`;",
                $name
            );
            $result += $this->getConnection()->exec($sql) === 0;
        }
        
        
        foreach ($this->_delete_all_tables as $name) {
            $sql = sprintf(
                "DELETE FROM `%s`;",
                $name
            );
            $result += $this->getConnection()->exec($sql) === 0;
        }
        
        return $result;
    }
    
    
    
    
    
    public function fillRandomData($options)
    {
        $result = array(
            "nAddedHotels"     => 0,
            "nAddedServices"   => 0,
            "nAddedRooms"      => 0,
            "nAddedImages"     => 0,
            "nAddedServices"   => 0,
        );
        //
        if (!$options) {
            return $result;
        }
        ///
        
        $nHotels            = $options["nHotels"]            ?? 10;
        $nServices          = $options["nServices"]          ?? 10;
        $nImagesPerHotel    = $options["nImagesPerHotel"]    ?? [3,5];
        $nImagesPerRoom     = $options["nImagesPerRoom"]     ?? [2,5];
        //$nServicesPerHotel  = $options["nServicesPerHotel"]  ?? [1,5];
        $nRoomsPerHotel     = $options["nRoomsPerHotel"]     ?? [5,20];
        //
        $stmtHotel          = $this->getConnection()->prepare("INSERT INTO `Hotels`               (`Name`,`Description`,`Address`,`ArbitaryFields`)   VALUES (:Name,:Description,:Address,:ArbitaryFields);");
        $stmtService        = $this->getConnection()->prepare("INSERT INTO `Hotels_Services`      (`Name`,`Description`)                              VALUES (:Name,:Description);");
        $stmtRoom           = $this->getConnection()->prepare("INSERT INTO `Hotels_Rooms`         (`Hotel_ID`,`Name`,`Description`)                   VALUES (:Hotel_ID,:Name,:Description);");
        
        $stmtServiceToHotel = $this->getConnection()->prepare("INSERT INTO `Hotels_ServiceToHotel`  (`Service_ID`,`Hotel_ID`)                           VALUES (:Service_ID,:Hotel_ID);");
        
        $stmtImage          = $this->getConnection()->prepare("INSERT INTO `Images`               (`Name`,`LocalPath`,`Url`,`Filesize`,`Hash`)        VALUES (:Name,:LocalPath,:Url,:Filesize,:Hash);");
        
        $stmtImageToHotel   = $this->getConnection()->prepare("INSERT INTO `Hotels_ImageToHotel`  (`Image_ID`,`Hotel_ID`)                             VALUES (:Image_ID,:Hotel_ID);");
        $stmtImageToRoom    = $this->getConnection()->prepare("INSERT INTO `Hotels_ImageToRoom`   (`Image_ID`,`Room_ID`)                              VALUES (:Image_ID,:Room_ID);");
        //
        $nAddedHotels   = 0;
        $nAddedRooms    = 0;
        $nAddedServices = 0;
        $nAddedImages   = 0;
        
        $arAddedHotelIDs     = array();
        $arAddedServicesDs   = array();
        $arAddedRoomIDs      = array();
        $arAddedImageIDs     = array();
        
        /*
        $_statuses = array(
            "new","complete"
        );
        */
        
        //$now = time();
        
        for ($i=0; $i < $nServices; $i++) {
            $_data = \Test\RandomDataGenerator::generateRandomHotelServiceData();
            
            //echo sprintf("<pre>%s</pre>",$name);
            $stmtService->execute(array(
                "Name"           => $_data["Name"],
                "Description"    => $_data["Description"],
            ));
            
            $_service_id = $this->getConnection()->lastInsertId();
            
            
            
            
            
            
            $arAddedServicesDs[] = $_service_id;
            
            $nAddedServices++;
        }
        
        
        
        for ($i=0; $i < $nHotels; $i++) {
            //$name = \Test\RandomDataGenerator::generateRandomHotelName();
            
            $_data = \Test\RandomDataGenerator::generateRandomHotelData($nRoomsPerHotel);
            
            //echo sprintf("<pre>%s</pre>",$name);
            $stmtHotel->execute(array(
                "Name"           => $_data["Name"],
                "Description"    => $_data["Description"],
                "Address"        => $_data["Address"],
                //
                "ArbitaryFields" => json_encode($_data["ArbitaryFields"]),
            ));
            
            $_hotel_id = $this->getConnection()->lastInsertId();
            
            
            
            
            
            
            $arAddedHotelIDs[] = $_hotel_id;
            
            
            
            
            
            $nMaxImages = mt_rand($nImagesPerHotel[0], $nImagesPerHotel[1]);
            
            for ($x=0; $x < $nMaxImages; $x++) {
                $_rnd_name = uniqid(rand(), true);
                
                //
                $stmtImage->execute(array(
                    "Name"      => sprintf("DCIM%04d.jpeg", mt_rand(0, 9999)),
                    "LocalPath" => sprintf("/var/www/public/images/%s.jpeg", $_rnd_name),
                    "Url"       => sprintf("/images/%s.jpeg", uniqid(rand(), true)),
                    "Filesize"  => mt_rand(100000, 2999999),
                    "Hash"      => md5("/var/www/public/images/%s.jpeg".$_rnd_name),
                    
                ));
                
                $_image_id = $this->getConnection()->lastInsertId();
                
                $arAddedImageIDs[] = $_image_id;
                
                
                $stmtImageToHotel->execute(array(
                    "Image_ID"  => $_image_id,
                    "Hotel_ID"  => $_hotel_id,
                    
                ));
                
                $nAddedImages++;
            }
            
            if ($arAddedServicesDs) {
                for ($s=0; $s < mt_rand(0, 3); $s++) {
                    $_service_id =$arAddedServicesDs[mt_rand(0, count($arAddedServicesDs)-1)];
                    
                    $stmtServiceToHotel->execute(array(
                        "Service_ID"  => $_service_id,
                        "Hotel_ID"    => $_hotel_id,
                        
                    ));
                }
            }
            
            
            for ($r=0; $r < $_data["RoomsCount"]; $r++) {
                //
                //$name  = \Test\RandomDataGenerator::generateRandomMerchandise();
                //$price = mt_rand($arPriceRange[0],$arPriceRange[1])/100;
                
                //echo sprintf("<pre>%s: %s</pre>",$name,$price);
                
                $_room_data = \Test\RandomDataGenerator::generateRandomHotelRoomData();
                
                $stmtRoom->execute(array(
                    "Hotel_ID"       => $_hotel_id,
                    "Name"           => $_room_data["Name"],
                    "Description"    => $_room_data["Description"],
                    
                ));
                
                
                //echo sprintf("<pre>%s: [%s]</pre>",$name,$app->getConnection()->lastInsertId());
                
                $_room_id  = $this->getConnection()->lastInsertId();
                
                $arAddedRoomIDs[] = $_room_id;
                
                $nAddedRooms++;
                //
                
                
                
                
                
                
                $nMaxImages = mt_rand($nImagesPerRoom[0], $nImagesPerRoom[1]);
                
                for ($x=0; $x < $nMaxImages; $x++) {
                    $_rnd_name = uniqid(rand(), true);
                    
                    //
                    $stmtImage->execute(array(
                        "Name"      => sprintf("DCIM%04d.jpeg", mt_rand(0, 9999)),
                        "LocalPath" => sprintf("/var/www/public/images/%s.jpeg", $_rnd_name),
                        "Url"       => sprintf("/images/%s.jpeg", uniqid(rand(), true)),
                        "Filesize"  => mt_rand(100000, 2999999),
                        "Hash"      => md5("/var/www/public/images/%s.jpeg".$_rnd_name),
                        
                    ));
                    
                    $_image_id = $this->getConnection()->lastInsertId();
                    
                    $arAddedImageIDs[] = $_image_id;
                    
                    
                    $stmtImageToRoom->execute(array(
                        "Image_ID"  => $_image_id,
                        "Room_ID"   => $_room_id,
                        
                    ));
                    
                    $nAddedImages++;
                }
            }
            
            
            //echo sprintf("<pre>%s: [%s]</pre>",$name,$app->getConnection()->lastInsertId());
            
            $nAddedHotels++;
        }
        
        
        /*
        for($i=0; $i < $nGoods; $i++)
        {


            $name  = \Test\RandomDataGenerator::generateRandomMerchandise();
            $price = mt_rand($arPriceRange[0],$arPriceRange[1])/100;

            //echo sprintf("<pre>%s: %s</pre>",$name,$price);

            $stmtMerchandise->execute(array(
                "name"  => $name,
                "price" => $price

            ));


            //echo sprintf("<pre>%s: [%s]</pre>",$name,$app->getConnection()->lastInsertId());

            $arAddedGoodsDs[] = $this->getConnection()->lastInsertId();

            $nAddedGoods++;
        }



        foreach ($arAddedClientIDs as $client_id)
        {
            $_nOrders = mt_rand($nOrdersPerClient[0],$nOrdersPerClient[1]);
            //
            $_order = array(
                "customer_id" => $client_id,
                "item_id"     => NULL,
                "comment"     => NULL,
                "status"      => NULL,
                "order_date"  => NULL,
            );

            for($i=0; $i < $_nOrders;$i++)
            {
                $_date = date("Y-m-d H:i:s",$now - mt_rand(0,60*60*24*7*4));


                $_order["item_id"]     = $arAddedGoodsDs[mt_rand(0,count($arAddedGoodsDs)-1)];
                $_order["comment"]     = "";
                $_order["status"]      = $_statuses[mt_rand(0,1)];
                $_order["order_date"]  = $_date;
                //

                $stmtOrder->execute($_order);

                //echo sprintf("<pre>%s: [%s]</pre>",$name,$app->getConnection()->lastInsertId());

                $arAddedOrderIDs[] = $this->getConnection()->lastInsertId();

                //
                $nAddedOrders++;
            }

        }
        */
        

        //
        $result["nAddedHotels"] = $nAddedHotels;
        $result["nAddedServices"]   = $nAddedServices;
        $result["nAddedRooms"]  = $nAddedRooms;
        $result["nAddedImages"]  = $nAddedImages;
        //
        $result["AddedHotelIDs"] = $arAddedHotelIDs;
        $result["AddedServiceDs"]   = $arAddedServicesDs;
        $result["AddedRoomIDs"]  = $arAddedRoomIDs;
        $result["AddedImageIDs"]  = $arAddedImageIDs;
        
        return $result;
    }
}
