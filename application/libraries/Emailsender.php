<?php
class Emailsender {
	private $from = 'admin@globaltechnosys.com';
	private $from_name = 'Global Admin';
	private $CI;

	public function __construct(){
		$this->CI =& get_instance();
    $this->CI->load->library('email');
	}
		
	public function send($to, $subject, $body, $type = 'html')
	{
		$config = array(
			'mailtype' => $type,
			'protocol' => 'mail',
			'mailpath' => 'usr/sbin/sendmail'
		);
		$this->CI->email->initialize($config);

		$this->CI->email->from($this->from, $this->from_name);
		$this->CI->email->to($to);

		// $this->CI->email->cc('another@another-example.com');
		// $this->CI->email->bcc('them@their-example.com');

		$this->CI->email->subject($subject);
		$this->CI->email->message($body);

		return $this->CI->email->send();
	}
}