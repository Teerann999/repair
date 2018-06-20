<?php
/**
 * @filesource modules/repair/modules/operator.php
 *
 * @see http://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Repair\Operator;

/**
 * อ่านรายชื่อช่างซ่อมทั้งหมด.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\KBase
{
    private $operators;

    /**
     * Query รายชื่อช่างซ่อม
     *
     * @return array
     */
    public static function all()
    {
        $model = new \Kotchasan\Model();

        return $model->db()->createQuery()
            ->select('id', 'name')
            ->from('user')
            ->where(array('permission', 'LIKE', '%,can_repair,%'))
            ->order('id')
            ->toArray()
            ->execute();
    }

    /**
     * อ่านรายชื่อช่างซ่อม
     *
     * @return array
     */
    public static function create()
    {
        $obj = new static();
        $obj->operators = array();
        foreach (self::all() as $item) {
            $obj->operators[$item['id']] = $item['name'];
        }

        return $obj;
    }

    /**
     * อ่านรายชื่อช่างซ่อมสำหรับใส่ลงใน select.
     *
     * @return array
     */
    public function toSelect()
    {
        return $this->operators;
    }

    /**
     * อ่านชื่อช่างที่ $id.
     *
     * @param int $id
     *
     * @return string
     */
    public function get($id)
    {
        return isset($this->operators[$id]) ? $this->operators[$id] : '';
    }
}
