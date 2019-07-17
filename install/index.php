<?php
/**
 *
 *
 * @var array             $config
 * @var \Test\Application $app
 *
 */
require($_SERVER["DOCUMENT_ROOT"]."/systemice.test"."/.bootstrap.php");
require($_SERVER["DOCUMENT_ROOT"]."/systemice.test"."/.shared/prolog.phtml");
require($_SERVER["DOCUMENT_ROOT"]."/systemice.test"."/.init.php");
?>
<?php
//
$output = null;
//
try {
    ob_start();
    //
    $app->initConnection();
    //
    $ready = null;
    //
    if (!$app->checkDatabasePresence()) {
        echo \Test\Debug\Decorator::renderError("База данных не найдена!<br /> Запустите установщик!");
        
        if ($app->createDatabase()) {
            echo \Test\Debug\Decorator::renderSuccess("База данных успешно создана!");
        }
        $ready = false;
    } elseif (!($app->switchDatabase() and $app->checkDatabaseStructure())) {
        echo \Test\Debug\Decorator::renderError("Отсутствует одна или несколько из необходимых таблиц!<br /> Запустите установщик!");
        
        
        
        
        if ($app->createTables(__DIR__."/sql")) {
            echo \Test\Debug\Decorator::renderSuccess("Таблицы успешно созданы!");
        } else {
            echo \Test\Debug\Decorator::renderError(sprintf(
                "<b>%s</b>:<pre>%s</pre>",
                $app->getConnection()->errorCode(),
                print_r($app->getConnection()->errorInfo(), true)
            ));
        }
        $ready = true;
    } else {
        echo \Test\Debug\Decorator::renderSuccess("Всё в порядке!<br /> База данных готова к работе!");
        
        $ready = true;
        
        
        if (isset($_POST["action"])) {
            switch ($_POST["action"]) {
                case "data.fillrandom":
                {
                    //echo $_POST["action"];
                    
                    $result = $app->fillRandomData(array(
                        "nHotels"         => 10,
                        "nGoods"           => 5000,
                        "nOrdersPerClient" => [1,1000],
                        "arPriceRange"     => [1,100000],
                    ));
                    //
                    echo \Test\Debug\Decorator::renderSuccess(sprintf("Добавлено отелей <b>%s</b>", $result["nAddedHotels"]));
                    echo \Test\Debug\Decorator::renderSuccess(sprintf("Добавлено услуг <b>%s</b>", $result["nAddedServices"]));
                    echo \Test\Debug\Decorator::renderSuccess(sprintf("Добавлено номеров <b>%s</b>", $result["nAddedRooms"]));
                    echo \Test\Debug\Decorator::renderSuccess(sprintf("Добавлено изображений <b>%s</b>", $result["nAddedImages"]));
                    //
                    break;
                }
                case "data.truncate":
                {
                    if ($nTruncated = $app->truncateAll()) {
                        echo \Test\Debug\Decorator::renderError(sprintf("Данные удалены", $nTruncated));
                    }
                    break;
                }
                case "data.showall":
                {
                    $page_size = 50;
                    $page_no   = 1;
                    //
                    $stmpHotels     = $app->getConnection()->prepare("SELECT * FROM `Hotels` LIMIT :from,:to;");
                    $stmpServices   = $app->getConnection()->prepare("SELECT * FROM `Hotels_Services` LIMIT :from,:to;");
                    $stmpRooms      = $app->getConnection()->prepare("SELECT * FROM `Hotels_Rooms` LIMIT :from,:to;");
                    $stmpImages     = $app->getConnection()->prepare("SELECT * FROM `Images` LIMIT :from,:to;");
                    //
                    $limit_from = $page_size*($page_no-1);
                    $limit_to   = $page_size*($page_no);
                    
                    
                    
                    
                    //
                    $stmpHotels->bindParam(':from', $limit_from, \PDO::PARAM_INT);
                    $stmpHotels->bindParam(':to', $limit_to, \PDO::PARAM_INT);
                    
                    $stmpHotels->execute();
                    
                    $hotels = $stmpHotels->fetchAll(\PDO::FETCH_ASSOC);
                    
                    $stmpHotels->closeCursor();
                    
                    
                    
                    
                    
                    //
                    $stmpServices->bindParam(':from', $limit_from, \PDO::PARAM_INT);
                    $stmpServices->bindParam(':to', $limit_to, \PDO::PARAM_INT);
                    
                    $stmpServices->execute();
                    
                    $services = $stmpServices->fetchAll(\PDO::FETCH_ASSOC);
                    
                    $stmpServices->closeCursor();
                    
                    
                    //
                    $stmpRooms->bindParam(':from', $limit_from, \PDO::PARAM_INT);
                    $stmpRooms->bindParam(':to', $limit_to, \PDO::PARAM_INT);
                    
                    $stmpRooms->execute();
                    
                    $rooms = $stmpRooms->fetchAll(\PDO::FETCH_ASSOC);
                    
                    $stmpRooms->closeCursor();
                    
                    
                    //
                    $stmpImages->bindParam(':from', $limit_from, \PDO::PARAM_INT);
                    $stmpImages->bindParam(':to', $limit_to, \PDO::PARAM_INT);
                    
                    $stmpImages->execute();
                    
                    $images = $stmpImages->fetchAll(\PDO::FETCH_ASSOC);
                    
                    $stmpImages->closeCursor();
                    
                    ?>
                    <h3>Отели</h3>
                    <table class="table table-striped table-bordered table-hover table-sm">
                    <?php foreach ($hotels as $client) {?>
                    <tr>
                        <th><?php echo $client["ID"];?></th>
                        <td><?php echo $client["Name"];?></td>
                        <td><?php echo $client["Description"];?></td>
                        <td><?php echo $client["Address"];?></td>
                        <td><?php echo $client["ArbitaryFields"];?></td>
                    </tr>
                    <?php } ?>
                    </table>
                    <h3>Услуги</h3>
                    <table class="table table-striped table-bordered table-hover table-sm">
                    <?php foreach ($services as $record) {?>
                    <tr>
                        <th><?php echo $record["ID"];?></th>
                        <td><?php echo $record["Name"];?></td>
                        <td><?php echo $record["Description"];?></td>
                    </tr>
                    <?php } ?>
                    </table>
                    <h3>Номера</h3>
                    <table class="table table-striped table-bordered table-hover table-sm">
                    <?php foreach ($rooms as $record) {?>
                    <tr>
                        <th><?php echo $record["ID"];?></th>
                        <th><?php echo $record["Hotel_ID"];?></th>
                        <td><?php echo $record["Name"];?></td>
                        <td><?php echo $record["Description"];?></td>
                    </tr>
                    <?php } ?>
                    </table>
                    
                    <h3>Изображения</h3>
                    <table class="table table-striped table-bordered table-hover table-sm">
                    <?php foreach ($images as $record) {?>
                    <tr>
                        <th><?php echo $record["ID"];?></th>
                        <td><?php echo $record["customer_id"];?></td>
                        <td><?php echo $record["item_id"];?></td>
                        <td><?php echo $record["comment"];?></td>
                        <td><?php echo $record["status"];?></td>
                        <td><?php echo $record["order_date"];?></td>
                    </tr>
                    <?php } ?>
                    </table>
                    <?php
                    
                    
                    
                    
                    
                    
                    break;
                }
                default:
                {
                    break;
                }
            }
        }
    }
    //
    $output = ob_get_clean();
} catch (\PDOException $e) {
    echo \Test\Debug\Decorator::renderError($e->getMessage());
} catch (\Exception $e) {
    echo \Test\Debug\Decorator::renderError($e->getMessage());
}
?>



<h1>
    Установка
</h1>
<div>
<?php echo $output; ?>


<?php if ($ready) {?>

  <div class="row">
    <div class="col-sm-6" style="text-align: center;">
        <form method="post">
            <button
                class="btn btn-primary" 
                name="action" 
                value="data.fillrandom" 
                type="submit" 
            >
                Заполнить случайными данными
            </button>
        </form>
    </div>
    <div class="col-sm-6" style="text-align: center;">
        <form method="post">
            <button
                class="btn btn-danger " 
                name="action" 
                value="data.truncate" 
                type="submit" 
            >
                Очистить все данные
            </button>
        </form>
    </div>
    <div class="col-sm-12" style="text-align: center; margin-top: 20px;">
        <form method="post">
            <button
                class="btn btn-outline-secondary" 
                name="action" 
                value="data.showall" 
                type="submit" 
            >
                Обзор
            </button>
        </form>
    </div>
  </div>



<?php }?>
</div>

<?php

require($_SERVER["DOCUMENT_ROOT"]."/systemice.test"."/.shared/epilog.phtml");
?>