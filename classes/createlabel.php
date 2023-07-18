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

require_once(_PS_ROOT_DIR_ . '/modules/sxdhl/vendor/autoload.php');

// Require the Main-Class (other classes will included by this file)
use Petschko\DHL\BusinessShipment;
use Petschko\DHL\Credentials;
use Petschko\DHL\Receiver;
use Petschko\DHL\ReturnReceiver;
use Petschko\DHL\Sender;
use Petschko\DHL\Service;
use Petschko\DHL\ShipmentOrder;
use Petschko\DHL\ShipmentDetails;

class CreateLabel extends Module
{
    public function __construct($dhlaccess, $mode)
    {
        if ($mode == "production") {
            $this->testMode = false;
        } else {
            $this->testMode = Credentials::TEST_NORMAL;
        }
        $this->version = '2.2';
        $this->reference = 'SX-PS-DHL';
        $this->credentials = new Credentials();

        if (!$this->testMode) {
            $this->credentials->setUser($dhlaccess['user']);
            $this->credentials->setSignature($dhlaccess['signature']);
            $this->credentials->setEkp($dhlaccess['ekp']);
            $this->credentials->setApiUser('ps_sx_shipping_1');
            $this->credentials->setApiPassword('');
        } else {
            $this->credentials->setApiUser($dhlaccess['user']);
            $this->credentials->setApiPassword($dhlaccess['signature']);
            $this->credentials->setEkp($dhlaccess['ekp']);
        }
    }

    public function create($shipper, $recipient, $detail)
    {
        if (Tools::strtoupper($recipient['iso_code']) == Tools::strtoupper($shipper['iso'])) { // national
            $product_nr = Configuration::get('SXDHL_NATIONAL_DEV');
            $product = Configuration::get('SXDHL_PRODUCT_NATIONAL_DEV');
        } else { // international
            $product_nr = Configuration::get('SXDHL_INTERNATIONAL_DEV');
            $product = Configuration::get('SXDHL_PRODUCT_INTERNATIONAL_DEV');
        }
        $accounting = Configuration::get('SXDHL_BILLING_DEV');

        $service = new Service();
        $shipmentDetails = new ShipmentDetails($this->credentials->getEkp() .
            $product_nr . $accounting);

        $shipmentDetails->setProduct($product);
        $shipmentDetails->setCustomerReference($detail['reference']);
        $shipmentDetails->setWeight($detail['weight']); // kg

        $shipmentDetails->setService($service);

        // Set Sender
        $sender = new Sender();
        $sender->setName($shipper['name']);
        $sender->setStreetName($shipper['street']);
        $sender->setStreetNumber($shipper['house']);
        $sender->setZip($shipper['zip']);
        $sender->setCity($shipper['city']);
        $sender->setCountry($shipper['country']);
        $sender->setCountryISOCode($shipper['iso']);

        // Set Receiver
        $street_array = explode(" ", $recipient['address1']);
        $street = "";
        for ($i = 0; $i < count($street_array) - 1; $i++) {
            $street .= $street_array[$i] . " ";
        }
        $house = $street_array[count($street_array) - 1];
        $receiver = new Receiver();

        if (
            isset($recipient['company']) && Tools::strlen($recipient['company']) >
            0
        ) {
            $receiver->setName($recipient['company']);
            $receiver->setName2($recipient['firstname'] . " " . $recipient['lastname']);
        } else {
            $receiver->setName($recipient['firstname'] . " " . $recipient['lastname']);
        }

        if (
            isset($recipient['address2']) && Tools::strlen($recipient['address2']) >
            0
        ) {
            $receiver->setName3("(" . $recipient['address2'] . ")");
        }
        $receiver->setStreetName($street);
        $receiver->setStreetNumber($house);
        $receiver->setZip($recipient['postcode']);
        $receiver->setCity($recipient['city']);
        $receiver->setCountry($recipient['country']);
        $receiver->setCountryISOCode($recipient['iso_code']);

        $dhl = new BusinessShipment(
            $this->credentials,
            $this->testMode,
            $this->version
        );

        $shipmentOrder = new ShipmentOrder();
        $shipmentOrder->setSequenceNumber($this->reference); // Just needed to

        $shipmentOrder->setSender($sender);
        $shipmentOrder->setReceiver($receiver);
        $shipmentOrder->setShipmentDetails($shipmentDetails);
        $shipmentOrder->setLabelResponseType(BusinessShipment::RESPONSE_TYPE_URL);

        $dhl->addShipmentOrder($shipmentOrder);
        $response = $dhl->createShipment();
        if ($dhl->getErrors()) {
            return null;
        } else {
            return $response;
        }
    }
}