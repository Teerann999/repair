<?php
/**
 * @filesource modules/repair/models/autocomplete.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Repair\Autocomplete;

use \Kotchasan\Http\Request;
use \Gcms\Login;

/**
 * ค้นหาสมาชิก สำหรับ autocomplete
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  /**
   * ค้นหาประวัติ
   * คืนค่าเป็น JSON
   *
   * @param Request $request
   */
  public function findCustomer(Request $request)
  {
    if ($request->initSession() && $request->isReferer() && Login::isMember()) {
      $search = $request->post('name')->topic();
      if ($search != '') {
        $where = array('name', 'LIKE', "%$search%");
        $order = 'name';
      } else {
        $search = $request->post('phone')->topic();
        if ($search != '') {
          $where = array('phone', 'LIKE', "%$search%");
          $order = 'phone';
        }
      }
      if (isset($where)) {
        $result = $this->db()->createQuery()
          ->select('id customer_id', 'name', 'phone', 'address', 'provinceID', 'zipcode')
          ->from('user')
          ->where($where)
          ->order($order)
          ->limit($request->post('count')->toInt())
          ->toArray()
          ->execute();
        if (!empty($result)) {
          // คืนค่า JSON
          echo json_encode($result);
        }
      }
    }
  }

  /**
   * ค้นหาประวัติการซ่อมสินค้า สำหรับ autocomplete
   * คืนค่าเป็น JSON
   *
   * @param Request $request
   */
  public function findInventory(Request $request)
  {
    if ($request->initSession() && $request->isReferer() && Login::isMember()) {
      $search = $request->post('equipment')->topic();
      if ($search != '') {
        $where = array('equipment', 'LIKE', "%$search%");
        $order = 'equipment';
      } else {
        $search = $request->post('serial')->topic();
        if ($search != '') {
          $where = array('serial', 'LIKE', "%$search%");
          $order = 'serial';
        }
      }
      if (isset($where)) {
        $result = $this->db()->createQuery()
          ->select('id inventory_id', 'equipment', 'serial')
          ->from('inventory')
          ->where($where)
          ->order($order)
          ->limit($request->post('count')->toInt())
          ->toArray()
          ->execute();
        if (!empty($result)) {
          // คืนค่า JSON
          echo json_encode($result);
        }
      }
    }
  }
}
