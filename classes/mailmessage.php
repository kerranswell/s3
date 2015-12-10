<?php
class MailMessage {
	var $fromName;
	var $fromEmail;
	var $toName;
	var $toEmail;
	var $subject;
	var $body;
	var $format;
	var $addHeaders;
	var $charset;
 
	function MailMessage() {
		$this->fromName         = 'Citsb.Ru Site';
		$this->fromEmail        = 'auto@citsb.ru';
		$this->toName           = NULL;
		$this->toEmail          = NULL;
		$this->subject          = NULL;
		$this->body             = NULL;
		$this->format           = 'text';
		$this->charset          = 'utf-8';
	}
 
	function setCharset($charset) {
		$this->charset = $charset;
	}
 
	function setFrom($name, $email) {
		$this->fromName  = $name;
		$this->fromEmail = $email;
	}
 
	function setTo($name, $email) {
		$this->toName  = strlen($name) ? $name : $email;
		$this->toEmail = $email;
	}
 
	function setSubject($string) {
		$this->subject = $string;
	}

	function setFormat($string) {
		$this->format = $string;
	}

	function setBody($string) {
		$string = str_replace("\xd", '', $string);
		$this->body = $string;
	}
 
	function setTemplate($filename, $variables=NULL) {
		if(is_file($filename)) {
 
			$template = file($filename);
	 
//			if($withSubject) {
//				$subject = trim($template[0]);
//	 
//				$subject = $this->parseTemplate($subject, $variables);
//				$this->setSubject($subject);
//				array_shift($template);
//			}
	 
			$template = implode('', $template);
			$template = $this->parseTemplate($template, $variables);
	 
			$this->setBody($template);
		}
	}
 
	function parseTemplate($text, $variables) {
		if(!is_array($variables)) {
			return $text;
		}
 
		foreach($variables as $k=>$v) {
			$text = str_replace('%'.strtoupper($k).'%', (string)$v, $text);
		}
 
		$text = preg_replace("/(?<!\%)\%[^%]+?\%/si", '', $text);
		
		$text = str_replace('%%', '%', $text);
		
		return $text;
	}
 
	function createSmtpMail() {
 
		$this->addHeaders = $this->_writeSmtpHeaders();

		$contentType = $this->format == 'html' ? 'text/html' : 'text/plain';

		$this->addHeaders .= "Content-Type: ".$contentType."; charset=".$this->charset."\n";
		$this->addHeaders .= "Content-Transfer-Encoding: 8bit";
 
		return;
	}
 
	function _getSubject() {
		if (strlen($this->subject)) {
			if (preg_match("/[А-я]/", $this->subject)) {
				$_subject = "=?".$this->charset."?B?".base64_encode($this->subject)."?=";
			} else {
				$_subject = $this->subject;
			}
		} else {
			$_subject = NULL;
		}
 
		return $_subject;
	}

	function send() {
		if (!isset($this->toEmail)) {
			return false;
		}
 
		$this->createSmtpMail();
		$_subject = $this->_getSubject();

		if (mail($this->toEmail, $_subject , $this->body, $this->addHeaders)) {
			return true;
		}
		return false;
	}
 
	function _writeSmtpHeaders() {
		$addHeaders = NULL;
 
		if (isset($this->fromName)) {
			$addHeaders .= "From: =?".$this->charset."?B?".base64_encode($this->fromName)."?= <{$this->fromEmail}>\n";
		} else {
			$addHeaders .= "From: {$this->fromEmail}\n";
		}
 
		$addHeaders .= "MIME-Version: 1.0\n";
		return $addHeaders;
	}
}
?>