<?php
/**
 * @filesource modules/repair/models/export.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Repair\Export;

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
   * อ่านรายละเอียดการทำรายการจาก $job_id
   * สำหรับการออกใบรับซ่อม
   *
   * @param string $job_id
   * @return object
   */
  public static function get($job_id)
  {
    $model = new static;
    $sql = $model->db()->createQuery()
      ->select('R.*', 'U.name', 'U.phone', 'U.address', 'U.zipcode', 'U.provinceID', 'V.equipment', 'V.serial', 'S.status', 'S.comment', 'S.operator_id')
      ->from('repair R')
      ->join('repair_status S', 'INNER', array('S.repair_id', 'R.id'))
      ->join('inventory V', 'INNER', array('V.id', 'R.inventory_id'))
      ->join('user U', 'INNER', array('U.id', 'R.customer_id'))
      ->where(array('R.job_id', $job_id))
      ->order('S.id ASC');
    return $model->db()->createQuery()
        ->from(array($sql, 'Q'))
        ->groupBy('Q.id')
        ->first();
  }
}
