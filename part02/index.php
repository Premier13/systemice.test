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
//require($_SERVER["DOCUMENT_ROOT"]."/systemice.test"."/.init.php");
?>
<div>
    <h2>﻿Дано: </h2>

<br />
<div> 

</div> 
<br />

<h2>﻿Необходимо:</h2>
<br />
<div>
Задача 2. Написать модуль получения цен по отелям по заданных характеристика в различных сервисах. <br />
Есть два удаленных сервиса получения информации по отелям.<br />
Сервис Домик:<br />
Параметры<br />
<pre>
       @var baseUrl string
       @var roomId int //id категории номера
       @var hotel string //id отеля в системе Домик
       @var dateStart // дата заезда формата Y-m-d
       @var dateEnd // дата выезда формата Y-m-d
@return object
           {
               "price" float //стоимость
               "error" string
           }
</pre>




Сервис Страна:
<pre>
Параметры
       @var baseUrl string
       @var roomName string //название номера
       @var hotel string //id отеля в системе Страна
       @var dateStart // дата заезда формата d.m.Y
       @var dateEnd // дата выезда формата d.m.Y
@return object
           {
"result_price" float //итоговая стоимость
               "error" array          
          }
</pre>

В системе должен быть доступ для получения объекта отеля. <br />
Нужна возможность получения стоимости проживания в отеле, который есть у нас в базе, с помощью сервиса Домик или Страна.<br /> 
Причем каждый запрос мы должны хранить в базе, по какому отелю, на какие даты, на какие номера, какая была стоимость.<br />
<br />
Порядок действий примерно следующий.<br />

<ol>
    <li>Получаем отель.</li> 
    <li>Получаем стоимость проживания в любом доступном сервисе.</li> 
    <li>Сохраняем у себя запрос с результатом.</li> 
</ol>
Сам код выборки данных и сохранения данных в бд можно не писать, главное сделать пометку в коде, что здесь это происходит.<br />
В результате должен получиться модуль, фрагмент программы, или набор классов и интерфейсов для работы с этими сервисами.<br /> 
Т.е. результат зависит полностью от исполнителя. <br />
<br />
Задача поставлена максимально широко, чтобы понять на каком уровне разработчик готов выполнять задачи и какие требования к себе предъявляет. <br />
Если у вас появляется какой-то вопрос, действуйте на своё усмотрение. <br />
<br />
Желательно выполнить задание на основе ваших навыков.<br />
Плюсом будет выполнение задание с использованием ООП.<br />
Большим плюсом будет написать легко расширяемый модуль, с интерфейсами, покрытый доками.<br />
Если получиться сделать модуль, независимый от системы, в которой он находится.<br />
Так же плюсом будет, если модуль будет спроектирован с использованием классических шаблонов (паттернов) и классических принципов ООП, таких как solid, dry и пр.<br /> 
<br />
Язык программирование PHP >=5.6(PHP 7 будет плюсом).<br />
<br />
Код предоставить на github.com<br />

</div>


</div>

<hr />
<?php
//
\Hotels\Service::getInstance()->configureStorage(array(
    "path" => __DIR__."/../mock.data/",
));



$from =  \DateTime::createFromFormat("Y-m-d", "2019-07-16");
$to   =  \DateTime::createFromFormat("Y-m-d", "2019-07-26");
/*
$roomId = 5;

echo "<pre>";
echo print_r([
    \Hotels\Service::getInstance()->getRoomById($roomId),
    \Hotels\Service::getInstance()->getRoomById($roomId)->getHotel(),
    \Hotels\External\Service::getInstance()
        ->getBookingPricesForRoom(\Hotels\Service::getInstance()->getRoomById($roomId), $from, $to),
    \Hotels\External\Service::getInstance()
        ->getBookingPricesForRoom(\Hotels\Service::getInstance()->getRoomById($roomId), $to, $from),

], true);
echo "</pre>";
*/



$roomId = 12;
$room   = \Hotels\Service::getInstance()->getRoomById($roomId);



if (extension_loaded("xdebug")) {
    var_dump([
        $room,
        $room->getHotel(),
        $room->getBookingPrices($from, $to),
        $room->findBestBookingPrice($from, $to),
        $room->getFirstBookingPrice($from, $to),
        //$room->getBookingPrices($to, $from),
        
    ]);
} else {
    echo "<pre>";
    echo print_r([
        $room,
        $room->getHotel(),
        $room->getBookingPrices($from, $to),
        $room->findBestBookingPrice($from, $to),
        $room->getFirstBookingPrice($from, $to),
        //$room->getBookingPrices($to, $from),
        
    ], true);
    echo "</pre>";
}
?>
<?php /*/ ?>
<?php


$clientD = new \Mock\Domik\Client();
$clientS = new \Mock\Strana\Client();




echo "<pre>";
echo print_r($clientD->getRoomPrice("/api/", 1, 2, "2019-07-16", "2019-07-26"), true);
echo "</pre>";


echo "<pre>";
echo print_r($clientD->getRoomPrice("", 2, 4, "2019-07-16", "2019-07-26"), true);
echo "</pre>";

echo "<pre>";
echo print_r($clientD->getRoomPrice("", 10, 3, "2019-07-16", "2019-07-26"), true);
echo "</pre>";

echo "<pre>";
echo print_r($clientD->getRoomPrice("", 5, 3, "2019-07-16", "2019-07-26"), true);
echo "</pre>";



echo "<hr />";

echo "<pre>";
echo print_r($clientS->getLivingPriceRoomPrice("/api/", "deluxe", "s2", "16.07.2019", "26.07.2019"), true);
echo "</pre>";
?>

<?php

echo "<pre>";
echo print_r(\Hotels\External\Service::getInstance()->getExternalClientById("domik"), true);
echo "</pre>";

echo "<pre>";
echo print_r(\Hotels\External\Service::getInstance()->getExternalClientById("strana"), true);
echo "</pre>";
?>

<?php /*/ ?>
<?php

require($_SERVER["DOCUMENT_ROOT"]."/systemice.test"."/.shared/epilog.phtml");
?>
