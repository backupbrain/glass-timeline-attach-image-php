<?php
// oauth2callback/index.php


require('../settings.php');

require_once('../classes/Google_OAuth2_Token.class.php');
require_once('../classes/Google_Timeline_Item.class.php');
	
/**
 * the OAuth server should have brought us to this page with a $_GET['code']
 */
if(isset($_GET['code'])) {
    // try to get an access token
    $code = $_GET['code'];
 
	// authenticate the user
	$Google_OAuth2_Token = new Google_OAuth2_Token();
	$Google_OAuth2_Token->code = $code;
	$Google_OAuth2_Token->client_id = $settings['oauth2']['oauth2_client_id'];
	$Google_OAuth2_Token->client_secret = $settings['oauth2']['oauth2_secret'];
	$Google_OAuth2_Token->redirect_uri = $settings['oauth2']['oauth2_redirect'];
	$Google_OAuth2_Token->grant_type = "authorization_code";

	try {
		$Google_OAuth2_Token->authenticate();
	} catch (Exception $e) {
		// handle this exception
		print_r($e);
	}

	// A user just logged in.  Let's insert a timeline card and attachment
	if ($Google_OAuth2_Token->authenticated) {
		
		// set up the new Timeline item with text content
		$Google_Timeline_Item = new Google_Timeline_Item($Google_OAuth2_Token);
		$Google_Timeline_Item->text = "This is an annotation";
			
		// attach a file.  Since the object files that process the image
		// live in a different directry, we need to feed an absolute path
		$filename = realpath(dirname( __FILE__ ).'/../assets/img/google-glass.jpg');
		$Google_Timeline_Item->addAttachment($filename);
		
		
		// insert the timeline item
		$Google_Timeline_Item->insert();
		
		// the insert() function syncs the timeline info from the Mirror API
		// that includes the IDs of the attachments, which we can display naw...
		
        // fetch the attachments - we will display them inline later
        if ($Google_Timeline_Item->attachments) {
                 foreach ($Google_Timeline_Item->attachments as $Attachment) {
                        $Attachment->fetchContent();
                }
        }
		
		
		
	}
}
?>
<h2>Timeline Item</h2>
<dl>
	<dt>ID</dt>
	<dd><?= $Google_Timeline_Item->id; ?></dd> 
	
	<dt>Created</dt>
	<dd><?= $Google_Timeline_Item->created; ?></dd>
	
	<? if ($Google_Timeline_Item->text) { ?>
		<dt>Text Content</dt>
		<dd><?= $Google_Timeline_Item->text; ?></dd>
	<? } ?>
	
	<? if ($Google_Timeline_Item->attachments) { ?>
	<dt>Attachments</dt>
	<? $numAttachments = count($Google_Timeline_Item->attachments); ?>
	Found <?= $numAttachments; ?> attachment<? if ($numAttachments !== 1) { ?>s<? } ?>:
	<? foreach ($Google_Timeline_Item->attachments as $Attachment) { ?>	
        <?
        // we can display images inline in our HTML
        // http://stackoverflow.com/questions/11474346/how-to-encode-images-within-html
        $imageType = $Attachment->contentType;
        $imagedata = base64_encode($Attachment->content);
        ?>
		<dd><?= $Attachment->id; ?>: <?= $Attachment->contentType; ?></dd>
        <dd><img alt="image" src="data:<?= $imagetype; ?>;base64,<?= $imagedata; ?>" width="320" height="234" /></dd>
	<? } ?>
	<? } ?>
</dl>