<?php
/**
* 2007-2020 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Orderexporter extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'orderexporter';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Rayyo';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('OrderExporter ');
        $this->description = $this->l('Export Order to web Portal');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall Module? You will lose all the data related to this module');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('ORDEREXPORTER_LIVE_MODE', false);

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayOrderConfirmation') && 
            $this->addTab('AdminIsufExportManegerOrder', 'ISUF Orders', 'AdminAdvancedParameters');
    }

    public function uninstall()
    {
        Configuration::deleteByName('ORDEREXPORTER_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall() && $this->deleteTab('AdminIsufExportManegerOrder');
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitOrderexporterModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitOrderexporterModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'ORDEREXPORTER_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid URl'),
                        'name' => 'ORDEREXPORTER_PORTAL_URL',
                        'label' => $this->l('Portal URl'),
                    ),
                 
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'ORDEREXPORTER_LIVE_MODE' => Configuration::get('ORDEREXPORTER_LIVE_MODE', true),
            'ORDEREXPORTER_PORTAL_URL' => Configuration::get('ORDEREXPORTER_PORTAL_URL', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->registerJavascript('exportorder',$this->_path.'/views/js/front.js',array('server' => 'remote', 'position' => 'top', 'priority' => 1000));
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookDisplayOrderConfirmation($params)
    {
        if(Configuration::get('ORDEREXPORTER_LIVE_MODE')){
            $order = $params['order'];
            if (Validate::isLoadedObject($order) && $order->getCurrentState() != (int)Configuration::get('PS_OS_ERROR')) {
                $order_sent = Db::getInstance()->getValue('SELECT id_order FROM `'._DB_PREFIX_.'orderexporter` WHERE id_order = '.(int)$order->id);
                if (1) {
                
                    if ($order->id_customer == $this->context->cookie->id_customer) {              
                    $data =  $this->wrapOrder($order);                   
                    }

                    Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'orderexporter` (id_order, id_shop, sent, data ,date_add) VALUES ('.(int)$order->id.', '.(int)$this->context->shop->id.', 1,\''.json_encode($data).'\', NOW())');
                    $URL = Configuration::get('ORDEREXPORTER_PORTAL_URL');
                    $js = 'MBG.ExportOrderajax("'.$URL.'",'.json_encode($data).');';
                    return $this->_runJs($js);       
                }
            }
        }
        

    }

    /**
     * wrap product to provide a standard product information for google analytics script
     */
    public function wrapProduct($product)
    {    
        $data = array(
                'product_id' => $product['product_id'],
                'name' => Tools::str2url($product['product_name']),            
                'quantity' => $product['product_quantity'],             
                'product_price' => $product['product_price'],
                'total_price_tax_excl' => $product['total_price_tax_excl'],
                'reference' => $product['reference'],
                'product_reference' => $product['product_reference'],
                'isbn' => $product['isbn'],             
                'wholesale_price' => $product['wholesale_price']
            );
            
      
        return $data;
    }

    public function wrapCustomer($order){
        $customer = $order->getCustomer();
        $data = array(
            'firstname' => $customer->firstname,
            'lastname' => $customer->lastname,
            'email' => $customer->email         
        );
        return $data;
    }

    public function wrapOrder($order){
        
        $data = array(
            'id' => $order->id,
            'id_address_delivery' => $order->id_address_delivery,
            'id_address_invoice' => $order->id_address_invoice,
            'id_cart' => $order->id_cart,
            'total_paid'=>$order->total_paid,
            'total_shipping' => $order->total_shipping,
            'created_date' => $order->date_add,
            'customer' => $this->wrapCustomer($order)
            
        );

        foreach ($order->getProducts() as $order_product) {
            $data['product'][] = $this->wrapProduct($order_product);
        }
        
        return $data;
    }

    protected function _runJs($js_code, $backoffice = 0)
    {
        if (Configuration::get('GA_ACCOUNT_ID')) {
            $runjs_code = '';           
            $runjs_code .= '
            <script type="text/javascript">               
                    document.addEventListener(\'DOMContentLoaded\', function() {      
                        var MBG = OrderExpoterECommerce;
                        '.$js_code.'
                    });
            </script>';
            

            return $runjs_code;
        }
    }
     /**
     * Add tab.
     */

    public function addTab($className, $name, $parentClassName) {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = array();
        $tab->name[(int) (Configuration::get('PS_LANG_DEFAULT'))] = $this->l($name);
        $tab->module = $this->name;
        $tab->id_parent = (int) Tab::getIdFromClassName($parentClassName);
        return $tab->add();
    }


    /**
     * Delete tab.
     */
    protected function deleteTab($className) {
        $id_tab = (int) Tab::getIdFromClassName($className);
        $allTableDeleted = true;
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $allTableDeleted = $tab->delete();
        } else {
            return false;
        }

        return $allTableDeleted;
    }


    
}


