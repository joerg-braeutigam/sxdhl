<?php
/**
 * PrestaShop module created by Saxtec, a prestashop certificated agency
 *
 * @author    Saxtec https://www.saxtec.com
 * @copyright 2008-2021 Saxtec
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER prestashop@saxtec.com
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class SxDhl extends Module
{
    protected $html = '';
    protected $postErrors = array();

    public function __construct()
    {
        $this->name = 'sxdhl';
        $this->tab = 'administration';
        $this->version = '1.2.2';
        $this->ps_versions_compliancy = array('min' => '1.7.7', 'max' => _PS_VERSION_);
        $this->author = 'Saxtec eCommerce';
        $this->module_key = 'b99e68639cf8e6a4a26abb1edfe02ead';
        $this->is_eu_compatible = 1;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('DHL Integration');
        $this->description = $this->l('SX DHL shipping integration');

        $this->output = '';
        $this->updintval = 3600;
        $this->state_delivred = 20;
        $this->state_shipped = 4;

        // Settings paths
        $this->js_path = $this->_path . 'views/js/';
        $this->css_path = $this->_path . 'views/css/';
        $this->img_path = $this->_path . 'views/img/';
        $this->docs_path = $this->_path . 'docs/';
        $this->logo_path = $this->_path . 'logo.png';
        $this->module_path = $this->_path;
        $this->ps_version = (bool)version_compare(_PS_VERSION_, '1.7', '>=');

        // Confirm uninstall
        $this->confirmUninstall = $this->trans(
            'Are you sure you want to uninstall this module?',
            array(),
            'Admin.Modules.Notification'
        );
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('displayAdminOrder')) {
            return false;
        }

        Configuration::updateValue('SXDHL_MODE', 'test');
        Configuration::updateValue('SXDHL_AUTO_VIEW', '0');
        Configuration::updateValue('SXDHL_USER_LIVE', null);
        Configuration::updateValue('SXDHL_PW_LIVE', null);
        Configuration::updateValue('SXDHL_EKP_LIVE', null);
        Configuration::updateValue('SXDHL_BILLING_LIVE', '01');
        Configuration::updateValue('SXDHL_NATIONAL_LIVE', '01');
        Configuration::updateValue('SXDHL_INTERNATIONAL_LIVE', '53');
        Configuration::updateValue('SXDHL_PRODUCT_NATIONAL_LIVE', 'V01PAK');
        Configuration::updateValue('SXDHL_PRODUCT_INTERNATIONAL_LIVE', 'V53WPAK');
        Configuration::updateValue('SXDHL_USER_DEV', null);
        Configuration::updateValue('SXDHL_PW_DEV', null);
        Configuration::updateValue('SXDHL_EKP_DEV', '2222222222');
        Configuration::updateValue('SXDHL_BILLING_DEV', '01');
        Configuration::updateValue('SXDHL_NATIONAL_DEV', '01');
        Configuration::updateValue('SXDHL_INTERNATIONAL_DEV', '53');
        Configuration::updateValue('SXDHL_PRODUCT_NATIONAL_DEV', 'V01PAK');
        Configuration::updateValue('SXDHL_PRODUCT_INTERNATIONAL_DEV', 'V53WPAK');
        Configuration::updateValue('SXDHL_SENDER_COMPANY', Configuration::get('PS_SHOP_NAME'));
        Configuration::updateValue('SXDHL_SENDER_STREET', Configuration::get('PS_SHOP_ADDR1'));
        Configuration::updateValue('SXDHL_SENDER_HOUSE', '0');
        Configuration::updateValue('SXDHL_SENDER_ZIP', Configuration::get('PS_SHOP_CODE'));
        Configuration::updateValue('SXDHL_SENDER_CITY', Configuration::get('PS_SHOP_CITY'));
        Configuration::updateValue('SXDHL_SENDER_COUNTRY', Configuration::get('PS_SHOP_COUNTRY'));
        Configuration::updateValue('SXDHL_SENDER_ISO', Configuration::get('PS_LOCALE_COUNTRY'));
        Configuration::updateValue('SXDHL_REVOKE_DAYS', '14');
        Configuration::updateValue('SXDHL_TRACKING_LINK', '');
        Configuration::updateValue('SXDHL_TRACKING_ENABLE', '0');

        $_table = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "sxdhl` ( "
            . " `id_sxdhl` int(10) unsigned NOT NULL AUTO_INCREMENT, "
            . " `id_order` int(10) unsigned DEFAULT NULL, "
            . " `shipping_number` varchar(50) DEFAULT NULL, "
            . " `label_file` varchar(255) DEFAULT NULL, "
            . " `label_create` datetime DEFAULT NULL, "
            . " `label_update` datetime DEFAULT NULL, "
            . " `state` varchar(255) DEFAULT NULL, "
            . " PRIMARY KEY (`id_sxdhl`) ) ";
        Db::getInstance()->execute($_table);

        return true;
    }

    public function uninstall()
    {
        Configuration::deleteByName('SXDHL_MODE');
        Configuration::deleteByName('SXDHL_AUTO_VIEW');
        Configuration::deleteByName('SXDHL_USER_LIVE');
        Configuration::deleteByName('SXDHL_PW_LIVE');
        Configuration::deleteByName('SXDHL_EKP_LIVE');
        Configuration::deleteByName('SXDHL_BILLING_LIVE');
        Configuration::deleteByName('SXDHL_NATIONAL_LIVE');
        Configuration::deleteByName('SXDHL_INTERNATIONAL_LIVE');
        Configuration::deleteByName('SXDHL_USER_DEV');
        Configuration::deleteByName('SXDHL_PW_DEV');
        Configuration::deleteByName('SXDHL_EKP_DEV');
        Configuration::deleteByName('SXDHL_BILLING_DEV');
        Configuration::deleteByName('SXDHL_NATIONAL_DEV');
        Configuration::deleteByName('SXDHL_INTERNATIONAL_DEV');
        Configuration::deleteByName('SXDHL_SENDER_COMPANY');
        Configuration::deleteByName('SXDHL_SENDER_STREET');
        Configuration::deleteByName('SXDHL_SENDER_HOUSE');
        Configuration::deleteByName('SXDHL_SENDER_ZIP');
        Configuration::deleteByName('SXDHL_SENDER_CITY');
        Configuration::deleteByName('SXDHL_SENDER_COUNTRY');
        Configuration::deleteByName('SXDHL_SENDER_ISO');

        $_table = "DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "sxdhl`";
        Db::getInstance()->execute($_table);

        return parent::uninstall();
    }

    protected function postProcess()
    {
        $success = 0;

        if (Tools::getValue('dhl_default_data') && Tools::getValue('dhl_default_data') == '1') {
            if (Tools::getValue('sxdhl_mode_form_switch') == '1') {
                if (Configuration::updateValue('SXDHL_MODE', 'production')) {
                    $success = 1;
                }
            } else {
                if (Configuration::updateValue('SXDHL_MODE', 'test')) {
                    $success = 1;
                }
            }
            if (Tools::getValue('sxdhl_autoview_form_switch') == '1') {
                if (Configuration::updateValue('SXDHL_AUTO_VIEW', '1')) {
                    $success = 1;
                }
            } else {
                if (Configuration::updateValue('SXDHL_AUTO_VIEW', '0')) {
                    $success = 1;
                }
            }
            if (Tools::getValue('sxdhl_tracking_form_switch') == '1') {
                if (Configuration::updateValue('SXDHL_TRACKING_ENABLE', '1')) {
                    $success = 1;
                }
            } else {
                if (Configuration::updateValue('SXDHL_TRACKING_ENABLE', '0')) {
                    $success = 1;
                }
            }

            if ($success == 1) {
                $this->output .= $this->displayConfirmation($this->l('Saved with success !'));
            } else {
                $this->output += $this->displayError($this->l('Error while saving'));
            }
        }

        if (Tools::getValue('dhl_dev') && Tools::getValue('dhl_dev') == '1') {
            if (Configuration::updateValue('SXDHL_USER_DEV', str_rot13(Tools::getValue('dhl_user_dev')))) {
                $success = 1;
            }
            if (Configuration::updateValue('SXDHL_PW_DEV', str_rot13(Tools::getValue('dhl_pw_dev')))) {
                $success = 1;
            }

            if ($success == 1) {
                $this->output .= $this->displayConfirmation($this->l('Saved with success !'));
            } else {
                $this->output += $this->displayError($this->l('Error while saving'));
            }
        }

        if (Tools::getValue('dhl_live') && Tools::getValue('dhl_live') == '1') {
            if (Configuration::updateValue(
                'SXDHL_USER_LIVE',
                str_rot13(Tools::getValue('dhl_user_live'))
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_PW_LIVE',
                str_rot13(Tools::getValue('dhl_pw_live'))
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_EKP_LIVE',
                Tools::getValue('dhk_ekp_live')
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_BILLING_LIVE',
                Tools::getValue('dhl_billing_live')
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_NATIONAL_LIVE',
                Tools::getValue('dhl_national_live')
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_INTERNATIONAL_LIVE',
                Tools::getValue('dhl_international_live')
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_PRODUCT_NATIONAL_LIVE',
                Tools::getValue('dhl_product_national_live')
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_PRODUCT_INTERNATIONAL_LIVE',
                Tools::getValue('dhl_product_international_live')
            )) {
                $success = 1;
            }

            if ($success == 1) {
                $this->output .= $this->displayConfirmation($this->l('Saved with success !'));
            } else {
                $this->output += $this->displayError($this->l('Error while saving'));
            }
        }

        if (Tools::getValue('dhl_sender') && Tools::getValue('dhl_sender') == '1') {
            if (Configuration::updateValue(
                'SXDHL_SENDER_COMPANY',
                Tools::getValue('dhl_sender_company')
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_SENDER_STREET',
                Tools::getValue('dhl_sender_street')
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_SENDER_HOUSE',
                Tools::getValue('dhl_sender_house')
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_SENDER_ZIP',
                Tools::getValue('dhl_sender_zip')
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_SENDER_CITY',
                Tools::getValue('dhl_sender_city')
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_SENDER_COUNTRY',
                Tools::getValue('dhl_sender_country')
            )) {
                $success = 1;
            }
            if (Configuration::updateValue(
                'SXDHL_SENDER_ISO',
                Tools::getValue('dhl_sender_iso')
            )) {
                $success = 1;
            }

            if ($success == 1) {
                $this->output .= $this->displayConfirmation($this->l('Saved with success !'));
            } else {
                $this->output += $this->displayError($this->l('Error while saving'));
            }
        }
    }

    public function loadAsset()
    {
        // Load CSS
        $css = array(
            $this->css_path . 'fontawesome-all.min.css',
            $this->css_path . 'datatables.min.css',
            $this->css_path . 'faq.css',
            $this->css_path . 'menu.css',
            $this->css_path . 'back.css',
            $this->css_path . $this->name . '.css',
        );

        $this->context->controller->addCSS($css, 'all');

        // Load JS
        $jss = array(
            $this->js_path . 'vue.min.js',
            $this->js_path . 'datatables.min.js',
            $this->js_path . 'faq.js',
            $this->js_path . 'menu.js',
            $this->js_path . 'back.js',
            $this->js_path . 'sweetalert.min.js',
            _PS_ROOT_DIR_ . 'js/tiny_mce/tiny_mce.js',
            _PS_ROOT_DIR_ . 'js/admin/tinymce.inc.js',
            $this->js_path . 'jszip.min.js',
            $this->js_path . 'pdfmake.min.js',
            $this->js_path . 'vfs_fonts.js',
            $this->js_path . 'buttons.html5.min.js',
        );

        $this->context->controller->addJS($jss);

        // Clean memory
        unset($jss, $css);
    }

    public function getContent()
    {
        $params = array('configure' => $this->name);
        $moduleAdminLink = Context::getContext()->link->getAdminLink('AdminModules', true, false, $params);

        $id_lang = $this->context->language->id;

        $this->loadAsset(); // load js and css
        $this->postProcess(); // execute submit form

        $iso_lang = Language::getIsoById($id_lang);
        // get readme
        switch ($iso_lang) {
            case 'de':
                $doc = $this->docs_path . 'readme_de.pdf';
                break;
            default:
                $doc = $this->docs_path . 'readme_en.pdf';
                break;
        }

        // youtube video
        switch ($iso_lang) {
            case 'de':
                $youtubeLink = 'https://www.youtube.com/embed/71raSgHONhk';
                break;
            default:
                $youtubeLink = 'https://www.youtube.com/embed/71raSgHONhk';
                break;
        }


        // get current page
        $currentPage = 'Config';
        $page = Tools::getValue('page');
        if (!empty($page)) {
            $currentPage = Tools::getValue('page');
        }

        // assign var to smarty
        $this->context->smarty->assign(array(
            'module_name' => $this->name,
            'module_version' => $this->version,
            'moduleAdminLink' => $moduleAdminLink,
            'doc' => $doc,
            'id_lang' => $id_lang,
            'youtubeLink' => $youtubeLink,
            'module_display' => $this->displayName,
            'module_path' => $this->module_path,
            'logo_path' => $this->logo_path,
            'img_path' => $this->img_path,
            'languages' => $this->context->controller->getLanguages(),
            'defaultFormLanguage' => (int)$this->context->employee->id_lang,
            'currentPage' => $currentPage,
            'ps_base_dir' => Tools::getHttpHost(true),
            'ps_version' => _PS_VERSION_,
            'isPs17' => $this->ps_version,
            'dhl_mode' => Configuration::get('SXDHL_MODE'),
            'dhl_autoview' => Configuration::get('SXDHL_AUTO_VIEW'),
            'dhl_tracking_link' => Configuration::get('SXDHL_TRACKING_LINK'),
            'dhl_tracking' => Configuration::get('SXDHL_TRACKING_ENABLE'),
            'dhl_user_dev' => str_rot13(Configuration::get('SXDHL_USER_DEV')),
            'dhl_pw_dev' => str_rot13(Configuration::get('SXDHL_PW_DEV')),
            'dhk_ekp_dev' => Configuration::get('SXDHL_EKP_DEV'),
            'dhl_billing_dev' => Configuration::get('SXDHL_BILLING_DEV'),
            'dhl_national_dev' => Configuration::get('SXDHL_NATIONAL_DEV'),
            'dhl_international_dev' => Configuration::get('SXDHL_INTERNATIONAL_DEV'),
            'dhl_product_national_dev' => Configuration::get('SXDHL_PRODUCT_NATIONAL_DEV'),
            'dhl_product_international_dev' => Configuration::get('SXDHL_PRODUCT_INTERNATIONAL_DEV'),
            'dhl_user_live' => str_rot13(Configuration::get('SXDHL_USER_LIVE')),
            'dhl_pw_live' => str_rot13(Configuration::get('SXDHL_PW_LIVE')),
            'dhk_ekp_live' => Configuration::get('SXDHL_EKP_LIVE'),
            'dhl_billing_live' => Configuration::get('SXDHL_BILLING_LIVE'),
            'dhl_national_live' => Configuration::get('SXDHL_NATIONAL_LIVE'),
            'dhl_international_live' => Configuration::get('SXDHL_INTERNATIONAL_LIVE'),
            'dhl_product_national_live' => Configuration::get('SXDHL_PRODUCT_NATIONAL_LIVE'),
            'dhl_product_international_live' => Configuration::get('SXDHL_PRODUCT_INTERNATIONAL_LIVE'),
            'dhl_sender_company' => Configuration::get('SXDHL_SENDER_COMPANY'),
            'dhl_sender_street' => Configuration::get('SXDHL_SENDER_STREET'),
            'dhl_sender_house' => Configuration::get('SXDHL_SENDER_HOUSE'),
            'dhl_sender_zip' => Configuration::get('SXDHL_SENDER_ZIP'),
            'dhl_sender_city' => Configuration::get('SXDHL_SENDER_CITY'),
            'dhl_sender_country' => Configuration::get('SXDHL_SENDER_COUNTRY'),
            'dhl_sender_iso' => Configuration::get('SXDHL_SENDER_ISO'),
        ));

        $this->output .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/menu.tpl');

        return $this->output;
    }

    public function hookDisplayAdminOrderMain($params)
    {
        $mode = Configuration::get('SXDHL_MODE'); // test or production
        $order = new Order($params['id_order']);

        if (Tools::isSubmit('create_label')) {
            require_once($this->local_path . 'classes/createlabel.php');

            // Access to DHL
            if ($mode == "production") { // live umgebung
                $dhlaccess = array(
                    'user' => str_rot13(Configuration::get('SXDHL_USER_LIVE')),
                    'signature' => str_rot13(Configuration::get('SXDHL_PW_LIVE')),
                    'ekp' => Configuration::get('SXDHL_EKP_LIVE'),
                );
            } else { // Test Umgebung
                $dhlaccess = array(
                    'user' => str_rot13(Configuration::get('SXDHL_USER_DEV')),
                    'signature' => str_rot13(Configuration::get('SXDHL_PW_DEV')),
                    'ekp' => Configuration::get('SXDHL_EKP_DEV'),
                );
            }

            // Sender
            $sender = array(
                "name" => Configuration::get('SXDHL_SENDER_COMPANY'),
                "street" => Configuration::get('SXDHL_SENDER_STREET'),
                "house" => Configuration::get('SXDHL_SENDER_HOUSE'),
                "zip" => Configuration::get('SXDHL_SENDER_ZIP'),
                "city" => Configuration::get('SXDHL_SENDER_CITY'),
                "country" => Configuration::get('SXDHL_SENDER_COUNTRY'),
                "iso" => Configuration::get('SXDHL_SENDER_ISO')
            );

            // Empfaenger
            $_get_address = "select "
                . " a.company, a.lastname, a.firstname, a.address1, "
                . " a.address2, a.postcode, a.city, b.name as country, c.iso_code"
                . " from " . _DB_PREFIX_ . "address a, " . _DB_PREFIX_ . "country_lang b, " . _DB_PREFIX_ . "country c "
                . " where id_address = '" . $order->id_address_delivery . "' "
                . " and a.id_country = b.id_country "
                . " and a.id_country = c.id_country "
                . " and b.id_lang = '" . Configuration::get('PS_LANG_DEFAULT') . "' ";
            $result = Db::getInstance()->executeS($_get_address);

            foreach ($result as $addresses) {
                $receiver = $addresses;
            }

            // Details
            $detail = array(
                'reference' => $order->reference,
                'weight' => Tools::getValue('weight')
            );

            // Create Label
            $label = new CreateLabel($dhlaccess, $mode);
            $mylabel = $label->create($sender, $receiver, $detail);

            // label download
            $file = $order->reference . '-' . $mylabel->getShipmentNumber() . '.pdf';
            $ch = curl_init($mylabel->getLabel());
            $zieldatei = fopen(_PS_ROOT_DIR_ . '/modules/sxdhl/label/' . $file, "w");
            curl_setopt($ch, CURLOPT_FILE, $zieldatei);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
            curl_exec($ch);
            fclose($zieldatei);

            // show label
            if (Configuration::get('SXDHL_AUTO_VIEW') == '1') {
                $this->context->smarty->assign(array(
                    'base_uri' => __PS_BASE_URI__,
                    'file' => $file,
                ));
                echo $this->display(__FILE__, 'new_label.tpl');
            }

            // DB Eintrag
            $_label_insert = "insert into " . _DB_PREFIX_ . "sxdhl values ( "
                . "'', "
                . "'" . $params['id_order'] . "', "
                . "'" . $mylabel->getShipmentNumber() . "', "
                . "'" . $file . "', "
                . "now(), "
                . "now(), "
                . "'create' )";
            Db::getInstance()->execute($_label_insert);

            // Bestell Status setzen, Tracking eintragen
            $order->shipping_number = $mylabel->getShipmentNumber();
            $order->setWsShippingNumber($mylabel->getShipmentNumber());
            $order->update();
        }

        // schauen, ob es schon ein Label gibt, dann zum Download anbieten
        $_label_select = "select * from " . _DB_PREFIX_ . "sxdhl where id_order = '" . $params['id_order'] . "' ";
        $result = Db::getInstance()->executeS($_label_select);
        $label_html = array();
        $label_detail = array();
        // $tracking_update = 0;

        if (count($result) > 0) {
            for ($i = 0; $i < count($result); $i++) {
                $label_html[$i]['download_path'] = __PS_BASE_URI__ . 'modules/sxdhl/label/';
                $label_html[$i]['shipping_number'] = $result[$i]['shipping_number'];
                $label_html[$i]['label_file'] = $result[$i]['label_file'];
                $label_html[$i]['label_create'] = $result[$i]['label_create'];
                $label_html[$i]['label_update'] = $result[$i]['label_update'];
                $label_html[$i]['state'] = $result[$i]['state'];

                $_sql_get_state_detail = "select * from " . _DB_PREFIX_ . "sxdhl_detail "
                    . " where id_sxdhl = '" . $result[$i]['id_sxdhl'] . "' order by id_sxdhl_detail";
                $_result_get_state_detail = Db::getInstance()->executeS($_sql_get_state_detail);
                $j = 0;
                foreach ($_result_get_state_detail as $detail) {
                    $label_detail[$i][$j]['id_sxdhl_detail'] = $detail['id_sxdhl_detail'];
                    $label_detail[$i][$j]['id_sxdhl'] = $detail['id_sxdhl'];
                    $label_detail[$i][$j]['name'] = $detail['name'];
                    $label_detail[$i][$j]['event_timestanp'] = $detail['event_timestanp'];
                    $label_detail[$i][$j]['event_status'] = $detail['event_status'];
                    $label_detail[$i][$j]['event_text'] = $detail['event_text'];
                    $label_detail[$i][$j]['event_text'] = $detail['event_text'];
                    $label_detail[$i][$j]['ice'] = $detail['ice'];
                    $label_detail[$i][$j]['ric'] = $detail['ric'];
                    $label_detail[$i][$j]['event_location'] = $detail['event_location'];
                    $label_detail[$i][$j]['event_country'] = $detail['event_country'];
                    $label_detail[$i][$j]['ruecksendung'] = $detail['ruecksendung'];
                    $label_detail[$i][$j]['date_upd'] = $detail['date_upd'];
                    $j++;
                }
            }
        }

        // URL
        $tmp = explode("?", $this->context->link->getAdminLink('AdminOrders'));
        $this->context->smarty->assign(array(
            'label_html' => $label_html,
            'label_detail' => $label_detail,
            'create_url' => $tmp[0] .$params['id_order']."/view?".$tmp[1],
        ));

        return $this->display(__FILE__, 'AdminOrder.tpl');
    }
}
