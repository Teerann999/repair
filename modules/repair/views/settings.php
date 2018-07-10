<?php
/**
 * @filesource modules/repair/views/settings.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Repair\Settings;

use Kotchasan\Html;
use Kotchasan\Language;

/**
 * module=repair-settings.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * ฟอร์ม ตั้งค่าร้านค้า.
     *
     * @return string
     */
    public function render()
    {
        // form
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/repair/model/settings/submit',
            'onsubmit' => 'doFormSubmit',
            'ajax' => true,
            'token' => true,
        ));
        $fieldset = $form->add('fieldset', array(
            'title' => '{LNG_Details of} {LNG_Company}',
        ));
        $groups = $fieldset->add('groups');
        // company_name
        $groups->add('text', array(
            'id' => 'company_name',
            'labelClass' => 'g-input icon-user',
            'itemClass' => 'width50',
            'label' => '{LNG_Company Name}',
            'comment' => '%COMPANY%',
            'value' => isset(self::$cfg->company_name) ? self::$cfg->company_name : '',
        ));
        // phone
        $groups->add('text', array(
            'id' => 'phone',
            'labelClass' => 'g-input icon-phone',
            'itemClass' => 'width50',
            'label' => '{LNG_Phone}',
            'maxlength' => 32,
            'comment' => '%COMPANYPHONE%',
            'value' => isset(self::$cfg->phone) ? self::$cfg->phone : '',
        ));
        // address
        $fieldset->add('text', array(
            'id' => 'address',
            'labelClass' => 'g-input icon-location',
            'itemClass' => 'item',
            'label' => '{LNG_Address}',
            'comment' => '%COMPANYADDRESS%',
            'value' => isset(self::$cfg->address) ? self::$cfg->address : '',
        ));
        $fieldset = $form->add('fieldset', array(
            'title' => '{LNG_Other}',
        ));
        // currency_unit
        $fieldset->add('select', array(
            'id' => 'currency_unit',
            'labelClass' => 'g-input icon-currency',
            'itemClass' => 'item',
            'label' => '{LNG_Currency unit}',
            'options' => Language::get('CURRENCY_UNITS'),
            'value' => isset(self::$cfg->currency_unit) ? self::$cfg->currency_unit : 'THB',
        ));
        $fieldset = $form->add('fieldset', array(
            'class' => 'submit',
        ));
        // submit
        $fieldset->add('submit', array(
            'class' => 'button save large icon-save',
            'value' => '{LNG_Save}',
        ));

        return $form->render();
    }
}
