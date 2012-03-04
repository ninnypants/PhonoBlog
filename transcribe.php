<?php
if (!isset($_REQUEST['TranscriptionStatus'])) {
	echo "Must specify transcription status";
	exit;
}

if (!isset($_REQUEST['RecordingUrl'])) {
	echo "Must specify recording url";
	exit;
}

require_once '../../../wp-load.php';
require './twilio.php';

$settings = get_option('phonoblogsettings');

if($_REQUEST['type'] == 'title'){
	
	// find post by sid
	$sid = $_REQUEST['CallSid'];
	// get transcription status and text
	$transcription_status = (strtolower($_REQUEST['TranscriptionStatus']) == 'completed') ? 'completed' : 'error';
	$transcription_text = $_REQUEST['TranscriptionText'] ? $_REQUEST['TranscriptionText'] : 'Error Transcribing Title';
	
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
			'post_title' => $transcription_text,
			'post_content' => 'Pending',
			'post_author' => $settings['user'],
			'post_status' => 'draft',
		));

		add_post_meta($post, 'phonoblog_title_status', $transcription_status);
		add_post_meta($post, 'phonoblog_title_recording', $_REQUEST['RecordingUrl']);
		add_post_meta($post, 'phonoblog_sid', $sid);

	}else{
		// if the post exists then get the status
		// of the title and content transcriptions
		$post = get_object_vars($posts[0]);
		$post_status = get_post_meta($post['ID'], 'phonoblog_post_status', true);
		$content_status = get_post_meta($post['ID'], 'phonoblog_content_status', true);

		// if everything is ready publish the post
		if(($post_status && $content_status) && $post_status == 'publish' && $content_status == 'completed'){
			$post['post_status'] = $post_status;
			$post['post_title'] = $transcription_text;
			wp_insert_post($post);
		}else{
			$post['post_title'] = $transcription_text;
			wp_insert_post($post);
		}
		
		// if not add the post status meta so that it can be
		// published when everything is ready
		add_post_meta($post['ID'], 'phonoblog_title_status', $transcription_status);
		add_post_meta($post['ID'], 'phonoblog_title_recording', $_REQUEST['RecordingUrl']);
		
	}
}elseif($_REQUEST['type'] == 'content'){
	// find post by sid
	$sid = $_REQUEST['CallSid'];
	// get transcription status and text
	$transcription_status = (strtolower($_REQUEST['TranscriptionStatus']) == 'completed') ? 'completed' : 'error';
	$transcription_text = $_REQUEST['TranscriptionText'] ? $_REQUEST['TranscriptionText'] : 'Error Transcribing Title';
	
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
			'post_content' => $transcription_text,
			'post_author' => $settings['user'],
			'post_status' => 'draft',
		));

		add_post_meta($post, 'phonoblog_content_status', $transcription_status);
		add_post_meta($post, 'phonoblog_content_recording', $_REQUEST['RecordingUrl']);
		add_post_meta($post, 'phonoblog_sid', $sid);

	}else{
		// if the post exists then get the status
		// of the title and content transcriptions
		$post = get_object_vars($posts[0]);
		$post_status = get_post_meta($post['ID'], 'phonoblog_post_status', true);
		$title_status = get_post_meta($post['ID'], 'phonoblog_title_status', true);

		// if everything is ready publish the post
		if(($post_status && $title_status) && $post_status == 'publish' && $title_status == 'completed'){
			$post['post_status'] = $post_status;
			$post['post_content'] = $transcription_text;
			wp_insert_post($post);
		}else{
			$post['post_content'] = $transcription_text;
			wp_insert_post($post);
		}
		
		// if not add the post status meta so that it can be
		// published when everything is ready
		add_post_meta($post['ID'], 'phonoblog_content_status', $transcription_status);
		add_post_meta($post['ID'], 'phonoblog_content_recording', $_REQUEST['RecordingUrl']);
	}
}else{
	exit;
}
?>