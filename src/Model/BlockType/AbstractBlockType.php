<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 19.11.15
 * Time: 17:55
 */

namespace  Pagemanager\Model\BlockType;

use Pagemanager\PageManager;

abstract class AbstractBlockType
{
    public $name;
    public $label;
    public $icon;
    public $style;
    public $attributes = array();
    public $themePath;

    /**
     *
     */
    public function __construct()
    {
        $this->init();
    }


    protected function init()
    {
        $this->attributes = $this->setAttributes();
        $this->name = $this->setName();
        $this->label = $this->setLabel();
        $this->icon = $this->setIcon();
        $this->style = $this->setStyle();
        $this->themePath = $this->getThemePath();
    }

    /**
     * @return string
     */
    protected function getThemePath()
    {
        $currentThemePath = get_stylesheet_directory();
        //TODO: Licencing Code

        if (is_dir($currentThemePath . '/pageblock')){
            if (pm_fs()->can_use_premium_code())
                return $currentThemePath;
        }
        return PAGEMANAGER_PLUGIN_PATH . 'src/Theme';
    }

    abstract public function setAttributes();

    abstract public function setName();

    abstract public function setLabel();

    abstract public function setIcon();

    public function setStyle()
    {
        $this->style = '';
    }


    protected function getPageblockTemplateSettings($blockTypesFilter = "selectedposts"){
        if (!isset($this->themePath) OR is_null($this->themePath)) {
            $this->themePath = $this->getThemePath();
        }
        $pageblockTemplates = $this->getFiles($this->themePath.'/pageblock/');

        $templates = array();
        $options = array();

        foreach ($pageblockTemplates as $file) {
            $source = file_get_contents( $file );

            $tokens = token_get_all( $source );
            $comment = array(
                T_COMMENT,      // All comments since PHP5
                // T_ML_COMMENT,   // Multiline comments PHP4 only
                // T_DOC_COMMENT   // PHPDoc comments
            );
            foreach( $tokens as $token ) {
                if( !in_array($token[0], $comment) )
                    continue;
                // Do something with the comment
                $settings = explode("\n", $token[1]);
                array_shift($settings);
                array_pop($settings);
                $settingSet = [];
                foreach ($settings as $settingData){
                    $parts = explode (": ", $settingData);
                    $settingSet[$parts[0]] = is_numeric($parts[1]) ? (int)$parts[1] : $parts[1]  ;
                    ;
                }

                $blocktypeSettings = array();
                foreach (explode(",", $settingSet['PageblockTemplateBlocktype']) as $blocktype) {
                    $blocktypeSettings[] = strtolower(str_replace(" ","", $blocktype));
                }

                if (in_array($blockTypesFilter, $blocktypeSettings)) {
                    $templates[] = [
                        'file' => $file,
                        'settings' => $settingSet
                    ];
                    $option = array(
                        'value' => $settingSet['PageblockTemplateValue'],
                        //'<i class="layout layout-lead-five"></i>'
                        'icon'  => isset($settingSet['PageblockTemplateIcon'])
                            ? '<i class="layout '. $settingSet['PageblockTemplateIcon'] .'"></i>'
                            //
                            : 'layout-'.$settingSet['PageblockTemplateValue'],
                        'label' => $settingSet['PageblockTemplateLabel'],
                        'sort' => $settingSet['LayoutSort']/1,
                    );
                    if (isset($settingSet['PageblockTemplateQuantity']) && $settingSet['PageblockTemplateQuantity']/1 ==$settingSet['PageblockTemplateQuantity']) {
                        $option['quantity'] = $settingSet['PageblockTemplateQuantity'];
                    }
                    $options[] = $option;
                }
            }
        }
        $columns = array_column($options, 'sort');
        array_multisort($columns, SORT_ASC, $options);

        return $options;
    }

    protected function getFiles($dirPath){
        foreach (scandir($dirPath) as $entry){
            if (is_file($dirPath.$entry)){
                $arrFiles[] = $dirPath.$entry;
            }
        }
        return $arrFiles;
    }
}
