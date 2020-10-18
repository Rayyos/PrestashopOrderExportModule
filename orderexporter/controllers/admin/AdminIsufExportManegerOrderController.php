<?php

/**
 * NOTICE OF LICENSE.
 *
 * This source file is subject to a commercial license from SAS Comptoir du Code
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SAS Comptoir du Code is strictly forbidden.
 * In order to obtain a license, please contact us: contact@comptoirducode.com
 *
 * @author    Vincent - Comptoir du Code
 * @copyright Copyright(c) 2015-2016 SAS Comptoir du Code
 * @license   Commercial license
 */


if (!defined('_ISUFORDEREXPORT_DIR_'))
    define('_ISUFORDEREXPORT_DIR_', dirname(__FILE__) . '/../..');

include_once _ISUFORDEREXPORT_DIR_ . '/classes/IsufOrderLog.php';

Class AdminIsufExportManegerOrderController extends ModuleAdminController {

    protected $statuses_array = array();
    protected $shop_id = null;

    public function __construct() {
        $this->bootstrap = true;
        $this->table = 'orderexporter';
        $this->identifier = 'id_orderexporter';     
          
        $this->lang = false;
        $this->addRowAction('view');
        $this->explicitSelect = true;
        $this->allow_export = true;
        $this->deleted = false;
        $this->context = Context::getContext();

        parent::__construct();

        $this->_orderWay = 'DESC';
      
        $this->_select = '
            a.sent,	IF(a.sent, 1, 0) badge_success,			
			osl.`name` AS `osname`,
            os.`color` AS `oscolor`,';
            
        $this->_join = '
            LEFT JOIN `' . _DB_PREFIX_ . 'orders` o ON (o.id_order = a.id_order)
            LEFT JOIN `' . _DB_PREFIX_ . 'order_state` os ON (os.`id_order_state` = o.`current_state`)
			LEFT JOIN `' . _DB_PREFIX_ . 'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = ' . (int) $this->context->language->id . ')';

            $statuses = OrderState::getOrderStates((int) $this->context->language->id);
            foreach ($statuses as $status) {
                $this->statuses_array[$status['id_order_state']] = $status['name'];
            }
     
        $this->fields_list = array(
            'id_orderexporter' => array(
                'title' => $this->l('ID'),
                'align' => 'text-center',
                'filter_key' => 'a!id_orderexporter',
                'class' => 'fixed-width-xs',
            ),
            'id_order' => array(
                'title' => $this->l('Order ID'),
                'align' => 'text-center',
                'filter_key' => 'o!id_order',
                'class' => 'fixed-width-xs',
            ),
            'reference' => array(
                'title' => $this->l('Reference'),
                'align' => 'text-center',
            ),
            'osname' => array(
                'title' => $this->l('Status'),
                'type' => 'select',
                'color' => 'oscolor',
                'list' => $this->statuses_array,
                'filter_key' => 'os!id_order_state',
                'filter_type' => 'int',
                'order_key' => 'osname',
            ),
            'order_date_add' => array(
                'title' => $this->l('Date order'),
                'type' => 'datetime',
                'filter_key' => 'o!date_add',
            ),
            'total_paid_tax_incl' => array(
                'title' => $this->l('Total'),
                'align' => 'text-right',
                'class' => 'fixed-width-sm',
                'type' => 'price',
                'currency' => true,
            ),
        
            'sent' => array(
                'title' => $this->l('Sent'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs',
                'orderby' => false,
                'badge_success' => true,
            ),
                
            
           
        );

       
    }
    public function initToolbar() {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }


    public function renderView() {
        $id_orderexporter = (int) Tools::getValue('id_orderexporter');

        $orderexporter = Db::getInstance()->getValue('SELECT id_order FROM `'._DB_PREFIX_.'orderexporter` WHERE id_orderexporter = '.(int)$id_orderexporter);

        $orderLog = new IsufOrderLog($id_orderexporter); 
        if (!Validate::isLoadedObject($orderLog)){
            $this->errors[] = Tools::displayError('The order cannot be found within your database.');
        }else{

        }

          // display view
          $this->context->smarty->assign(array(
            'orderLog' => $orderLog,           
        ));

        return parent::renderView();
    }


  

}