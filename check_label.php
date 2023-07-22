<?php
header("Content-type: text/plain");

// exit;

// alle Fehler anzeigen
error_reporting(E_ALL);
// Fehler in der Webseite anzeigen (nicht in Produktion verwenden)
ini_set('display_errors', 'On');

require(dirname(__FILE__).'/../../config/config.inc.php');
if (!defined('_PS_VERSION_')) {
    exit;
}
require(dirname(__FILE__).'/classes/tracking.php');

$db = Db::getInstance();

$updintval = 7200;
$mode = "production"; // sandbox or production
$track = new Tracking($mode);

$state_shipped = '4';
$state_delivered = "20";
$state_done = "21";

/* reparatur
$sql = "select id_sxdhl, ice, date_upd from ps_sxdhl_detail";
$query = $db->executeS($sql);
foreach ($query as $dhl) {
    print_r($dhl);

    $update = "update ps_sxdhl set label_update = '".$dhl['date_upd']."', state = '".$dhl['ice']."' where id_sxdhl = '".$dhl['id_sxdhl']."'";
    $db->executeS($update);
}
exit;
*/

$disable_order_state = array(0, 5, 6, 7, 20, 21, 24, 26, 28, 31, 41);
$_sql_get_orders = "select a.id_order, a.shipping_number, a.current_state"
    . " from " . _DB_PREFIX_ . "orders a, " . _DB_PREFIX_ . "sxdhl b"
    . " where a.shipping_number != '' "
    . " and a.id_order = b.id_order "
    . " and (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(b.label_update)) >= " .$updintval. "";
//    . " and a.id_order = '4211' ";
foreach ($disable_order_state as $state) {
    $_sql_get_orders .= " and a.current_state != '".$state."'";
}
$_sql_get_orders .= " order by a.id_order";
$_get_orders = $db->executeS($_sql_get_orders);

$i = 0;
while($i < 1 && count($_get_orders) > 0) {
    $id_order = $_get_orders[$i]['id_order'];
    $order = new Order($id_order);

    echo "Ich bearbeite $id_order ... \n";

    $i = 1;
    // DHL pakete holen, können auch viele sein
    $_sql_get_dhl_label = "select id_sxdhl, shipping_number, label_update, state "
        . " from ps_sxdhl "
        . " where id_order = '$id_order'"
        . " and (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(label_update)) >= " .$updintval. "";
    $_get_dhl_label = $db->executeS($_sql_get_dhl_label);

    foreach ($_get_dhl_label as $mylabel) {
        $id_sxdhl = $mylabel['id_sxdhl'];
        $shipping_number = $mylabel['shipping_number'];
        $label_update = $mylabel['label_update'];
        $state = $mylabel['state'];

        if($state != "DLVRD")
        {
            $tracking_result = $track->getState($shipping_number);

            if (is_array($tracking_result)) {
                foreach ($tracking_result as $tracking_state) {
                    $sql_track_detail = "insert into " . _DB_PREFIX_ . "sxdhl_detail "
                        . " values('', "
                        . " '" . $id_sxdhl . "', "
                        . " '" . $tracking_state['name'] . "',"
                        . " '" . $tracking_state['event_timestamp'] . "',"
                        . " '" . $tracking_state['event_status'] . "',"
                        . " '" . $tracking_state['event_text'] . "',"
                        . " '" . $tracking_state['event_short_status'] . "',"
                        . " '" . $tracking_state['ice'] . "',"
                        . " '" . $tracking_state['ric'] . "',"
                        . " '" . $tracking_state['event_location'] . "',"
                        . " '" . $tracking_state['event_country'] . "',"
                        . " '" . $tracking_state['ruecksendung'] . "', "
                        . " now() "
                        . " ) "
                        . "ON DUPLICATE KEY UPDATE date_upd = now()";

                    $db->execute($sql_track_detail);
                    
                    if($tracking_state['ice'] == "SHRCU" || $tracking_state['ice'] == "PCKDU") // Einlieferung Filiale 
                    {
                        echo "$shipping_number wurde verschickt... \n";
                        $_sql_update_label = "update " . _DB_PREFIX_ . "sxdhl set state = '".$tracking_state['ice']."' "
                        . " where id_sxdhl = '$id_sxdhl'";
                        $db->execute($_sql_update_label);
                        if($order->current_state != $state_shipped)
                        {
                            echo "order muss auf versendet gesetzt werden ... \n";
                            $order->setWsCurrentState($state_shipped);
                            $order->update();

                            $id_carrier = $order->id_carrier;
                            
                            $order_carrier = new OrderCarrier($id_carrier);
                            $order_carrier->sendInTransitEmail($order);
                        } else {
                            echo "status ist schon auf versendet .. \n";
                        }
                        
                    } else if($tracking_state['ice'] == "SRTED") {
                        echo "$shipping_number in Zustellung... \n";
                    } else if($tracking_state['ice'] == "DLVRD") {
                        echo "$shipping_number zugestellt... \n";
                        $_sql_update_label = "update " . _DB_PREFIX_ . "sxdhl set state = '".$tracking_state['ice']."' "
                        . " where id_sxdhl = '$id_sxdhl'";
                        $db->execute($_sql_update_label);
                        if($order->current_state == $state_shipped)
                        {
                            $db->execute($_sql_update_label);
                            $order->setWsCurrentState($state_delivered);
                            $order->update();
                        }
                    }
                    
                }
            }
            $_sql_update_label = "update " . _DB_PREFIX_ . "sxdhl set label_update = now() "
                        . " where id_sxdhl = '$id_sxdhl'";
            $db->execute($_sql_update_label);
        }
    }
}


exit;
// status auf endgültig setzen

$rueckgabe = 14;

$_sql_get_orders_recv = "select a.id_order, a.reference, c.event_timestanp, c.ice, c.event_location "
	. " from ps_orders a, ps_sxdhl b, ps_sxdhl_detail c "
	. " where a.current_state = '20' "
	. " and a.id_order = b.id_order "
	. " and b.id_sxdhl = c.id_sxdhl "
	. " and c.ice = 'DLVRD'";
$_get_orders_recv = $db->executeS($_sql_get_orders_recv);

foreach ($_get_orders_recv as $_myorder) {
	$id_order = $_myorder['id_order'];
	$reference = $_myorder['reference'];
	$event_timestanp = $_myorder['event_timestanp'];
	$ice = $_myorder['ice'];
	$event_location = $_myorder['event_location'];

	$_tmp = explode(" ", $event_timestanp);
	$_datum = explode(".", $_tmp[0]);
	$datum = $_datum[2]."-".$_datum[1]."-".$_datum[0];
	$zeitraum = $datum;

	$zugestellt = date_create($datum);
	$heute = date_create(date("Y-m-d"));
	$zeitraum = date_diff($zugestellt, $heute);
	if($zeitraum->days >= $rueckgabe)
	{
		$order = new Order($id_order);
		if($order->current_state == '20')
		{
			$order->setWsCurrentState($state_done);
            		$order->update();

            		echo "Order $id_order ist endgültig \n";
		} 
	} else {
            echo "Order $id_order kann noch zurück geschickt werden. \n";
    }
} // status auf endgültig setzen ...



