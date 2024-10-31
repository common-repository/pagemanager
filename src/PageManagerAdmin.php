<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 15.05.14
 * Time: 18:16
 */

namespace Pagemanager;

use Pagemanager\Model\PageType;
use Pagemanager\Model\PageTypes;
use Pagemanager\Model\Settings;
use Pagemanager\Controller\IndexController;
use Pagemanager\PageManager;
use Pagemanager\Widgets\GlobalFooter;
use Pagemanager\Widgets\GlobalHeader;

class PageManagerAdmin
{

    /**
     * @var PageManagerAdmin
     */
    protected static $instance;

    private function __construct()
    {
    }

    /**
     * @return PageManagerAdmin
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }



    function register_widgets() {
        register_widget( GlobalHeader::class );
        register_widget( GlobalFooter::class );
    }

    function register_widget_areas(){
        register_sidebar( array(
            'name'          => 'PM Above Footer Area',
            'id'            => 'pagemanager_footer_widget_area',
            'before_widget' => '<div>',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="rounded">',
            'after_title'   => '</h2>',
        ) );
    }

    /**
     *
     */
    function footerWidget()
    {
        if (function_exists('dynamic_sidebar')) {
            dynamic_sidebar('pagemanager_footer_widget_area' );
        }

    }

    /**
     * @return PageManagerAdmin
     */
    public function init()
    {
        add_action('admin_bar_menu', array($this, 'toolbarMenu'), 100);
        add_action('admin_menu', array($this, 'registerPagemanagerPage'));
        add_action('wp_ajax_pagemanager-helper-search', array($this, 'ajaxHelperSearch'));
        add_action('get_footer', [$this, 'footerWidget']);

        //metabox sidebar mit Links and pageblock shortcode generator
        add_action('add_meta_boxes', array($this, 'addMetaBox'));

        //Widgets
//        add_action( 'widgets_init', [$this,'register_widgets']);
//        add_action( 'widgets_init', [$this,'register_widget_areas']);

        $pageTypesInstance = PageTypes::getInstance();
        foreach ($pageTypesInstance->getActivePresetPageTypes() as $pageTypeSettings) {
            $type = $pageTypeSettings[0];
            $label = $pageTypeSettings[1];
            $caseIdType = isset($pageTypeSettings[2])
                ? $pageTypeSettings[2]
                : array(0 => 'Standard');
            new PageType($type, $label, $caseIdType);
        }

        if (file_exists(get_template_directory().'/pagemanager.php')) {
            include_once(get_template_directory().'/pagemanager.php');
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function activate()
    {
        $settings = new Settings();
        $settings->createTable();

        // sets the user rights to the current user role
        global $current_user;
        $userRole = array_shift($current_user->roles);
        $role = get_role($userRole);
        $role->add_cap('pagemanager_edit');
        $role->add_cap('pagemanager_import');
        $role->add_cap('pagemanager_settings');

        return $this;
    }

    /**
     *
     */
    public function deactivate()
    {
    }

    /**
     * Add styles and jQuery scripts for Admin Backend.
     *
     * @return DashboardAdmin
     */
    public function addScriptsAndStyles()
    {
        wp_register_style('fa-style', PAGEMANAGER_PLUGIN_URL . 'styles/font-awesome/css/font-awesome.min.css', [],PAGEMANAGER_PLUGIN_VERSION);
        wp_enqueue_style('fa-style');

        wp_register_style('pm-style', PAGEMANAGER_PLUGIN_URL . 'styles/style.css',[],PAGEMANAGER_PLUGIN_VERSION );
        wp_enqueue_style('pm-style');

        return $this;
    }

    /**
     * @param $admin_bar
     * @return $this
     */
    function toolbarMenu($admin_bar){

        if (!is_admin() and  current_user_can( 'pagemanager_edit' )){
            $admin_bar->add_menu( array(
                'id'    => 'toolbar-pagemanager',
                'title' => 'Pagemanager',
                'href'  => '#',
                'meta'  => array(
                    'title' => __('Pagemanager'),
                ),
            ));
            if (is_home()) {
                $admin_bar->add_menu( array(
                    'id'    => 'toolbar-pagemanager',
                    'title' => 'Pagemanager Home',
                    'href'  => admin_url() . 'admin.php?page=pagemanager&target=index&backlink='.urlencode($_SERVER['REQUEST_URI']),
                    'meta'  => array(
                        'title' => __('Pagemanager Home'),
                        //'target' => '_blank',
                        'class' => 'my_menu_item_class'
                    ),
                ));
            }
            if (is_page()){
                $admin_bar->add_menu( array(
                    'id'    => 'toolbar-pagemanager',
                    'title' => 'Pagemanager Page',
                    'href'  => admin_url() . 'admin.php?page=pagemanager&target=page&case='.get_post()->ID,
                    'meta'  => array(
                        'title' => __('Pagemanager Page'),
                        //'target' => '_blank',
                        'class' => 'my_menu_item_class'
                    ),
                ));
                $admin_bar->add_menu( array(
                    'id'    => 'toolbar-pagemanager-page',
                    'parent' => 'toolbar-pagemanager',
                    'title' => 'Default Page',
                    'href'  => admin_url() . 'admin.php?page=pagemanager&target=page&case=0',
                    'meta'  => array(
                        'title' => __('Page Default'),
                        //'target' => '_blank',
                        'class' => 'my_menu_item_class'
                    ),
                ));
            }
            if (is_single()){
                $admin_bar->add_menu( array(
                    'id'    => 'toolbar-pagemanager',
                    'title' => 'Pagemanager Post',
                    'href'  => admin_url() . 'admin.php?page=pagemanager&target=post&case='.get_post()->ID,
                    'meta'  => array(
                        'title' => __('Pagemanager Post'),
                        //'target' => '_blank',
                        'class' => 'my_menu_item_class'
                    ),
                ));
                $admin_bar->add_menu( array(
                    'id'    => 'toolbar-pagemanager-post',
                    'parent' => 'toolbar-pagemanager',
                    'title' => 'Post Default',
                    'href'  => admin_url() . 'admin.php?page=pagemanager&target=post&cas=0',
                    'meta'  => array(
                        'title' => __('Post Default'),
                        //'target' => '_blank',
                        'class' => 'my_menu_item_class'
                    ),
                ));
            }
            if (is_category()){
                $admin_bar->add_menu( array(
                    'id'    => 'toolbar-pagemanager',
                    'title' => 'Pagemanager Category Page',
                    'href'  => admin_url() . 'admin.php?page=pagemanager&target=category&case='.get_queried_object()->term_id,
                    'meta'  => array(
                        'title' => __('Pagemanager Category Page'),
                        //'target' => '_blank',
                        'class' => 'my_menu_item_class'
                    ),
                ));
                $admin_bar->add_menu( array(
                    'id'    => 'toolbar-pagemanager-post',
                    'parent' => 'toolbar-pagemanager',
                    'title' => 'Category Default',
                    'href'  => admin_url() . 'admin.php?page=pagemanager&target=category&case=0',
                    'meta'  => array(
                        'title' => __('Category Page Default'),
                        //'target' => '_blank',
                        'class' => 'my_menu_item_class'
                    ),
                ));
            }

            if (is_tag()){
                $admin_bar->add_menu( array(
                    'id'    => 'toolbar-pagemanager',
                    'title' => 'Pagemanager Tag Page',
                    'href'  => admin_url() . 'admin.php?page=pagemanager&target=post_tag&case='.get_queried_object()->term_id,
                    'meta'  => array(
                        'title' => __('Pagemanager Category Page'),
                        //'target' => '_blank',
                        'class' => 'my_menu_item_class'
                    ),
                ));
                $admin_bar->add_menu( array(
                    'id'    => 'toolbar-pagemanager-post',
                    'parent' => 'toolbar-pagemanager',
                    'title' => 'Tag Page Default',
                    'href'  => admin_url() . 'admin.php?page=pagemanager&target=post_tag&cas=0',
                    'meta'  => array(
                        'title' => __('Category Default'),
                        //'target' => '_blank',
                        'class' => 'my_menu_item_class'
                    ),
                ));
            }
            if (is_search()){
                $admin_bar->add_menu( array(
                    'id'    => 'toolbar-pagemanager',
                    'title' => 'Pagemanager Search Page',
                    'href'  => admin_url() . 'admin.php?page=pagemanager&target=search&case=0',
                    'meta'  => array(
                        'title' => __('Pagemanager Search Page'),
                        //'target' => '_blank',
                        'class' => 'my_menu_item_class'
                    ),
                ));
            }
            if (is_404()){
                $admin_bar->add_menu( array(
                    'id'    => 'toolbar-pagemanager',
                    'title' => 'Pagemanager 404 Page',
                    'href'  => admin_url() . 'admin.php?page=pagemanager&target=search&case=0',
                    'meta'  => array(
                        'title' => __('Pagemanager 404 Page'),
                        //'target' => '_blank',
                        'class' => 'my_menu_item_class'
                    ),
                ));
            }

            $admin_bar->add_menu( array(
                'id'    => 'toolbar-pagemanager-header',
                'parent' => 'toolbar-pagemanager',
                'title' => 'Header',
                'href'  => admin_url() . 'admin.php?page=pagemanager&target=globalheader',
                'meta'  => array(
                    'title' => __('Header'),
                    //'target' => '_blank',
                    'class' => 'my_menu_item_class'
                ),
            ));
            $admin_bar->add_menu( array(
                'id'    => 'toolbar-pagemanager-footer',
                'parent' => 'toolbar-pagemanager',
                'title' => 'Footer',
                'href'  => admin_url() . 'admin.php?page=pagemanager&target=globalfooter',
                'meta'  => array(
                    'title' => __('Footer'),
                    //'target' => '_blank',
                    'class' => 'my_menu_item_class'
                ),
            ));


        } else {
            if (function_exists('get_current_screen')) {
                $screen = get_current_screen();
                if (in_array($screen->id, ['toplevel_page_pagemanager']))
                {
                    if (isset($_GET['backlink'])) {
                        $admin_bar->add_menu( array(
                            'id'    => 'toolbar-pagemanager',
                            'title' => 'Back to View',
                            'href'  => sanitize_url(urldecode($_GET['backlink'])),
                            'meta'  => array(
                                'title' => __('Pagemanager'),
                            ),
                        ));
                    }

                }
            }
        }
        return $this;
    }

    /**
     * adds the Link to the Menu
     * @return PageManagerAdmin
     */
    public function registerPagemanagerPage()
    {
        $pagemanagerSite = add_menu_page(
            'Page Manager', //page title
            'Page Manager', //menu title
            'pagemanager_edit', //capability rights
            'pagemanager', //menu_slug php file
            array(IndexController::getInstance(), 'manageAction'), //function
            PAGEMANAGER_PLUGIN_URL . 'images/pagemanager_icon_white24.png', //icon_url
            3 //position
        );

        add_submenu_page(
            'pagemanager', //parent_slug
            'Page Manager Settings', //page title
            'Settings', //menu title
            'pagemanager_settings', //capability rights
            'settings_pagemanager', //menu_slug php file
            array(IndexController::getInstance(), 'settingsAction')
        );

        add_action('admin_head-' . $pagemanagerSite, array($this, 'addScriptsAndStyles'));

        add_action('admin_enqueue_scripts', [$this, 'enqueueShortcodeHelperScript']);

        return $this;
    }

    /**
     *
     */
    public function ajaxHelperSearch()
    {
        if (false === headers_sent()) {
            header('Content-Type: text/html; charset=' . get_bloginfo('charset'));
        }
        $posttypes = array('post');



        $searchString = sanitize_title_for_query($_POST['q']);

        global $wpdb;

        $helper = isset($_POST['helper'])
            ? sanitize_key($_POST['helper'])
            : 'post';

        switch ($helper) {
            case 'post':
                if (isset($_POST['post-type']) && $_POST['post-type'] != 'undefined') {
                    $posttypes = array_map("sanitize_key", explode(",", $_POST['post-type']));
                }
                $sql = "SELECT ID, post_title, post_type
                            FROM ".$wpdb->base_prefix."posts
                            WHERE (post_title LIKE '%" . $searchString . "%' OR  ID = '" . $searchString . "')
                                AND post_type IN ('" . implode("','", $posttypes) . "')
                                AND post_status = 'publish'
                            ORDER BY ID DESC";
                break;
            case 'gallery':
                $sql = "SELECT ID, post_title, post_type
                            FROM ".$wpdb->base_prefix."posts
                            WHERE (post_title LIKE '%" . $searchString . "%' OR  ID = '" . $searchString . "')
                                AND post_type IN ('post')
                                AND post_status = 'publish'
                            ORDER BY post_type ASC, ID DESC";
                break;
            case 'term':
                $sql = "SELECT ".$wpdb->base_prefix."terms.term_id as ID, ".$wpdb->base_prefix."terms.name as post_title, ".$wpdb->base_prefix."term_taxonomy.taxonomy
                        FROM ".$wpdb->base_prefix."terms
                        JOIN ".$wpdb->base_prefix."term_taxonomy ON ".$wpdb->base_prefix."term_taxonomy.term_id = ".$wpdb->base_prefix."terms.term_id
                        WHERE ".$wpdb->base_prefix."terms.name LIKE '%" . $searchString . "%'
                        OR ".$wpdb->base_prefix."terms.term_id = '" . $searchString . "'
                        ORDER BY ID DESC";
                break;
        }
        $results = $wpdb->get_results($sql);

        $html = '<ul class="autosuggest">';
        foreach ($results as $result) {
            $htmlBracket = '';
            if (isset($result->taxonomy)) {
                $htmlBracket = $result->taxonomy == 'category' ? '(Category)' : " (Tag)";
            }
            if (isset($result->post_type)) {
                $htmlBracket = " (Post)";
            }
            if (isset($result->parentcategory)) {
                $htmlBracket = $result->parentcategory == 'en' ? ' (en)' : " (de)";
            }
            $html .= '<li data-id="' . $result->ID . '"><i class="fa fa-lg fa-plus-circle"></i>'
                . $result->post_title . $htmlBracket . '</li>';
        }
        if (count($results) < 1) {
            $html .= '<li>Keine Treffer</li>';
        }
        $html .= '</ul>';
        echo wp_kses_post($html);
        die();
    }

    public function addMetaBox()
    {
        // get all post_types
        $args = [
            'public' => true,
            'exclude_from_search' => false,
        ];
        $postTypes = get_post_types($args);

        $notThisTypes = ['attachment'];
        foreach ($notThisTypes as $notThisType) {
            unset($postTypes['$notThisType']);
        }

        foreach ($postTypes as $postType) {
            add_meta_box(
                'wp_editor_pmbox',
                'PageManager',
                array($this, 'callbackMetabox'),
                $postType,
                'side',
                'core'
            );
            add_meta_box(
                'wp_editor_pm_shortcode_generator',
                'Pagemanager Shortcode Helper',
                array($this, 'callbackShortcodeMetabox'),
                $postType,
                'normal',
                'low'
            );
        }
        return $this;
    }

    public function callbackMetabox()
    {
        $postID = isset($_GET['post'])
            ? sanitize_key($_GET['post'])
            : false;
        if (!$postID) {
            echo "Bitte erst speichern";
            return $this;
        }

        $post = get_post( $postID);
        $defaultLink = '/wp/wp-admin/admin.php?page=pagemanager&target=' . $post->post_type . '&case=0';
        $customLink = '/wp/wp-admin/admin.php?page=pagemanager&target=' . $post->post_type . '&case='.$postID;

        echo '<a href="'.esc_url($defaultLink).'" target="_blank">Default <i>' . esc_textarea($post->post_type) . '</i> settings</a>';
        echo '<hr/>';
        echo '<a href="'. esc_url($customLink).'" target="_blank">Special settings for this <i>' . esc_html($post->post_type) . '</i> only</i></a>';
        return $this;
    }

    public function enqueueShortcodeHelperScript($hook)
    {
        if (!in_array($hook, ['post.php','post-new.php'])) {
            return;
        }

        wp_register_style(
            'fa-style',
            PAGEMANAGER_PLUGIN_URL .'pagemanager/styles/font-awesome/css/font-awesome.min.css',
            'pagemanager',
            PAGEMANAGER_PLUGIN_VERSION
        );
        wp_enqueue_style('fa-style');

        wp_register_style(
            'pm-style',
            PAGEMANAGER_PLUGIN_URL .'pagemanager/styles/style.css',
            'pagemanager',
            PAGEMANAGER_PLUGIN_VERSION
        );
        wp_enqueue_style('pm-style');

        wp_enqueue_script(
            'pm-library-js',
            PAGEMANAGER_PLUGIN_URL .'pagemanager/js/pagemanager.js',
            'pagemanager',
            PAGEMANAGER_PLUGIN_VERSION
        );
        wp_enqueue_script(
            'pm-shortcode-js',
            PAGEMANAGER_PLUGIN_URL .'pagemanager/js/shortcode.js',
            'pagemanager',
            PAGEMANAGER_PLUGIN_VERSION
        );
    }

    public function callbackShortcodeMetabox()
    {
//        echo '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>';
        return;
    }

}
