<?php
/**
 * Description: PageManager Settings Model
 * Author: Carsten Kermer and Timor Kodal
 */

namespace Pagemanager\Model;


class Settings
{
    const ERROR_VERSION = "version";
    const TABLE_NAME = 'pagemanager_settings';

    const TABLE_STATUS_ENTRY_ACTIVE = "1";
    const TABLE_STATUS_ENTRY_HISTORY = "2";
    const TABLE_STATUS_ENTRY_REMOVED = "3";

    const PAGETYPE_GLOBALHEADER = 'globalheader';
    const PAGETYPE_GLOBALFOOTER = 'globalfooter';
    const PAGETYPE_INDEX = 'index';
    const PAGETYPE_TERM = 'term';
    const PAGETYPE_SINGLE = 'single';
    const PAGETYPE_CATEGORYPAGE = 'categorypage';
    const PAGETYPE_PAGE = 'page';
    const PAGETYPE_SEARCH = 'search';
    const PAGETYPE_AUTHOR = 'author';
    const PAGETYPE_SIDEBAR = 'sidebar';
    const PAGETYPE_ADVERTISING = 'advertising';
    const PAGETYPE_DEFAULT = 'default';

    public $pageList = array(
        self::PAGETYPE_GLOBALHEADER,
        self::PAGETYPE_GLOBALFOOTER,
        self::PAGETYPE_INDEX,
        self::PAGETYPE_TERM, //archive
        self::PAGETYPE_SINGLE,
        self::PAGETYPE_CATEGORYPAGE,
        self::PAGETYPE_AUTHOR,
        self::PAGETYPE_PAGE,
        self::PAGETYPE_SIDEBAR,
        self::PAGETYPE_SEARCH,
        self::PAGETYPE_ADVERTISING
    );

    public $pageCases = array(
        self::PAGETYPE_DEFAULT,
        self::PAGETYPE_INDEX,
        self::PAGETYPE_TERM, //archive
        self::PAGETYPE_SINGLE,
        self::PAGETYPE_AUTHOR,
        self::PAGETYPE_PAGE,
        self::PAGETYPE_SEARCH
    );

    public $metaPageList = array(
        self::PAGETYPE_SIDEBAR,
        self::PAGETYPE_GLOBALHEADER,
        self::PAGETYPE_GLOBALFOOTER
    );

    public $advertisingCaseList = array(
        'superbanner',
        'billboard',
        'medium_rectangle',
        'skyscraper',
        'rich_media'
    );

    protected $pageType;
    protected $pageId;
    protected $settings;

    /**
     * @var Settings
     */
    protected static $instance;

    public $version;
    public $success;
    public $errorMessage = '';

    /**
     * @return Settings
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Changes the status of all active settings with the page type and id to history
     * @param $pageType
     * @param $pageID
     * @return $this
     */
    protected function setHistoryStatus($pageType, $pageID)
    {
        global $wpdb;
        $result = $wpdb->update(
            self::TABLE_NAME,
            array('status' => self::TABLE_STATUS_ENTRY_HISTORY),
            array(
                'page_type' => $pageType,
                'page_id' => $pageID != 'default' ? $pageID : 0,
                'status' => self::TABLE_STATUS_ENTRY_ACTIVE,
            )
        );
        return $this;
    }

    /**
     * adds a setting to page if there are changes
     * @param $pageType
     * @param int $pageID
     * @param $setting
     * @return Settings
     */
    public function add($pageType, $pageID = 0, $setting = "")
    {
        global $wpdb;

        $pageID = $pageID != 'default'
            ? $pageID
            : 0;

        $setting_encoded = serialize($setting);

        $sql_check = "SELECT settings FROM `" . self::TABLE_NAME . "`" .
            "WHERE `page_id` = '" . $pageID . "' " .
            "AND `page_type` = '" . $pageType . "' " .
            "AND `status` = " . self::TABLE_STATUS_ENTRY_ACTIVE . " ";

        $result = $wpdb->get_results($sql_check);

        if (!isset($result[0]->settings) || md5($result[0]->settings) != md5($setting_encoded)) {
            $this->setHistoryStatus($pageType, $pageID);
            $currentUser = wp_get_current_user();
            $data = array(
                'page_type' => $pageType,
                'page_id' => $pageID,
                'settings' => $setting_encoded,
                'created_by' => $currentUser->get('ID'),
                'created_at' => strftime('%Y-%m-%d %H:%M:%S'),
                'status' => self::TABLE_STATUS_ENTRY_ACTIVE,
            );
            if (is_array($setting) && count($setting) > 0) {
                $wpdb->insert(self::TABLE_NAME, $data);
            }
            $this->success = true;
            return $this;
        }
        $this->success = false;
        return $this;
    }

