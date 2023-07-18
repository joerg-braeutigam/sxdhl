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

class Tracking extends Module
{
    public function __construct($mode = "sandbox")
    {
        $this->mode = $mode;
        $this->endpoint = 'https://cig.dhl.de/services/' . $this->mode . '/rest/sendungsverfolgung';

        if ($this->mode == "production") {
            $this->username = ''; // zt number for production
            $this->password = ''; // zt password for production
            $this->appname = '';
            $this->apppass = '';
            $this->auth = '';
        } else {
            $this->username = ''; // zt number
            $this->password = ''; // zt password
            $this->appname = '';
            $this->apppass = '';
            $this->auth = '';
        }
    }

    public function getState($tracking)
    {
        if ($this->mode == "production") {
            $this->tracking = $tracking;
        } else {
            $this->tracking = "00340434161094015902";
        }

        $payload = simplexml_load_string('<?xml version="1.0" encoding="UTF-8" standalone="no"?>'
            . ' <data appname="' . $this->username . '" language-code="de" password="'
            . $this->password . '" piece-code="' . $this->tracking . '" request="d-get-piece-detail"/>');

        /* base64_encode( $this->appname.":".$this->apppass ) => $this->auth */
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Authorization: Basic " . $this->auth
            )
        );

        $context = stream_context_create($opts);
        $response = Tools::file_get_contents($this->endpoint . '?'
            . http_build_query(array('xml' => $payload->saveXML())), false, $context);
        $responseXml = simplexml_load_string($response);

        $response_array = array();
        $i = 0;
        if (Tools::strlen((string) $responseXml->attributes()->{'error'}) == 0) {
            foreach ($responseXml->data->data->data as $event) {
                $response_array[$i]['name'] = (string) $event->attributes()->{'name'};
                $response_array[$i]['event_timestamp'] = (string) $event->attributes()->{'event-timestamp'};
                $response_array[$i]['event_status'] = (string) $event->attributes()->{'event-status'};
                $response_array[$i]['event_text'] = (string) $event->attributes()->{'event-text'};
                $response_array[$i]['event_short_status'] = (string) $event->attributes()->{'event-short-status'};
                $response_array[$i]['ice'] = (string) $event->attributes()->{'ice'};
                $response_array[$i]['ric'] = (string) $event->attributes()->{'ric'};
                $response_array[$i]['event_location'] = (string) $event->attributes()->{'event-location'};
                $response_array[$i]['event_country'] = (string) $event->attributes()->{'event-country'};
                $response_array[$i]['event_country'] = (string) $event->attributes()->{'event-country'};
                $response_array[$i]['ruecksendung'] = (string) $event->attributes()->{'ruecksendung'};
                $i++;
            }
        } else {
            return null;
        }

        return $response_array;
    }
}