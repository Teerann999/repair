<?php
/**
 * @filesource modules/repair/models/detail.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Repair\Detail;

use \Kotchasan\Http\Request;
use \Gcms\Login;
use \Kotchasan\Language;

/**
 * รับงานซ่อม
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  /**
   * อ่านรายละเอียดการทำรายการจาก $id
   *
   * @param int $id
   * @return object
   */
  public static function get($id)
  {
    $model = new static;
    $sql = $model->db()->createQuery()
      ->select('R.*', 'U.name', 'U.phone', 'U.address', 'U.zipcode', 'U.provinceID', 'V.equipment', 'V.serial', 'S.status', 'S.comment', 'S.cost', 'S.operator_id', 'S.id status_id')
      ->from('repair R')
      ->join('repair_status S', 'INNER', array('S.repair_id', 'R.id'))
      ->join('inventory V', 'INNER', array('V.id', 'R.inventory_id'))
      ->join('user U', 'INNER', array('U.id', 'R.customer_id'))
      ->where(array('R.id', $id))
      ->order('S.id DESC');
    return $model->db()->createQuery()
        ->from(array($sql, 'Q'))
        ->groupBy('Q.id')
        ->first();
  }

  /**
   *
   * @param type $id
   */
  public static function getAllStatus($id)
  {
    $model = new static;
    return $model->db()->createQuery()
        ->select('U.name', 'S.status', 'S.cost', 'S.create_date', 'S.comment')
        ->from('repair_status S')
        ->join('user U', 'LEFT', array('U.id', 'S.operator_id'))
        ->where(array('S.repair_id', $id))
        ->order('S.id')
        ->toArray()
        ->execute();
  }

  /**
   * รับค่า submit จากหน้าดูรายละเอียดการซ่อม
   *
   * @param Request $request
   */
  public function submit(Request $request)
  {
    $ret = array();
    // session, token, can_received_repair, can_repair
    if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
      if ($login['username'] != 'demo' && Login::checkPermission($login, array('can_received_repair', 'repair'))) {
        print_r($_POST);
      }
    }
    if (empty($ret)) {
      $ret['alert'] = Language::get('Unable to complete the transaction');
    }
    // คืนค่าเป็น JSON
    echo json_encode($ret);
  }
}
