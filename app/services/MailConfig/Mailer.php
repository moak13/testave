<?php

namespace App\Services\MailConfig;

class Mailer
{
	private $mail;
	protected $sender_email = "fstackdevinc.gmail.com";
	protected $sender_password = "08521234";
	protected $sender_display_name = "AVE - VOTING";
	protected $sender_display_email = "no-reply@fstackdev.net";
	protected $sender_reply_to_name = "AVE - VOTING";
	protected $sender_reply_to_email = "cs@fstackdev.net";

	public function __construct()
	{
		$this->mail = new \PHPMailer;
		$this->mail->isSMTP();
		$this->mail->SMTPDebug = 0;
		$this->mail->Debugoutput = 'html';
		$this->mail->Host = gethostbyname('smtp.gmail.com');
		$this->mail->port = 465;
		$this->mail->SMTPSecure = 'tls';
		$this->mail->SMTPAuth = true;
		$this->setSender(
			$this->sender_email,
			$this->sender_password,
			$this->sender_display_name,
			$this->sender_display_email,
			$this->sender_reply_to_name,
			$this->sender_reply_to_email
		);
	}

	public function setSender($email, $password, $display_name, $display_email, $reply_name, $reply_email)
	{
		$this->mail->Username = $email;
		$this->mail->Password = $password;
		$this->mail->setForm($display_email, $display_name);
		$this->mail->addReplyTo($reply_email, $reply_name);
	}

	public function sendEmail($to_email, $to_name, $subject, $message, $template_name = '')
	{
		$this->mail->addAddress($to_email, $to_name);
		$this->mail->Subject = $subject;
		$this->mail->msgHTML(file_get_contents($template_name));
		$this->mail->AltBody = $message;
		if (!$this->mail->send()){
			echo "Mailer Error:" . $this->mail->ErrorInfo;
		} else {
			echo "Message sent!";
		}
	}
}

?>