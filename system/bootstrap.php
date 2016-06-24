<?php
/*
 * mobiCMS Content Management System (http://mobicms.net)
 *
 * For copyright and license information, please see the LICENSE.md
 * Installing the system or redistributions of files must retain the above copyright notice.
 *
 * @link        http://johncms.com JohnCMS Project
 * @copyright   Copyright (C) JohnCMS Community
 * @license     GPL-3
 */

date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

define('START_MEMORY', memory_get_usage());
define('START_TIME', microtime(true));

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);

require __DIR__ . '/vendor/autoload.php';

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

class App
{
    private static $container;

    /**
     * @return ServiceManager
     */
    public static function getContainer()
    {
        if (null === self::$container) {
            $config = [];

            // Read configuration
            foreach (Glob::glob(CONFIG_PATH . '{{,*.}global,{,*.}local}.php', Glob::GLOB_BRACE) as $file) {
                $config = ArrayUtils::merge($config, include $file);
            }

            self::$container = new ServiceManager;
            (new Config($config['dependencies']))->configureServiceManager(self::$container);
            self::$container->setService('config', $config);
        }

        return self::$container;
    }
}
