<?php
/**
 * @filesource modules/repair/models/detail.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Repair\Detail;

use \Kotchasan\Database\Sql;

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
    $q1 = $model->db()->createQuery()
      ->select('repair_id', Sql::MAX('id', 'max_id'))
      ->from('repair_status')
      ->groupBy('repair_id');
    $sql = $model->db()->createQuery()
      ->select('R.*', 'U.name', 'U.phone', 'U.address', 'U.zipcode', 'U.provinceID', 'V.equipment', 'V.serial', 'S.status', 'S.comment', 'S.cost', 'S.operator_id', 'S.id status_id')
      ->from('repair R')
      ->join(array($q1, 'T'), 'INNER', array('T.repair_id', 'R.id'))
      ->join('repair_status S', 'INNER', array('S.id', 'T.max_id'))
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
   * อ่านสถานะการทำรายการทั้งหมด
   *
   * @param int $id
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
}
