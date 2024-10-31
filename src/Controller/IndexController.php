<?php

namespace Pagemanager\Controller;

use Pagemanager\Model\BlockTypes;
use Pagemanager\Model\PageType;
use Pagemanager\Model\PageType\AbstractPageType;
use Pagemanager\Model\PageTypes;
use Pagemanager\Model\Settings;

class IndexController
{
    /**
     * @var IndexController
     */
    protected static $instance;

    /**
     * Constructor
     */
    private function __construct()
    {
    }

    /**
     * @return IndexController
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return $this
     */
    public function manageAction()
    {
        wp_enqueue_script('pm-js',PAGEMANAGER_PLUGIN_URL. 'js/pagemanager.js', array(),PAGEMANAGER_PLUGIN_VERSION);
        wp_enqueue_script('pm-manage-js', PAGEMANAGER_PLUGIN_URL .'js/script.js', array(),PAGEMANAGER_PLUGIN_VERSION);

        $settings = get_option('pagemanager_settings', []);
        if (!isset($settings['pagetype'])) {
            $this->settingsAction();
            return $this;
        }

        $blockTypesModel = BlockTypes::getInstance();
        $blocksConfig = $blockTypesModel->getAllTypes();

        if (!isset($settings['search'])) {
            $settings['search'] = array('default' => array());
        }


        $blocksPage = isset($_GET["target"]) && $_GET["target"] != '' ? sanitize_key($_GET["target"]) : 'index';
        $blocksPageCase = isset($_GET["case"]) && $_GET["case"] != '' ? sanitize_key($_GET["case"]) : '0';

        $settingsInst = Settings::getInstance();

        if (isset($_GET['restore'])) {
            $restoreId = sanitize_key(urldecode(base64_decode($_GET['restore'])));
            $settingsInst->reactivate($blocksPage, $blocksPageCase, $restoreId);
        }

        $settings = $settingsInst->getSettings($blocksPage, $blocksPageCase, false);

        $pageTypes = PageTypes::getInstance();
        $pageList = $pageTypes->getPageTypes();

        $settingsIndex = $settingsInst->getSettingsIndex();
        $pageTypeList = array();
        foreach ($pageList as $pageType) {
            if ($pageType->isType($blocksPage)) {
                $pageTypeTitle = $pageType->getLabel();
                $pageCaseTitle = $pageType->getCaseIdInfo($blocksPageCase);
            }
            if ($pageType instanceof AbstractPageType && is_array($pageType->caseIdSource)) {
                foreach ($pageType->caseIdSource as $id => $type) {
                    $settingsIndex[$pageType->getType()][] = $id;
                }
            } else {
                $settingsIndex[$pageType->getType()][] = 0;
            }
            $pageTypeList[] = array(
                "type" => $pageType->getType(),
                "label" => $pageType->getLabel(),
                "caseIdSource" => $pageType->getCaseIdSource(),
            );
        }

        $settingsIdInfo = array();
        foreach ($settingsIndex as $target => $cases) {
            foreach ($pageList as $pageType) {
                if ($pageType instanceof PageType && $pageType->isType($target)) {
                    foreach ($cases as $case) {
                        $settingsIdInfo[$target][$case] = $pageType->getCaseIdInfo($case);
                    }
                    break;
                }
            }
        }

        $hash = $settingsInst->version;
        $blockSettings = $settings;

        $blockHistory = $settingsInst->getHistory($blocksPage, $blocksPageCase);

        include_once((plugin_dir_path(__FILE__) . '../views/manage.php'));

        return $this;
    }

    public function settingsAction()
    {
        $list = [];
        $pageTypes = PageTypes::getInstance();
        $list = $pageTypes->getPresetPageTypes();

        if (isset($_POST['submit'])) {
            $settings = array();
            foreach($list as $pagetype) {
                if (isset($_POST['pagetype'][$pagetype[0]])) {
                    $settings['pagetype'][$pagetype[0]] = sanitize_key($_POST['pagetype'][$pagetype[0]]);
                }
            }
            update_option(
                'pagemanager_settings',
                $settings,
                'no'
            );
        } else {
            $settings = get_option('pagemanager_settings', []);
            if (!isset($settings['pagetype'])){
                $errormessage =  __("Welcome to the Pagemanager. Please choose your page types first.", "pagemanager");
            }
        }

        include_once((plugin_dir_path(__FILE__) . '../views/settings.php'));
    }
}
