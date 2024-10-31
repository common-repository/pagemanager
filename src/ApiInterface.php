<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 11.05.16
 * Time: 11:43
 */

namespace Pagemanager;

interface ApiInterface
{
    public function check();
    public function args();
    public function act($params);
    public function method();
}
