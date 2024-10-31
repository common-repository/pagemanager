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

class DisplayPosts implements ApiInterface
{
    private $postTypes = [
        'post',
        'partial',
        'categorypage'
    ];

    public function check()
    {
        return true;
    }

    public function method()
    {
        return 'GET';
    }

    public function args()
    {
        return array(
            'ids'
        );
    }

    public function act($params)
    {
        global $wpdb;

        $ids = array();
        foreach (explode(",", $params['ids']) as $id) {
            if (is_int((int)$id)) {
                $ids[] = $id;
            }
        }

        if (count($ids)>0) {
            $sql = "SELECT ID, post_title, post_type
            FROM ".$wpdb->base_prefix."posts
            WHERE ID IN (" . implode(",", $ids) . ")
            AND post_type IN ('". implode("','", $this->postTypes)."')
            ORDER BY FIELD (`ID`, " . implode(', ', $ids) . " )
            ";
            $results = $wpdb->get_results($sql);
            return  $results;

        }
        return array('status' => 'nok', 'errormessage' => 'No value in param ids');
    }
}