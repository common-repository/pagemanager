<?php
/**
 * Plugin Name: PageManager
 * Plugin URI: https://wp-pagemanager.com/
 * Description: Wordpress content management on a new level. Curate your posts and compose page blocks with a variety of layouts.
 * Version: 1.0.9
 *
 * @copyright      Carsten Kermer
 *
 *
 * PageManager is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * PageManager is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

/**
 * @param $var
 */

use Pagemanager\PageManagerAdmin;
use Pagemanager\AutoLoader;
use Pagemanager\PageManager;

define('PAGEMANAGER_PLUGIN_VERSION', '1.0.9');
define('PAGEMANAGER_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('PAGEMANAGER_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('PAGEMANAGER_PLUGIN_SRC_PATH', PAGEMANAGER_PLUGIN_PATH . 'src/');

//AutoLoader if no other solution like composer is used
load_plugin_textdomain('pagemanager', false, basename( dirname( __FILE__ ) ) . '/languages' );

if (!class_exists('PageManagerAdmin')) {
    require_once(PAGEMANAGER_PLUGIN_SRC_PATH . 'Autoloader.php');
    $autoLoader = new AutoLoader('Pagemanager');
    $autoLoader->register();
}

add_action('plugins_loaded', array(PageManager::getInstance(), 'init'));
add_action('plugins_loaded', array(PageManagerAdmin::getInstance(), 'init'));

if (is_admin()) {
    register_activation_hook(__FILE__, array(PageManagerAdmin::getInstance(), 'activate'));
    register_deactivation_hook(__FILE__, array(PageManagerAdmin::getInstance(), 'deactivate'));
}

add_shortcode('pagemanager', [PageManager::getInstance(),'shortcode']);

if ( ! function_exists( 'pm_fs' ) ) {
    // Create a helper function for easy SDK access.
    function pm_fs() {
        global $pm_fs;

        if ( ! isset( $pm_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $pm_fs = fs_dynamic_init( array(
                'id'                  => '10004',
                'slug'                => 'pagemanager',
                'type'                => 'plugin',
                'public_key'          => 'pk_d8a278bcc77c92e1956d96169830e',
                'is_premium'          => false,
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'trial'               => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                'menu'                => array(
                    'slug'           => 'pagemanager',
                    'support'        => false,
                ),
                // Set the SDK to work in a sandbox mode (for development & testing).
                // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
                'secret_key'          => 'undefined',
            ) );
        }

        return $pm_fs;
    }

    // Init Freemius.
    pm_fs();
    // Signal that SDK was initiated.
    do_action( 'pm_fs_loaded' );
}


