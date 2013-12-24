<?php
// classes/Google_Timeline_Item.class.php
require_once('Google_Menu_Item.class.php');
require_once('Google_Contact.class.php');
require_once('Google_Location.class.php');
require_once('Google_Notification.class.php');
require_once('Google_Timeline_Item_Attachment.class.php');

/**
 * Google Timeline card
 * https://www.googleapis.com/mirror/v1/timeline
 * 
 * Requires that user has been OAuth authenticated
 */
class Google_Timeline_Item {
	
	const URL = 'https://www.googleapis.com/mirror/v1/timeline';
	const UPLOAD_URL = 'https://www.googleapis.com/upload/mirror/v1/timeline';
	
	const OPERATION_UPDATE = "UPDATE";
	const OPERATION_INSERT = "INSERT";
	const OPERATION_DELETE = "DELETE";
	
	const COLLECTION_TIMELINE = "timeline";
	const COLLECTION_LOCATIONS = "locations";
	
	// this is the scope required to access the userinfo
	public static $scopes = array(
		'https://www.googleapis.com/auth/glass.timeline'
	); 
	
		
		
	// we can only grab userinfo from an authenticated user
	public $Google_OAuth2_Token;
	
	// these are the variables that will come back from the server
	public $kind = 'mirror#timelineItem',
		$id,
		$selfLink,
		$created,
		$updated,
		$displayTime,
		$etag,
		$html,
		$text,
		$notification,
		$recipients = array(),
		$attachments = array(),
		$menuItems = array(),
		$pinScore,
		$sourceItemId,
		$speakableText,
		$speakableType,
		$title,
		$location,
		$isPinned,
		$isDeleted,
		$isBundleCover,
		$inReplyTo,
		$creator,
		$canonicalUrl,
		$bundleId;


		
	/**
	 * Use the authenticated Google_OAuth2_Token
	 */
	public function __construct($Google_OAuth2_Token) {
		$this->notification = new Google_Notification();
		$this->Google_OAuth2_Token = $Google_OAuth2_Token;
	}

	public function fromJSONObject($jsonObject) {
		$this->kind = $jsonObject->kind;
		$this->id = $jsonObject->id;
		$this->selfLink = $jsonObject->selfLink;
		$this->created = $jsonObject->created;
		$this->updated = $jsonObject->updated;
		$this->displayTime = $jsonObject->displayTime;
		
		$this->html = $jsonObject->html;
		$this->text = $jsonObject->text;
		
		
		$this->pinScore = $jsonObject->pinScore;
		$this->sourceItemId = $jsonObject->sourceItemId;
		$this->speakableText = $jsonObject->speakableText;
		$this->speakableType = $jsonObject->speakableType;
		$this->title = $jsonObject->title;
		$this->isPinned = $jsonObject->isPinned;
		$this->isDeleted = $jsonObject->isDeleted;
		$this->isBundleCover = $jsonObject->isBundleCover;
		$this->inReplyTo = $jsonObject->inReplyTo;
		$this->creator = $jsonObject->creator;
		$this->canonicalUrl = $jsonObject->canonicalUrl;
		$this->bundleId = $jsonObject->bundleId;
		
		$this->notification = null;
		if ($jsonObject->notification) {
			$this->notification = new Google_Notification();
			$this->notification->fromJSONObject($jsonObject->notification);
		}
		
		$this->recipients = array();
		if ($jsonObject->recipients) foreach ($jsonObject->recipients as $recipient) {
			$Recipient = new Google_Contact();
			$Recipient->fromJSONObject($recipient);
			$this->recipients[] = $Recipient;
		}
		
		
		$this->attachments = array();
		if ($jsonObject->attachments) foreach ($jsonObject->attachments as $attachment) {
			$Attachment = new Google_Timeline_Item_Attachment($this->Google_OAuth2_Token);
			$Attachment->fromJSONObject($attachment);
			$this->attachments[] = $Attachment;
		}
		
		
		$this->location = null;
		if ($jsonObject->location) {
			$this->location = new Google_Location();
			$this->location->fromJSONObject($jsonObject->location);
		}
		
		
		$this->menuItems = array();
		if ($jsonObject->menuItems) foreach ($jsonObject->menuItems as $menuItem) {
			$MenuItem = new Google_Menu_Item();
			$MenuItem->fromJSONObject($menuItem);
			$this->menuItems[] = $MenuItem;
		}
		
		
	//	$Recipient = new Google_Timeline_MenuItem();
		
	}
	
	
	public function fromJSON($json) {
		$this->fromJSONObject(json_decode($json));
	}
	
