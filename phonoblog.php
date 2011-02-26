<?php

require 'twilio.php';

/* Define Menu */
$menu = array();
$menu['default'] = array('record-title', 'record-post', 'choose-status');

/* Get the menu node, index, and url */
$node = $_REQUEST['node'];
$index = (int) $_REQUEST['Digits'];
$url = 'http://'.dirname($_SERVER["SERVER_NAME"].$_SERVER['PHP_SELF']).'/phonemenu.php';

/* Check to make sure index is valid */
if(isset($web[$node]) || count($web[$node]) >= $index && !is_null($_REQUEST['Digits'])){
	$destination = $web[$node][$index];
}else{
	$destination = NULL;
}

/* Render TwiML */
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Response>\n";
switch($destination) {
	case 'hours': ?>
		<Say>Initech is open Monday through Friday, 9am to 5pm</Say>
		<Say>Saturday, 10am to 3pm and closed on Sundays</Say>
	<?php
	break;
	
	case 'location': ?>
		<Say>Initech is located at 101 4th St in San Francisco California</Say>
		<Gather action="<?php echo 'http://' . dirname($_SERVER["SERVER_NAME"] .  $_SERVER['PHP_SELF']) . '/phonemenu.php?node=location'; ?>" numDigits="1">
			<Say>For directions from the East Bay, press 1</Say>
			<Say>For directions from San Jose, press 2</Say>
		</Gather>
	<?php
	break;
	
	case 'east-bay': ?>
		<Say>Take BART towards San Francisco / Milbrae. Get off on Powell Street. Walk a block down 4th street</Say>
	<?php
	break;
	
	case 'san-jose': ?>
		<Say>Take Cal Train to the Milbrae BART station. Take any Bart train to Powell Street</Say>
	<?php
	break;
	
	case 'duck'; ?>
		<Play>duck.mp3</Play>
	<?php
	break;
	
	case 'receptionist'; ?>
		<Say>Please wait while we connect you</Say>
		<Dial>NNNNNNNNNN</Dial>
	<?php
	break;
	
	default: ?>
		<Gather action="<?php echo 'http://' . dirname($_SERVER["SERVER_NAME"] .  $_SERVER['PHP_SELF']) . '/phonemenu.php?node=default'; ?>" numDigits="1">
		<Say>Hello and welcome to the Initech Phone Menu</Say>
		<Say>For business hours, press 1</Say>
		<Say>For directions, press 2</Say>
		<Say>To hear a duck quack, press 3</Say>
		<Say>To speak to a receptionist, press 0</Say>
		</Gather>
	<?php
	break;
}

if($destination && $destination != 'receptionist') { ?>
	<Pause/>
	<Say>Main Menu</Say>
	<Redirect><?php echo 'http://' . dirname($_SERVER["SERVER_NAME"] .  $_SERVER['PHP_SELF']) . '/phonemenu.php' ?></Redirect>
<?php }