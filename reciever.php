<?php

require_once '../../../wp-config.php';
require_once ABSPASH.WPINC.'/post.php';
require './twilio.php';

// get settings
$settings = get_option('phonoblogsettings');
$name = get_bloginfo('name');
$url = get_bloginfo('url');
$gather_url = $url.str_replace(ABSPATH, '', __FILE__);
$transcribe_url = $url.dirname($filepath).'/transcribe.php';

// check to make sure the number is allowed
if($settings['phone'] != $_REQUEST['From']){
	header("Content-type: text/xml");
	
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	?>
	<Response>
		<Reject />
	</Response>
	<?php
	exit;
}


/* Render TwiML */
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Response>\n";
switch($_REQUEST['step']) {
	case 2: ?>
		<Say>Dicktate your post then press any key to continue. We're listening.</Say>
		<Gather action="<?php  echo $url.'?step=3'; ?>" numDigits="1">
			<Record transcribe="true" transcribeCallback="<?php echo $transcribe_url.'?type=content'; ?>" maxLength="600" />
		</Gather>
	<?php
	break;
	
	case 3: ?>
		<Gather action="<?php  echo $url.'?step=4'; ?>" numDigits="1">
			<Say>Press 1, to publish your post.</Say>
			<Say>Press 2, to save your post as a draft</Say>
		</Gather>
	<?php
	break;

	case 4:
		
	break;
	
	default: ?>
		<Say>Hello and welcome to <?php echo $name; ?> please tell us the title of your post then press any key to continue.</Say>
		<Gather action="<?php echo $url.'?step=2'; ?>" numDigits="1">
			<Record transcribe="true" transcribeCallback="<?php echo $transcribe_url.'?type=title'; ?>" maxLength="30" />
		</Gather>
	<?php
	break;
}

?>
</Response>