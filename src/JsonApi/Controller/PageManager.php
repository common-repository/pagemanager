<?php
// @codingStandardsIgnoreFile
/*
Controller name: PageManager
Controller description: used by AS PageManager Plugin
*/

use Pagemanager\Model\Settings;

class JSON_API_PageManager_Controller
{
    /**
     * save pagemanager settings for home page
     *
     * @return array|bool
     */
    public function savePageSetting()
    {
        //TODO: find right user rights
        if (!current_user_can('publish_posts')) {
            return false;
        }
        global $json_api;
        $target = $json_api->query->target;
        $targetCase = $json_api->query->targetcase;
        $settings = ($json_api->query->settings);
        $version = ($json_api->query->version);

        $settingsInst = Settings::getInstance();
        $result = $settingsInst->savePageSettings(array($target, $targetCase), $settings, $version);

        return array(
            'success' => $result->success,
            'errormessage' => $result->errorMessage,
            'currentVersion' => $result->version
        );
    }

    /**
     * save pagemanager settings for home page
     *
     * @return array|bool
     */
    public function savePageSettingv2()
    {
        //TODO: find right user rights
        if (!current_user_can('publish_posts')) {
            return false;
        }
        global $json_api;
        $target = $json_api->query->target;
        $targetCase = $json_api->query->targetcase;
        $settings = ($json_api->query->settings);
        $version = ($json_api->query->version);

        $settingsInst = Settings::getInstance();
        $result = $settingsInst->savePageSettings(array($target, $targetCase), $settings, $version);

        return array(
            'success' => $result->success,
            'errormessage' => $result->errorMessage,
            'currentVersion' => $result->version
        );
    }

    /**
     * post title information for pagemanager
     * @return array
     */
    public function displayposts(){
        global $json_api, $wpdb;

        $sql = $wpdb->prepare( "SELECT ID, post_title
            FROM ".$wpdb->base_prefix."posts
            WHERE ID IN (%s)
            AND post_type IN ('post')
            ORDER BY ID DESC", $json_api->query->ids );
        $results = $wpdb->get_results($sql);
        return array('status' => 'ok', 'data' => $results);
    }

    /**
     * term title information for pagemanager
     * @return array
     */
    public function displayterms(){
        global $json_api, $wpdb;

        $sql = $wpdb->prepare("SELECT term_id, name
            FROM ".$wpdb->base_prefix."terms
            WHERE term_id IN (%s)
            ORDER BY term_id DESC", $json_api->query->ids);
        $results = $wpdb->get_results($sql);
        return array('status' => 'ok', 'data' => $results);
    }

    public function loadmore()
    {
        global $json_api;

        $args = $json_api->query->data;

        $blockSettings = array(
          'type' => $args['type'],
          'settings' => $args
        );
        $pageblock = \Pagemanager\PageBlockFactory::render($blockSettings);
        exit;
    }


}
