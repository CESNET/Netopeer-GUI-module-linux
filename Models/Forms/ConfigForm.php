<?php
/**
 * Created by PhpStorm.
 * User: Honza
 * Date: 6.5.2016
 * Time: 17:26
 */

namespace FIT\Bundle\ModuleLinuxBundle\Models\Forms;


class ConfigForm
{
    protected static function getNodeValue($node, $defaultValue = null) {
        return isset($node) && !empty($node) ? (string)$node : $defaultValue;
    }
}