<?php
namespace PhpRbac;

use \Jf;

/**
 * @file
 * Provides NIST Level 2 Standard Role Based Access Control functionality
 *
 * @defgroup phprbac Rbac Functionality
 * @{
 * Documentation for all PhpRbac related functionality.
 */
class Rbac
{
    public function __construct($config, $unit_test = '')
    {
        if ((string) $unit_test === 'unit_test') {
            $host=$config['host'];
            $user=$config['user'];
            $pass=$config['pass'];
            $dbname=$config['dbname'];
            
            if (!key_exists('adapter', $config)) {
                $adapter="pdo_mysql";
            } else {
                $adapter = $config['adapter'];
            }
            
            if (!key_exists('tablePrefix', $config)) {
                $tablePrefix = "";
            } else {
                $tablePrefix = $config['tablePrefix'];
            }
        } else {
            require_once dirname(dirname(__DIR__)) . '/database/database.config';
        }

        require_once 'core/lib/Jf.php';

        $this->Permissions = Jf::$Rbac->Permissions;
        $this->Roles = Jf::$Rbac->Roles;
        $this->Users = Jf::$Rbac->Users;
    }

    public function assign($role, $permission)
    {
        return Jf::$Rbac->assign($role, $permission);
    }

    public function check($permission, $user_id)
    {
        return Jf::$Rbac->check($permission, $user_id);
    }

    public function enforce($permission, $user_id)
    {
        return Jf::$Rbac->enforce($permission, $user_id);
    }

    public function reset($ensure = false)
    {
        return Jf::$Rbac->reset($ensure);
    }

    public function tablePrefix()
    {
        return Jf::$Rbac->tablePrefix();
    }
}

/** @} */ // End group phprbac */
