<?php
/**
 * @filesource modules/index/views/setup.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Setup;

use Gcms\Login;
use Kotchasan\DataTable;
use Kotchasan\Date;
use Kotchasan\Http\Request;

/**
 * module=member.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * @var mixed
     */
    private $statuses;
    /**
     * @var mixed
     */
    private $operators;

    /**
     * ตารางรายชื่อสมาชิก
     *
     * @param Request $request
     * @param array   $login
     *
     * @return string
     */
    public function render(Request $request, $login)
    {
        $isAdmin = Login::checkPermission($login, 'can_received_repair');
        // สถานะการซ่อม
        $this->statuses = \Repair\Status\Model::create();
        // รายชื่อช่างซ่อม
        $operator_id = $request->request('operator_id', -1)->toInt();
        $this->operators = \Repair\Operator\Model::create();
        $operators = array();
        if ($isAdmin) {
            $operators[-1] = '{LNG_all items}';
        } else {
            $operator_id = $login['id'];
        }
        foreach ($this->operators->toSelect() as $k => $v) {
            if ($isAdmin || $k == $operator_id) {
                $operators[$k] = $v;
            }
        }
        $status = $request->request('status', -1)->toInt();
        // URL สำหรับส่งให้ตาราง
        $uri = self::$request->createUriWithGlobals(WEB_URL.'index.php');
        // ตาราง
        $table = new DataTable(array(
            /* Uri */
            'uri' => $uri,
            /* Model */
            'model' => \Repair\Setup\Model::toDataTable(),
            'perPage' => $request->cookie('repair_perPage', 30)->toInt(),
            'sort' => $request->cookie('repair_sort', 'create_date desc')->toString(),
            'onRow' => array($this, 'onRow'),
            /* คอลัมน์ที่ไม่ต้องแสดงผล */
            'hideColumns' => array('id'),
            /* คอลัมน์ที่สามารถค้นหาได้ */
            'searchColumns' => array('name', 'phone', 'job_id'),
            /* ตั้งค่าการกระทำของของตัวเลือกต่างๆ ด้านล่างตาราง ซึ่งจะใช้ร่วมกับการขีดถูกเลือกแถว */
            'action' => 'index.php/repair/model/setup/action',
            'actionCallback' => 'dataTableActionCallback',
            'actions' => array(
                array(
                    'id' => 'action',
                    'class' => 'ok',
                    'text' => '{LNG_With selected}',
                    'options' => array(
                        'delete' => '{LNG_Delete}',
                    ),
                ),
            ),
            /* ตัวเลือกด้านบนของตาราง ใช้จำกัดผลลัพท์การ query */
            'filters' => array(
                'operator_id' => array(
                    'name' => 'operator_id',
                    'default' => -1,
                    'text' => '{LNG_Operator}',
                    'options' => $operators,
                    'value' => $operator_id,
                ),
                'status' => array(
                    'name' => 'status',
                    'default' => -1,
                    'text' => '{LNG_Status}',
                    'options' => array(-1 => '{LNG_all items}') + $this->statuses->toSelect(),
                    'value' => $status,
                ),
            ),
            /* ส่วนหัวของตาราง และการเรียงลำดับ (thead) */
            'headers' => array(
                'job_id' => array(
                    'text' => '{LNG_Receipt No.}',
                ),
                'name' => array(
                    'text' => '{LNG_Name}',
                    'sort' => 'name',
                ),
                'phone' => array(
                    'text' => '{LNG_Phone}',
                    'class' => 'center',
                ),
                'equipment' => array(
                    'text' => '{LNG_Equipment}',
                ),
                'create_date' => array(
                    'text' => '{LNG_Received date}',
                    'class' => 'center',
                    'sort' => 'create_date',
                ),
                'appointment_date' => array(
                    'text' => '{LNG_Appointment date}',
                    'class' => 'center',
                    'sort' => 'appointment_date',
                ),
                'operator_id' => array(
                    'text' => '{LNG_Operator}',
                    'class' => 'center',
                ),
                'status' => array(
                    'text' => '{LNG_Repair status}',
                    'class' => 'center',
                    'sort' => 'status',
                ),
            ),
            /* รูปแบบการแสดงผลของคอลัมน์ (tbody) */
            'cols' => array(
                'phone' => array(
                    'class' => 'center',
                ),
                'create_date' => array(
                    'class' => 'center',
                ),
                'appointment_date' => array(
                    'class' => 'center',
                ),
                'operator_id' => array(
                    'class' => 'center',
                ),
                'status' => array(
                    'class' => 'center',
                ),
            ),
            /* ปุ่มแสดงในแต่ละแถว */
            'buttons' => array(
                array(
                    'class' => 'icon-print button print notext',
                    'href' => WEB_URL.'modules/repair/print.php?id=:job_id',
                    'target' => 'print',
                    'title' => '{LNG_Print receipt}',
                ),
                'status' => array(
                    'class' => 'icon-list button orange notext',
                    'id' => ':id',
                    'title' => '{LNG_Repair status}',
                ),
                array(
                    'class' => 'icon-report button purple notext',
                    'href' => $uri->createBackUri(array('module' => 'repair-detail', 'id' => ':id')),
                    'title' => '{LNG_Repair job description}',
                ),
            ),
        ));
        // สามารถแก้ไขใบรับซ่อมได้
        if ($isAdmin) {
            $table->buttons[] = array(
                'class' => 'icon-edit button green notext',
                'href' => $uri->createBackUri(array('module' => 'repair-receive', 'id' => ':id')),
                'title' => '{LNG_Edit} {LNG_Repair details}',
            );
        }
        // save cookie
        setcookie('repair_perPage', $table->perPage, time() + 2592000, '/', null, HOST, true);
        setcookie('repair_sort', $table->sort, time() + 2592000, '/', null, HOST, true);

        return $table->render();
    }

    /**
     * จัดรูปแบบการแสดงผลในแต่ละแถว.
     *
     * @param array $item
     *
     * @return array
     */
    public function onRow($item, $o, $prop)
    {
        $item['create_date'] = Date::format($item['create_date'], 'd M Y');
        $item['appointment_date'] = Date::format($item['appointment_date'], 'd M Y');
        $item['phone'] = self::showPhone($item['phone']);
        $item['status'] = '<mark class=term style="background-color:'.$this->statuses->getColor($item['status']).'">'.$this->statuses->get($item['status']).'</mark>';
        $item['operator_id'] = $this->operators->get($item['operator_id']);

        return $item;
    }
}
