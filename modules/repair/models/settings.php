<?php
/**
 * @filesource modules/repair/models/settings.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Repair\Settings;

use \Kotchasan\Http\Request;
use \Gcms\Login;
use \Kotchasan\Language;
use \Kotchasan\Config;

/**
 * module=repair-settings
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\KBase
{

  /**
   * บันทึกการตั้งค่าโมดูล
   *
   * @param Request $request
   */
  public function submit(Request $request)
  {
    $ret = array();
    // session, token, can_config
    if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
      if ($login['username'] != 'demo' && Login::checkPermission($login, 'can_config')) {
        // โหลด config
        $config = Config::load(ROOT_PATH.'settings/config.php');
        // รับค่าจากการ POST
        $config->company_name = $request->post('company_name')->topic();
        $config->phone = $request->post('phone')->topic();
        $config->address = $request->post('address')->topic();
        $config->currency_unit = $request->post('currency_unit')->topic();
        // save config
        if (Config::save($config, ROOT_PATH.'settings/config.php')) {
          // คืนค่า
          $ret['alert'] = Language::get('Saved successfully');
          $ret['location'] = 'reload';
          // เคลียร์
          $request->removeToken();
        } else {
          // ไม่สามารถบันทึก config ได้
          $ret['alert'] = sprintf(Language::get('File %s cannot be created or is read-only.'), 'settings/config.php');
        }
      }
    }
    if (empty($ret)) {
      $ret['alert'] = Language::get('Unable to complete the transaction');
    }
    // คืนค่าเป็น JSON
    echo json_encode($ret);
  }
}
