<?php
if (!isset($_REQUEST['TranscriptionStatus'])) {
	echo "Must specify transcription status";
	exit;
}

if (!isset($_REQUEST['RecordingUrl'])) {
	echo "Must specify recording url";
	exit;
}

require_once '../../../wp-config.php';
require_once ABSPATH.WPINC.'/post.php';
require_once ABSPATH.WPINC.'/query.php';
require './twilio.php';

if($_REQUEST['type'] == 'title'){
	
	// find post by sid
	$sid = $_REQUEST['Sid'];
	// get transcription status and text
	$transcription_status = (strtolower($_REQUEST['TranscriptionStatus']) == 'completed') ? 'completed' : 'error';
	$transcription_text = $_REQUEST['TranscriptionText'] ? $_REQUEST['TranscriptionText'] : 'Error Transcribing Title';
	
	$posts = get_posts(array(
		'meta_query' => array(
			'key' => 'phonoblog_sid',
			'value' => $sid
		)
	));
	// if the post doesn't exist create one
	// and add the post_status meta but leave
	// it as a draft since none of the other
	// content is in place
	if(count($posts) == 0){

		$post = wp_insert_post(array(
			'post_title' => $transcription_text,
			'post_content' => 'Pending',
			'post_author' => $settings['user']
		));

		add_post_meta($post, 'phonoblog_title_status', $transcription_status);
		add_post_meta($post, 'phonoblog_title_recording', $_REQUEST['RecordingUrl']);

	}else{
		// if the post exists then get the status
		// of the title and content transcriptions
		$post = get_object_vars($posts[0]);
		$post_status = get_post_meta($post['ID'], 'phonoblog_post_status', true);
		$content_status = get_post_meta($post['ID'], 'phonoblog_content_status', true);

		// if everything is ready publish the post
		if(($post_status && $content_status) && $post_status == 'publish' && $content_status == 'completed'){
			$post['post_status'] ='publish';
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
	$sid = $_REQUEST['Sid'];
	// get transcription status and text
	$transcription_status = (strtolower($_REQUEST['TranscriptionStatus']) == 'completed') ? 'completed' : 'error';
	$transcription_text = $_REQUEST['TranscriptionText'] ? $_REQUEST['TranscriptionText'] : 'Error Transcribing Title';
	
	$posts = get_posts(array(
		'meta_query' => array(
			'key' => 'phonoblog_sid',
			'value' => $sid
		)
	));
	// if the post doesn't exist create one
	// and add the post_status meta but leave
	// it as a draft since none of the other
	// content is in place
	if(count($posts) == 0){

		$post = wp_insert_post(array(
			'post_title' => 'Pending',
			'post_content' => $transcription_text,
			'post_author' => $settings['user']
		));

		add_post_meta($post, 'phonoblog_content_status', $transcription_status);
		add_post_meta($post, 'phonoblog_content_recording', $_REQUEST['RecordingUrl']);

	}else{
		// if the post exists then get the status
		// of the title and content transcriptions
		$post = get_object_vars($posts[0]);
		$post_status = get_post_meta($post['ID'], 'phonoblog_post_status', true);
		$title_status = get_post_meta($post['ID'], 'phonoblog_title_status', true);

		// if everything is ready publish the post
		if(($post_status && $title_status) && $post_status == 'publish' && $title_status == 'completed'){
			$post['post_status'] ='publish';
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
}else{
	exit;
}
?>