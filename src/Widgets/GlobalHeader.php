<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 03.12.21
 * Time: 09:48
 */

namespace Pagemanager\Widgets;


use Pagemanager\PageManager;

class GlobalHeader extends \WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        // widget actual processes
        $widget_ops = array('classname' => 'GlobalHeader', 'description' => 'Displays Pagemanager Global Header' );

        parent::__construct(
            'Pagemanager_Globalheader_Widget', // Base ID
            'PM Global Header Widget',// Name
            $widget_ops
        );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        PageManager::run('globalheader');
    }

    /**
     * Ouputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
        // outputs the options form on admin
        PageManager::run('globalheader');
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        return $instance;
    }
}