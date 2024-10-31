<?php

namespace Pagemanager\Model;

class PageTypes
{
    /**
     * @var array
     */
    protected $collection = array();

    private static $instance = null;

    public $presetPageTypes = [
        ['globalheader', 'Global: Header'],
        ['globalfooter', 'Global: Footer'],
        ['index', 'Index Page']
    ];

    public $presetAdditionalPageTypes = [

        ['search', 'Search Page'],
        ['archive', 'Archive\'s Page'],
        ['404', '404 Page'],
        ['sidebar', 'Sidebar', 'pagetypes'],
    ];

    public $blackListMetaPageTypes = ['globalheader', 'globalfooter', 'sidebar'];

    private function __construct(){
    }


    private function addTaxoaddTaxonomyPageTypesnomyPageTypes()
    {

        $args = [
            'public' => true,
            'show_ui' => true
        ];
        $taxonomies = get_taxonomies($args, 'objects');

        foreach($taxonomies as $taxonomy) {

            $type = $taxonomy->name;
            $label = __($taxonomy->label." page", 'Pagemanager');
            $caseIdType = 'term';
            new \Pagemanager\Model\PageType($type, $label, $caseIdType);
        }
    }

    private function init()
    {
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            $instance = new static();
            $instance->init();
            self::$instance = $instance;
        }
        return self::$instance;
    }

    /**
     * @param $className
     */
    public function addPageType($className)
    {
        if ($className instanceof \Pagemanager\Model\PageType) {
            array_push($this->collection, $className);
        }
//        else {
//            $instance = PageType::getInstance($className);
//            if ($instance instanceof AbstractPageType) {
//                array_push($this->collection, $instance);
//            }
//        }
        return $this;
    }


    /**
     * @return array
     */
    public function getPageTypes()
    {
        return $this->collection;
    }

    /**
     * get all included page type options
     * @return array
     */
    public function getPresetPageTypes()
    {
        $list = array();
        foreach($this->presetPageTypes as $preset) {
            array_push($list, $preset);
        }

        $args = [
            'public' => true,
            'show_ui' => true
        ];
        $taxonomies = get_taxonomies($args, 'objects');

        foreach($taxonomies as $taxonomy) {
            $type = $taxonomy->name;
            $label = __($taxonomy->label." page", 'Pagemanager');
            $caseIdType = 'term';
            array_push($list, [$type, $label, $caseIdType]);
        }
        // get all post_types
        $args = [
            'public' => true,
            'exclude_from_search' => false,
        ];

        $postTypes = get_post_types($args, 'objects');

        $notThisTypes = ['attachment'];
        foreach ($notThisTypes as $notThisType) {
            unset($postTypes[$notThisType]);
        }

        foreach ($postTypes as $postType) {
            $type = $postType->name;
            $label = __($postType->label);
            $caseIdType = 'post';
            array_push($list, [$type, $label, $caseIdType]);
        }

        foreach($this->presetAdditionalPageTypes as $preset) {
            if (isset($preset[2]) && $preset[2] == 'pagetypes') {
                $metaTypes = array();
                foreach ($list as $item) {
                    if (!in_array($item[0],$this->blackListMetaPageTypes)) {
                        array_push($metaTypes, $item[0]);
                    }
                }
                $preset[2] = $metaTypes;
            }
            array_push($list, $preset);
        }

        return $list;
    }

    /**
     * get all activated Page Types with settings
     * @return array
     */
    public function getActivePresetPageTypes(){
        $list = array();
        $settings = get_option('pagemanager_settings', []);
        foreach ($this->getPresetPagetypes() as $preset){
            $name = $preset[0];
            if (isset($settings['pagetype'][$name])) {
                array_push($list, $preset);
            }
        }
        return $list;
    }
}