	public function toJSONObject() {
		
        // this will be converted to the JSON you saw above
		if ($this->kind) $postData['kind'] = $this->kind;
		if ($this->selfLink) $postData['selfLink'] = $this->selfLink;
		if ($this->created) $postData['created'] = $this->created;
		if ($this->updated) $postData['updated'] = $this->updated;
		if ($this->displayTime) $postData['displayTime'] = $this->displayTime;
		if ($this->etag) $postData['etag'] = $this->etag;
		if ($this->html) $postData['html'] = $this->html;
		if ($this->text) $postData['text'] = $this->text;
		if ($this->pinScore) $postData['pinScore'] = $this->pinScore;
		if ($this->sourceItemId) $postData['sourceItemId'] = $this->sourceItemId;
		if ($this->speakableText) $postData['speakableText'] = $this->speakableText;
		if ($this->speakableType) $postData['speakableType'] = $this->speakableType;
		if ($this->title) $postData['title'] = $this->title;
		if ($this->location) $postData['location'] = $this->location;
		if ($this->isPinned) $postData['isPinned'] = $this->isPinned;
		if ($this->isDeleted) $postData['isDeleted'] = $this->isDeleted;
		if ($this->isBundleCover) $postData['isBundleCover'] = $this->isBundleCover;
		if ($this->inReplyTo) $postData['inReplyTo'] = $this->inReplyTo;
		if ($this->canonicalUrl) $postData['canonicalUrl'] = $this->canonicalUrl;
		if ($this->bundleId) $postData['bundleId'] = $this->bundleId;
		
		
		if ($this->menuItems) foreach ($this->menuItems as $menuItem) {
			$postData['menuItems'][] = $menuItem->toJSONObject();			
		}
		
		if ($this->location) {
			$postData['location'] = $this->location->toJSONObject();			
		}
		
		if ($this->recipients) foreach ($this->recipients as $recipient) {
			$postData['recipients'][] = $recipient->toJSONObject();			
		}
		
		if ($this->notification) {
			$postData['notification'] = $this->notification->toJSONObject();			
		}
		
		return $postData;
	}
	
	public function insert() {
		// format the post data as JSON:
		$jsonPost = json_encode($this->toJSONObject());
		
		$headers[] = 'Authorization: '.$this->Google_OAuth2_Token->token_type.' '.$this->Google_OAuth2_Token->access_token;
		
		if (count($this->attachments)) {
			
			$url = self::UPLOAD_URL.'?uploadType=multipart';
			
			$mime_boundary = '<<<==+X['.md5(time()).']';
			$mime_boundary = md5(time());
			$headers[] = 'Content-Type: multipart/related; boundary="'.$mime_boundary.'"';
			
			$postParms[] = "--".$mime_boundary;
			$postParms[] = 'Content-Type: application/json';
			$postParms[] = "";
			
		} else {
			$url = self::URL.'?uploadType=multipart';
		}
		
		$postParms[] = $jsonPost;
		
		if (count($this->attachments)) {
			foreach ($this->attachments as $Attachment) {
				$postParms[] = "";
				$postParms[] = "--".$mime_boundary;
				$postParms[] = 'Content-Type: '.$Attachment->contentType;
				$postParms[] = 'Content-Transfer-Encoding: binary';
				$postParms[] = "";
				$postParms[] = $Attachment->content;
				$postParms[] = "";
			}
			$postParms[] = "--".$mime_boundary.'--'; 			
		}
		
		$postData = implode("\r\n", $postParms);
		
		$headers[] = 'Content-Length: '.strlen($postData);

		$this->HttpPost = new HttpPost($url);
		$this->HttpPost->setHeaders( $headers );
		$this->HttpPost->setRawPostData( $postData );
		
		if ($this->Google_OAuth2_Token->authenticated) {
			$this->HttpPost->send();
		    $response = json_decode($this->HttpPost->httpResponse);

		} else {
			throw new Exception ("Google_OAuth2_Token needs to be authenticated before you can insert timeline items.");
		}
		

		// is there an error here?
		if ($response->error) {
			throw new Exception("The server reported an error: '".$response->error->errors[0]->message."'");
		} else {
			$this->fromJSONObject($response);
			$this->fetched = true;
		}


	}
	
	public function addAttachment($filename) {
		$Attachment = new Google_Timeline_Item_Attachment($this->Google_OAuth2_Token);
		$Attachment->parseFile($filename);
		$this->attachments[] = $Attachment;
	}
	
}

?>