<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 09.02.17
 * Time: 14:42
 */

namespace Pagemanager\api;

use Pagemanager\ApiInterface;
use Pagemanager\Model\Settings;

class SaveSettings implements ApiInterface
{
    protected $pagetype = null;

    protected $caseId = null;

    protected $settings = [];

    protected $version = null;


    public function check()
    {
        return current_user_can('publish_posts');
    }

    public function method()
    {
        return 'POST';
    }

    public function args()
    {
        // TODO: Implement args() method.
        return array(
            'pagetype',
            'case',
            'settings',
            'version'
        );
    }


    public function act($params)
    {
        $settingsInst = Settings::getInstance();

        $this->pageType = $settingsInst->sanitizeParam('pagetype', $params['pagetype'] );
        $this->caseId = $settingsInst->sanitizeParam('case', $params['case'] );
        $this->settings = $settingsInst->sanitizeParam('settings', $params['settings'] );
        $this->version = $settingsInst->sanitizeParam('version', $params['version'] );

        $result = $settingsInst->savePageSettings(array($this->pageType, $this->caseId), $this->settings, $this->version);

        return array(
                'success' => $result->success,
                'errormessage' => $result->errorMessage,
                'currentVersion' => $result->version
        );
    }
}