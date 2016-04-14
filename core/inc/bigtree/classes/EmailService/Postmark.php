<?php
	/*
		Class: BigTree\EmailService\Postmark
			Implements a BigTree email service for Mailgun (http://www.mailgun.com/)
	*/
	
	namespace BigTree\EmailService;

	use BigTree\cURL;
	use BigTree\Email;

	class Postmark extends Provider {

		function send(Email $email) {
			// Get formatted name/email
			list($from_email,$from_name) = $this->parseAddress($email->From);

			// Get formatted reply-to
			list($reply_to,$reply_email) = $this->parseAddress($email->ReplyTo,false);

			// Build POST data
			$data = array(
				"From" => $from_name ? "$from_name <$from_email>" : $from_email,
				"To" => is_array($email->To) ? implode(",",$email->To) : $email->To,
				"Subject" => $email->Subject,
				"HtmlBody" => $email->Body,
				"TextBody" => $email->Text
			);

			// Add reply to info
			if ($reply_to) {
				$data["ReplyTo"] = $reply_to;
			}

			if ($email->CC) {
				$data["Cc"] = is_array($email->CC) ? implode(",",$email->CC) : $email->CC;
			}

			if ($email->BCC) {
				$data["Bcc"] = is_array($email->BCC) ? implode(",",$email->BCC) : $email->BCC;
			}

			if ($email->Headers) {
				$data["Headers"] = array();
				foreach ($email->Headers as $key => $value) {
					$data["Headers"][] = array("Name" => $key, "Value" => $value);
				}
			}

			$response = json_decode(cURL::request("https://api.postmarkapp.com/email",json_encode($data),array(CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Accept: application/json",
				"X-Postmark-Server-Token: ".$this->Settings["postmark_key"]
			))),true);

			if ($response["ErrorCode"]) {
				$this->Error = $response["Message"];

				return false;
			}

			return true;
		}

	}