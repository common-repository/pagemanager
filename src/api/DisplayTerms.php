<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 09.02.17
 * Time: 14:42
 */

namespace Pagemanager\api;

use Pagemanager\ApiInterface;

class DisplayTerms implements ApiInterface
{
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
            $sql = "SELECT te.term_id, te.`name`,ct.taxonomy
            FROM ".$wpdb->base_prefix."terms te
			JOIN ".$wpdb->base_prefix."term_taxonomy ct
            	ON (te.term_id=ct.term_id)
            WHERE te.term_id IN (" . implode(",", $ids) . ")
            ORDER BY te.term_id DESC";
            $results = $wpdb->get_results($sql);
            return  $results;

        }
        return array('status' => 'nok', 'errormessage' => 'No value in param ids');
    }
}