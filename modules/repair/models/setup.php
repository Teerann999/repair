<?php
/**
 * @filesource modules/repair/models/setup.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Repair\Setup;

use \Kotchasan\Http\Request;
use \Gcms\Login;
use \Kotchasan\Language;

/**
 * โมเดลสำหรับ (setup.php)
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  /**
   * Query ข้อมูลสำหรับส่งให้กับ DataTable
   *
   * @return /static
   */
  public static function toDataTable()
  {
    $model = new static;
    $sql1 = $model->db()->createQuery()
      ->select('R.id', 'R.job_id', 'U.name', 'U.phone', 'V.equipment', 'R.create_date', 'R.appointment_date', 'S.operator_id', 'S.status')
      ->from('repair R')
      ->join('repair_status S', 'INNER', array('S.repair_id', 'R.id'))
      ->join('inventory V', 'INNER', array('V.id', 'R.inventory_id'))
      ->join('user U', 'LEFT', array('U.id', 'R.customer_id'))
      ->order('S.id DESC');
    return $model->db()->createQuery()
        ->select()
        ->from(array($sql1, 'Q1'))
        ->groupBy('Q1.id');
  }

  /**
   * รับค่าจาก action
   *
   * @param Request $request
   */
  public function action(Request $request)
  {
    $ret = array();
    // session, referer, can_received_repair
    if ($request->initSession() && $request->isReferer() && $login = Login::isMember()) {
      if ($login['username'] != 'demo') {
        // รับค่าจากการ POST
        $action = $request->post('action')->toString();
        // id ที่ส่งมา
        if (preg_match_all('/,?([0-9]+),?/', $request->post('id')->toString(), $match)) {
          // Model
          $model = new \Kotchasan\Model;
          // ตาราง
          $table = $model->getTableName('repair');
          if ($action === 'delete' && Login::checkPermission($login, 'can_received_repair')) {
            // ลบรายการสั่งซ่อม
            $model->db()->delete($table, array('id', $match[1]), 0);
            // reload
            $ret['location'] = 'reload';
          } elseif ($action === 'status' && Login::checkPermission($login, array('can_received_repair', 'can_repair'))) {
            // อ่านข้อมูลรายการที่ต้องการ
            $index = \Repair\Detail\Model::get($request->post('id')->toInt());
            if ($index) {
              $ret['modal'] = Language::trans(createClass('Repair\Action\View')->render($index, $login));
            }
          }
        }
      }
    }
    if (empty($ret)) {
      $ret['alert'] = Language::get('Unable to complete the transaction');
    }
    // คืนค่า JSON
    echo json_encode($ret);
  }
}
