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
try {
    $app->initConnection();
    //
    if (!$app->checkDatabasePresence()) {
        echo \Test\Debug\Decorator::renderError("База данных не найдена!<br /> Запустите установщик!"); ?>
        <div style="text-align: center;">
            <a 
                class="btn btn-primary btn-lg"
                href="<?php echo SELF_URL_ROOT; ?>install/"
            >
                Запустить установку
            </a>
        </div>
        <?php
    } elseif (!($app->switchDatabase() and $app->checkDatabaseStructure())) {
        echo \Test\Debug\Decorator::renderError("Отсутствует одна или несколько из необходимых таблиц!<br /> Запустите установщик!"); ?>
        <div style="text-align: center;">
            <a 
                class="btn btn-primary btn-lg"
                href="<?php echo SELF_URL_ROOT; ?>install/"
            >
                Запустить установку
            </a>
        </div>
        <?php
    } else {
        echo \Test\Debug\Decorator::renderSuccess("Всё в порядке! Готовы к работе!");
    }
    //
} catch (\PDOException $e) {
    echo \Test\Debug\Decorator::renderError($e->getMessage());
} catch (\Exception $e) {
    echo \Test\Debug\Decorator::renderError($e->getMessage());
}
?>
<?php

require($_SERVER["DOCUMENT_ROOT"]."/systemice.test"."/.shared/epilog.phtml");
?>