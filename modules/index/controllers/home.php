<?php
/**
 * @filesource modules/index/controllers/home.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Home;

use \Kotchasan\Http\Request;
use \Kotchasan\Html;
use \Kotchasan\Language;

/**
 * module=home
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{

  /**
   * Dashboard
   *
   * @param Request $request
   * @return string
   */
  public function render(Request $request)
  {
    // ข้อความ title bar
    $this->title = Language::get('Dashboard');
    // เลือกเมนู
    $this->menu = 'home';
    // แสดงผล
    $section = Html::create('section');
    // breadcrumbs
    $breadcrumbs = $section->add('div', array(
      'class' => 'breadcrumbs'
    ));
    $ul = $breadcrumbs->add('ul');
    $ul->appendChild('<li><span class="icon-home">{LNG_Home}</span></li>');
    $section->add('header', array(
      'innerHTML' => '<h2 class="icon-dashboard">'.$this->title.'</h2>'
    ));
    // แสดงฟอร์ม
    $section->appendChild(createClass('Index\Home\View')->render($request));
    return $section->render();
  }
}
