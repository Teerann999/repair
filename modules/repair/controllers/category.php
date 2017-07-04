<?php
/**
 * @filesource modules/repair/controllers/category.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Repair\Category;

use \Kotchasan\Http\Request;
use \Gcms\Login;
use \Kotchasan\Html;
use \Kotchasan\Language;

/**
 * module=repair-category
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{

  /**
   * หมวดหมู่งานซ่อม
   *
   * @param Request $request
   * @return string
   */
  public function render(Request $request)
  {
    $index = (object)array(
        // ประเภทที่ต้องการ
        'group_id' => $request->request('typ')->toInt(),
        // ชื่อหมวดหมู่ที่สามารถใช้งานได้
        'categories' => Language::get('REPAIR_CATEGORIES')
    );
    if (!isset($index->categories[$index->group_id])) {
      $index->group_id = reset((array_keys($index->categories)));
    }
    // ข้อความ title bar
    $title = $index->categories[$index->group_id];
    $this->title = Language::trans('{LNG_List of} ').$title;
    // เลือกเมนู
    $this->menu = 'settings';
    // สามารถตั้งค่าระบบได้
    if (Login::checkPermission(Login::isMember(), 'can_config')) {
      // แสดงผล
      $section = Html::create('section');
      // breadcrumbs
      $breadcrumbs = $section->add('div', array(
        'class' => 'breadcrumbs'
      ));
      $ul = $breadcrumbs->add('ul');
      $ul->appendChild('<li><span class="icon-settings">{LNG_Settings}</span></li>');
      $ul->appendChild('<li><span>{LNG_Repair system}</span></li>');
      $ul->appendChild('<li><span>'.$title.'</span></li>');
      $section->add('header', array(
        'innerHTML' => '<h2 class="icon-tools">'.$this->title.'</h2>'
      ));
      // แสดงฟอร์ม
      $section->appendChild(createClass('Repair\Category\View')->render($index));
      return $section->render();
    }
    // 404.html
    return \Index\Error\Controller::page404();
  }
}
