<?php
namespace Test\Debug;

/**
 *
 * @namespace Test
 * @package   Debug
 * @author    Premier13 <alex@yandexolog.ru>
 *
 */
class Decorator
{
    /**
     *
     * @param string $message
     * @return string
     */
    public static function renderError($message)
    {
        return sprintf(
            '<div class="alert alert-danger" role="alert">%s</div>',
            $message
        );
    }
    
    /**
     *
     * @param string $message
     * @return string
     */
    public static function renderSuccess($message)
    {
        return sprintf(
            '<div class="alert alert-success" role="alert">%s</div>',
            $message
            );
    }
}
