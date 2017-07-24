<?php
/**
 * @filesource modules/repair/models/home.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Repair\Home;

use \Kotchasan\Database\Sql;

/**
 * โมเดลสำหรับอ่านข้อมูลแสดงในหน้า  Home
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  /**
   * อ่านงานซ่อมใหม่
   *
   * @return object
   */
  public static function getNew($login)
  {
    if (isset(self::$cfg->repair_first_status)) {
      $model = new static;
      $q1 = $model->db()->createQuery()
        ->select('id', 'status', 'repair_id')
        ->from('repair_status')
        ->order('create_date DESC');
      $q2 = $model->db()->createQuery()
        ->select()
        ->from(array($q1, 'S'))
        ->groupBy('S.repair_id')
        ->having(array('S.status', self::$cfg->repair_first_status));
      $search = $model->db()->createQuery()
        ->selectCount()
        ->from(array($q2, 'Z'))
        ->toArray()
        ->execute();
      if (!empty($search)) {
        return $search[0]['count'];
      }
    }
    return 0;
  }
}
