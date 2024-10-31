<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 31.08.15
 * Time: 12:43
 */

namespace Pagemanager;

use Pagemanager\Model\PageTypes;
use Pagemanager\Model\Settings;

class PageManager
{
    /**
     * @var PageManager
     */
    protected static $instance;

    /**
     * @var Settings
     */
    protected $settingsModel;

    protected $pageSettings = array();

    public $page;

    public $queryType;

    public $case = 0;

    public $usedPosts = array();

    public $pagelist = array();

    /**
     * @var array
     */
    protected $postIds = array();

    private function __construct()
    {
        $this->init();
        $pagetypes = PageTypes::getInstance();
        foreach ($pagetypes->getPageTypes() as $pageType) {
            $this->pagelist[] = $pageType->getType();
        }
        $this->settingsModel = Settings::getInstance();
    }

    /**
     * @return PageManager
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    public function usePluginThemePath()
    {
        $currentThemePath = get_stylesheet_directory();
        //TODO: Licencing Code

        if (is_dir($currentThemePath . '/pageblock')){
            return false;
        }

        return true;
    }

    /**
     * @return PageManagerAdmin
     */
    public function init()
    {
        //new plugin Api
        add_action('init', array(Api::getInstance(), 'addEndpoints'));
        add_action('template_redirect', array(Api::getInstance(), 'doApi'));
        add_filter('get_the_date',array($this, 'wp_relative_date')); // for posts
        add_action('template_include', array($this, 'rewritePageTemplates'));

        return $this;
    }


    public function rewritePageTemplates($template)
    {
        if (!$this->usePluginThemePath()) {
            return $template;
        }

        wp_register_style('pb-default-style', PAGEMANAGER_PLUGIN_URL. 'styles/pageblock-default.css',  [], PAGEMANAGER_PLUGIN_VERSION);
        wp_enqueue_style('pb-default-style');

        $settings = get_option('pagemanager_settings', []);

        if (!is_array($settings) || count($settings)<1) {
            return $template;
        }

        $pluginThemePath = PAGEMANAGER_PLUGIN_PATH . 'src/Theme/';


        foreach (array_keys($settings['pagetype']) as $pagetype){
            switch($pagetype) {
                case 'index':
                    if (is_home()) {
                        $new_template = $pluginThemePath.'index.php';
                        if ( '' != $new_template ) {
                            return $new_template ;
                        }
                    }
                    break;
                case 'post_tag':
                case 'category':
                    if (is_category()){
                        $new_template = $pluginThemePath.'category.php';

                    }
                    if (is_tag()){
                        $new_template = $pluginThemePath.'tag.php';
                    }
                    if ( '' != $new_template ) {
                        return $new_template ;
                    }
                    break;
                default:
                    return $template;
                    break;
            }
        }
        return $template;
    }


    public static function run($page, $case = 0)
    {
        $pageController = new PageManager();
        if (!in_array($page, $pageController->pagelist)) {
            die("Error: NO VALID PAGE PARAMETER");
        }
        $pageController->page = $page;
        $pageController->case = $case;

        $pageController->pageSettings = $pageController->settingsModel->getSettings(
            $page,
            $case
        );

        foreach ($pageController->pageSettings as $pageSetting) {
            PageBlockFactory::render($pageSetting, $pageController);
        }
    }

    public function runGlobalHeader(){
        self::run(
            'globalheader'
        );
    }

    public function shortcode($atts, $label)
    {
        $blockSettings = [
            'type' => $atts['name'],
            'settings' => $atts
        ];
        ob_start();
        PageBlockFactory::render($blockSettings);
        return ob_get_clean();
    }

    function wp_relative_date() {
        return human_time_diff( get_the_time('U'), current_time( 'timestamp' ) ) . ' ago';
    }

}
