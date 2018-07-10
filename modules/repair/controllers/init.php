<?php
/**
 * @filesource modules/repair/controllers/init.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Init;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * Init Module.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\KBase
{
    /**
     * ฟังก์ชั่นเริ่มต้นการทำงานของโมดูลที่ติดตั้ง
     * และจัดการเมนูของโมดูล.
     *
     * @param Request                $request
     * @param \Index\Menu\Controller $menu
     */
    public static function execute(Request $request, $menu, $login)
    {
        $submenus = array();
        if (Login::checkPermission($login, 'can_received_repair')) {
            $submenus[] = array(
                'text' => '{LNG_Get a repair}',
                'url' => 'index.php?module=repair-receive',
            );
        }
        $submenus[] = array(
            'text' => '{LNG_Repair list}',
            'url' => 'index.php?module=repair-setup',
        );
        // repair module
        $menu->addTopLvlMenu('repair', '{LNG_Repair Jobs}', null, $submenus, 'member');
        // ตั้งค่าโมดูล
        $menu->add('settings', '{LNG_Repair settings}', 'index.php?module=repair-settings');
        foreach (Language::get('REPAIR_CATEGORIES') as $key => $value) {
            $menu->add('settings', $value, 'index.php?module=repair-category&amp;typ='.$key);
        }
    }

    /**
     * รายการ permission ของโมดูล.
     *
     * @param array $permissions
     *
     * @return array
     */
    public static function updatePermissions($permissions)
    {
        $permissions['can_received_repair'] = '{LNG_Can get the repair}';
        $permissions['can_repair'] = '{LNG_Repairman}';

        return $permissions;
    }
}
