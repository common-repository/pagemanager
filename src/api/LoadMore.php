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
use Pagemanager\PageBlockFactory;

class LoadMore implements ApiInterface
{

    public function check()
    {
        return true;
    }

    public function method()
    {
        return 'POST';
    }

    public function args()
    {
        return array(
            'quantity',
            'layout',
            'termsExclude',
            'terms',
            'titleLink',
            'title',
            'postType',
            'type',
            'excludePosts',
            'offset',
            'pagination'
        );
    }

    public function act($params)
    {
        $blockSettings = array(
            'type' => $params['type'],
        );

        foreach ($params as $paramName => $paramValue) {
            $paramName = preg_replace_callback('/([A-Z])/', create_function('$matches','return \'-\' . strtolower($matches[1]);'), $paramName);
            $blockSettings['settings'][$paramName] = $paramValue;
        }

        status_header(200);
        $pageblock = PageBlockFactory::render($blockSettings);

        exit;
    }
}