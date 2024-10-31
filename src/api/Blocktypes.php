<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 09.02.17
 * Time: 14:42
 */

namespace Pagemanager\api;

use Pagemanager\ApiInterface;
use Pagemanager\Model\BlockTypes as BlocktypeModel;

class Blocktypes implements ApiInterface
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
        return [];
    }

    public function act($params)
    {
        $blockTypesModel = BlocktypeModel::getInstance();
        $blocksConfig = $blockTypesModel->getAllTypes();

        return $blocksConfig;

        status_header(200);
        echo wp_json_encode($blocksConfig);
        exit;
    }
}