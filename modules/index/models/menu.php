<?php
/**
 * @filesource modules/index/models/menu.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Menu;

use \Gcms\Login;
use \Kotchasan\Language;

/**
 * รายการเมนู
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model
{

  /**
   * รายการเมนู
   *
   * @param array $login
   * @return array
   */
  public static function getMenus($login)
  {
    $menus = array(
      'home' => array(
        'text' => '{LNG_Home}',
        'url' => 'index.php?module=home'
      ),
      'repair' => array(
        'text' => '{LNG_Repair Jobs}',
        'submenus' => array(
          'get' => array(
            'text' => '{LNG_Get a repair}',
            'url' => 'index.php?module=repair-receive'
          ),
          array(
            'text' => '{LNG_Repair list}',
            'url' => 'index.php?module=repair-setup'
          ),
        )
      ),
      'member' => array(
        'text' => '{LNG_Users}',
        'submenus' => array(
          array(
            'text' => '{LNG_Member list}',
            'url' => 'index.php?module=member'
          ),
          array(
            'text' => '{LNG_Register}',
            'url' => 'index.php?module=register'
          )
        ),
      ),
      'settings' => array(
        'text' => '{LNG_Settings}',
        'submenus' => array(
          array(
            'text' => '{LNG_Site settings}',
            'url' => 'index.php?module=system'
          ),
          array(
            'text' => '{LNG_Member status}',
            'url' => 'index.php?module=memberstatus'
          ),
          array(
            'text' => '{LNG_Email settings}',
            'url' => 'index.php?module=mailserver'
          ),
        ),
      ),
      'signout' => array(
        'text' => '{LNG_Sign Out}',
        'url' => 'index.php?action=logout'
      ),
    );
    // สามารถตั้งค่าระบบได้
    if (!Login::checkPermission($login, 'can_config')) {
      unset($menus['settings']);
    } else {
      $menus['settings']['submenus'][] = array(
        'text' => '{LNG_Repair settings}',
        'url' => 'index.php?module=repair-settings'
      );
      foreach (Language::get('REPAIR_CATEGORIES') as $key => $value) {
        $menus['settings']['submenus'][] = array(
          'text' => $value,
          'url' => 'index.php?module=repair-category&amp;typ='.$key
        );
      }
    }
    if (!Login::checkPermission($login, 'can_received_repair')) {
      unset($menus['repair']['submenus']['get']);
    }
    // ไม่ใช่แอดมิน
    if (!Login::isAdmin()) {
      unset($menus['member']);
    }
    return $menus;
  }
}
