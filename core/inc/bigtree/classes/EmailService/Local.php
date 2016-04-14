<?php
	/*
		Class: BigTree\EmailService\Local
			Implements a BigTree email service via local mail service delivery.
	*/
	
	namespace BigTree\EmailService;

	use BigTree\Email;

	class Local extends Provider {

		function send(Email $email) {
			$mailer = new PHPMailer;

			foreach ($email->Headers as $key => $val) {
				$mailer->addCustomHeader($key,$val);
			}

			$mailer->Subject = $subject;
			
			if ($email->HTML) {
				$mailer->isHTML(true);
				$mailer->Body = $email->HTML;
				$mailer->AltBody = $email->Text;
			} else {
				$mailer->Body = $email->Text;
			}

			list($from_email,$from_name) = $this->parseAddress($email->From);

			$mailer->From = $from;
			$mailer->FromName = $from_name;

			list($reply_email,$reply_name) = $this->parseAddress($email->ReplyTo,false);
			
			if ($reply_email) {
				$mailer->addReplyTo($reply_email,$reply_name);
			}
			
			if ($email->CC) {
				if (is_array($email->CC)) {
					foreach ($email->CC as $item) {
						$mailer->addCC($item);
					}
				} else {
					$mailer->addCC($email->CC);
				}
			}

			if ($email->BCC) {
				if (is_array($email->BCC)) {
					foreach ($email->BCC as $item) {
						$mailer->addBCC($item);
					}
				} else {
					$mailer->addBCC($email->BCC);
				}
			}

			if (is_array($email->To)) {
				foreach ($email->To as $item) {
					$mailer->addAddress($item);
				}
			} else {
				$mailer->addAddress($email->To);
			}
			
			return $mailer->send();
		}

	}