<?php

require_once '../../../wp-load.php';
require './twilio.php';

// get settings
$settings = get_option('phonoblogsettings');
$name = get_bloginfo('name');
$url = get_bloginfo('url').'/';
$gather_url = $url.str_replace(ABSPATH, '', __FILE__);
$transcribe_url = $url.dirname(str_replace(ABSPATH, '', __FILE__)).'/transcribe.php';
// check to make sure the number is allowed
if($settings['number'] != $_REQUEST['From']){
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
		<pause length="5" />
		<Say>Dicktate your post then press any key to continue. We're listening.</Say>
		<Record transcribe="true" transcribeCallback="<?php echo $transcribe_url.'?type=content'; ?>" maxLength="600" action="<?php echo $gather_url.'?step=3'; ?>" />
		
	<?php
	break;
	
	case 3: ?>
		<pause length="5" />
		<Gather action="<?php  echo $gather_url.'?step=4'; ?>" numDigits="1">
			<Say>Press 1, to publish your post.</Say>
			<Say>Press 2, to save your post as a draft</Say>
		</Gather>
	<?php
	break;

	case 4:
		if($_REQUEST['Digits'] == 1){
			// find post by sid
			$sid = $_REQUEST['CallSid'];
			$posts = get_posts(array(
				'meta_query' => array(
					array(
						'key' => 'phonoblog_sid',
						'value' => $sid,
					)
				),
				'post_status' => 'any',
			));
			// if the post doesn't exist create one
			// and add the post_status meta but leave
			// it as a draft since none of the other
			// content is in place
			if(empty($posts)){

				$post = wp_insert_post(array(
					'post_title' => 'Pending',
					'post_content' => 'Pending',
					'post_author' => $settings['user'],
					'post_status' => 'draft',
				));

				add_post_meta($post, 'phonoblog_post_status', 'publish');
				add_post_meta($post, 'phonoblog_sid', $sid);

			}else{
				// if the post exists then get the status
				// of the title and content transcriptions
				$post = get_object_vars($posts[0]);
				$title_status = get_post_meta($post['ID'], 'phonoblog_title_status', true);
				$content_status = get_post_meta($post['ID'], 'phonoblog_content_status', true);

				// if everything is ready publish the post
				if(($title_status && $content_status) && $title_status == 'completed' && $content_status == 'completed'){
					$post['post_status'] ='publish';
					wp_insert_post($post);
				}
				// if not add the post status meta so that it can be
				// published when everything is ready
				add_post_meta($post['ID'], 'phonoblog_post_status', 'publish');
				
			}

		}elseif($_REQUEST['Digits'] == 2){
			// get post by sid
			$sid = $_REQUEST['CallSid'];
			$posts = get_posts(array(
				'meta_query' => array(
					array(
						'key' => 'phonoblog_sid',
						'value' => $sid,
					)
				),
				'post_status' => 'any',
			));

			// if post doesn't exist set create it and set 
			// the status meta
			if(empty($posts)){

				$post = wp_insert_post(array(
					'post_title' => 'Pending',
					'post_content' => 'Pending',
					'post_author' => $settings['user'],
					'post_status' => 'draft',
				));

				add_post_meta($post, 'phonoblog_post_status', 'publish');
				add_post_meta($post, 'phonoblog_sid', $sid);
			}else{
				// if post exists add the status meta
				$post = get_object_vars($posts[0]);
				add_post_meta($post['ID'], 'phonoblog_post_status', 'draft');
			}

		}
	break;
	
	default: ?>
		<Say>Hello and welcome to <?php echo $name; ?> please tell us the title of your post then press any key to continue.</Say>
		<Record transcribe="true" transcribeCallback="<?php echo $transcribe_url.'?type=title'; ?>" maxLength="30" action="<?php echo $gather_url.'?step=2'; ?>" />
		
	<?php
	break;
}

?>
</Response>