    public function createTable()
    {
        global $wpdb;
        $sql = "
            CREATE TABLE IF NOT EXISTS `" . self::TABLE_NAME . "` (
              `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `page_type` varchar(64) NOT NULL DEFAULT '' COMMENT 'enum field (index,archive,single)',
              `page_id` bigint(20) unsigned DEFAULT NULL COMMENT 'term ID or post ID',
              `settings` longtext NOT NULL COMMENT 'json',
              `created_by` bigint(11) NOT NULL COMMENT '".$wpdb->base_prefix."user ID',
              `created_at` datetime NOT NULL,
              `status` int(4) NOT NULL COMMENT '1 - active, 2 - history, 3 - removed',
              PRIMARY KEY (`ID`),
              KEY `page_type` (`page_type`),
              KEY `page_id` (`page_id`),
              KEY `status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

        $wpdb->query($sql);

        $this->initializeSettings();
        return $this;
    }

    protected function initializeSettings()
    {
        $pageID = 0;
        $pageSetting = '';

        foreach ($this->pageList as $pageType) {
            $this->add($pageType, $pageID, $pageSetting);
        }
    }

    public function migrateSettings()
    {
        global $wpdb;

        $sql_check = "SELECT COUNT(id) as `count` FROM `" . self::TABLE_NAME . "`";
        $result = $wpdb->get_results($sql_check);

        if ($result[0]->count == "0") {
            //empty default settings
            $this->initializeSettings();

            $settings = get_option('pagemanager_settings');
            foreach ($settings as $pageType => $pageTypeSettings) {
                foreach ($pageTypeSettings as $pageID => $pageSetting) {
                    foreach ($pageSetting as $blockID => $blockAttr) {
                        foreach ($blockAttr['settings'] as $settingsTitle => $settingsValue) {
                            $pageSetting[$blockID]['settings'][substr($settingsTitle, 6)] = $settingsValue;
                            unset($pageSetting[$blockID]['settings'][$settingsTitle]);
                        }
                    }
                    $this->add($pageType, $pageID, $pageSetting);
                }
            }
        }
    }

    public function getCurrentPageSettings($context = false, $id = false)
    {
        $queriedObject = get_queried_object();
        if ($context === false && $id === false) {
            $context = 'index';
            $id = '';
            if (isset($queriedObject)) {
                if (strtoupper(get_class($queriedObject)) == 'WP_POST') {
                    $context = 'single';
                    $id = isset($queriedObject->ID) && $queriedObject->ID > 0 ? $queriedObject->ID : false;
                } elseif (isset($queriedObject->term_id)) {
                    $context = 'archive';
                    $id = $queriedObject->term_id;
                }
            }
            if (array_key_exists('search', $_REQUEST)) {
                $id = 'default';
                $context = 'search';
            }
        }
        $settings = $this->getSettings($context, $id);
        return $settings;
    }

    /**
     * @param $pageId
     * @return bool|int
     */
    protected function getPageId($pageId, array $pageList)
    {
        $array = array_flip($pageList);
        if (isset($array[$pageId])) {
            return (int) $array[$pageId];
        } else {
            return false;
        }
    }

    public function getSettings($pageType, $pageID = 0, $returnDefault = true)
    {
        global $wpdb;

        $pageID = $pageID == 'default'
            ? 0
            : $pageID;

        if (!is_numeric($pageID)) {
            if (in_array($pageType, $this->metaPageList)) {
                $pageID = $this->getPageId($pageID, $this->pageCases);
            }
            if ($pageType == self::PAGETYPE_ADVERTISING) {
                $pageID = $this->getPageId($pageID, $this->advertisingCaseList);
            }
        }

        $sql = "SELECT `page_id`, `settings` FROM `" . self::TABLE_NAME . "` " .
            "WHERE `page_id` IN ('" . $pageID . "'" . ($returnDefault ? ", '0'" : '') . ") " .
            "AND `page_type` = '" . $pageType . "' " .
            "AND `status` = " . self::TABLE_STATUS_ENTRY_ACTIVE . " " .
            ($returnDefault ? "AND `settings` NOT IN ('', 'N;', 's:0:\"\";') " : "") .
            "ORDER BY `page_id` DESC";

        $result = $wpdb->get_results($sql);
        $this->version = md5(isset($result[0]) ? $result[0]->settings : serialize(array()));

        $settings = array();
        if (isset($result[0])) {
            $settings = unserialize($result[0]->settings);
            if (!is_array($settings)) {
                $this->errorMessage = "Kann Setting nicht lesen<br/>".
                    "<textarea style=\"height: 100px;width: 400px;\">" . $result[0]->settings . "</textarea>";
                $settings = array();
            }
        }
        return $settings;
    }

    public function getSettingsIndex()
    {
        global $wpdb;
        $sql = "SELECT `page_type`, `page_id` FROM `" . self::TABLE_NAME . "` " .
            "WHERE  `status` = " . self::TABLE_STATUS_ENTRY_ACTIVE . "";
        $result = $wpdb->get_results($sql);
        $resultArray = array();
        foreach ($result as $resultRow) {
            $resultArray[$resultRow->page_type][] = $resultRow->page_id;
        }
        return $resultArray;
    }

    /**
     * @param $target
     * @param array $newSettings
     * @param string $version
     * @return $this
     */
    public function savePageSettings($target, $newSettings = array(), $version = '')
    {
        $allSettings = $this->getSettings($target[0], $target[1], false);

        $target[1] = $target[1] == 'default' || null
            ? 0
            : $target[1];

        if ($this->version != $version) {
            $this->success = false;
            $this->errorMessage = self::ERROR_VERSION;

            return $this;
        }

        $this->add($target[0], $target[1], $newSettings);

        $this->version = md5(serialize($newSettings));

        return $this;
    }

    /**
     * @param bool $target
     * @param bool $case
     * @return mixed
     */
    public static function exportSettings($target = false, $case = false)
    {
        global $wpdb;
        $sql = "SELECT * FROM `" . self::TABLE_NAME . "` " .
            "WHERE  `status` = " . self::TABLE_STATUS_ENTRY_ACTIVE . " ";
        if ($target != false) {
            $sql .= " AND `" . self::TABLE_NAME . "`.`page_type` = '" . $target . "' ";
        }
        if ($case != false) {
            $sql .= " AND   `" . self::TABLE_NAME . "`.`page_id` = '" . $case . "' ";
        }
        $result = $wpdb->get_results($sql);
        return $result;
    }

    public function getHistory($pageType, $pageID = 0)
    {
        global $wpdb;
        $sql = "SELECT `" . self::TABLE_NAME . "`.`ID`," .
            "`" . self::TABLE_NAME . "`.`status`, `" . self::TABLE_NAME
            . "`.`created_at`, `".$wpdb->base_prefix."users`.`user_nicename` " .
            "FROM `" . self::TABLE_NAME . "` " .
            "LEFT JOIN `".$wpdb->base_prefix."users` ON `".$wpdb->base_prefix."users`.`ID`=`" . self::TABLE_NAME . "`.`created_by` " .
            "WHERE  `" . self::TABLE_NAME . "`.`page_id` = '" . $pageID . "' " .
            "AND `" . self::TABLE_NAME . "`.`page_type` = '" . $pageType . "' " .
            "ORDER BY `" . self::TABLE_NAME . "`.`status` ASC, `" . self::TABLE_NAME . "`.`created_at` DESC " .
            "LIMIT 0, 20";
        $result = $wpdb->get_results($sql);
        return $result;
    }

    public static function getPageList()
    {
//        $instance = self::getInstance();
//        return $instance->pageList;
        $pageList = array(
            array(
                'name' => 'index',
                'label' => __('Homepage', 'pagemanager'),
                'options' => false
            ),
            array(
                'name' => 'term',
                'label' => __('Category/Tag Page', 'pagemanager'),
                'options' => 'WP_Term'
            ),
            array(
                'name' => 'single',
                'label' => __('(Single-)Post Page', 'pagemanager'),
                'options' => 'WP_Post'
            ),
            array(
                'name' => 'page',
                'label' => __('Page', 'pagemanager'),
                'options' => 'WP_Post'
            ),
            array(
                'name' => 'globalheader',
                'label' => __('Global Header', 'pagemanager'),
                'options' => self::getPageCases(),
            ),
            array(
                'name' => 'globalfooter',
                'label' => __('Global Footer', 'pagemanager'),
                'options' => self::getPageCases(),
            )
        );
        return $pageList;
    }
    public static function getPageCases()
    {
        $instance = self::getInstance();
        return $instance->pageCases;
    }
    public static function getAdCases()
    {
        $instance = self::getInstance();
        return $instance->advertisingCaseList;
    }
    public static function getMetaPageList()
    {
        $instance = self::getInstance();
        return $instance->metaPageList;
    }

    public function reactivate($pageType, $pageID = 0, $settingsId = false)
    {
        if ($settingsId == false) {
            return false;
        }
        global $wpdb;
        $sql = "UPDATE `" . self::TABLE_NAME . "` SET `status` = " . self::TABLE_STATUS_ENTRY_HISTORY . " " .
            "WHERE  `status` = " . self::TABLE_STATUS_ENTRY_ACTIVE . " " .
            "AND `" . self::TABLE_NAME . "`.`page_id` = '" . $pageID . "' " .
            "AND `" . self::TABLE_NAME . "`.`page_type` = '" . $pageType . "' ";
        $wpdb->query($sql);
        $sql = "UPDATE `" . self::TABLE_NAME . "` SET `status` = " . self::TABLE_STATUS_ENTRY_ACTIVE . " " .
            "WHERE  `status` = " . self::TABLE_STATUS_ENTRY_HISTORY . " " .
            "AND `" . self::TABLE_NAME . "`.`ID` = '" . $settingsId . "' ";
        $wpdb->query($sql);
    }

    /**
     * @param $param
     * @return array
     *
     * Sanitizes PageBlockSettings by BlockType
     */
    public  function sanitizeSettings($param)
    {
        $blockTypes = \Pagemanager\Model\BlockTypes::getInstance();


        $sanitizedSettings = [];
        foreach ($param as $blockSetting) {
            foreach ($blockTypes->getAllTypes() as $blockType) {
                if ($blockSetting['type'] == $blockType->name) {
                    $sanitizedBlockSetting = [];

                    foreach($blockType->attributes as $attribute){
                        if (isset($blockSetting['settings'][$attribute['name']])) {
                            if (in_array($attribute['name'], ['html','code'])) {
                                $sanitizedBlockSetting[$attribute['name']] = wp_kses_post($blockSetting['settings'][$attribute['name']]);
                            } else {
                                $sanitizedBlockSetting[$attribute['name']] = sanitize_text_field($blockSetting['settings'][$attribute['name']]);
                            }
                        }
                    }
                    $sanitizedSettings[]= array(
                        'type' => $blockType->name,
                        'settings' => $sanitizedBlockSetting
                    );
                }
            }
        }

        return $sanitizedSettings;
    }

    /**
     * @param $attr
     * @param $param
     * @return array|null|string
     *
     * Sanitizes input params for Settings
     */
    public function sanitizeParam($attr, $param)
    {
        $sanitizedParam = null;
        switch($attr){
            case 'case':
                if (isset($param))
                    return sanitize_key($param);
                break;
            case 'pagetype':
                if (isset($param))
                    return sanitize_key($param);
                break;
            case 'settings':
                if (is_array($param))
                    return $this->sanitizeSettings($param);
                else
                    return [];
                break;
            case 'version':
                return sanitize_key($param);
                break;
        }

        return $sanitizedParam;
    }

}
