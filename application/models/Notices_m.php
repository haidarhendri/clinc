<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notices_m extends CI_Model
{
	protected $_table;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Categories_m');
		$tables = $this->config->item('openblog');
		$this->_table = $tables['tables'];
	}


	public function insert_email($email)
	{
		if ( $this->db->where('email_address', $email)->count_all_results('notifications') > 0)
		{
			$this->session->set_flashdata('error', lang('notices_email_exists'));
            redirect();
		}

		if ( $inserted = $this->db->insert('notifications', ['email_address' => $email, 'verify_code' => md5(date('U') . $email . date('U'))]) )
		{
			$new_id = $this->db->insert_id();

			$new_noti = $this->db->where('id', $new_id)->limit(1)->get('notifications')->row_array();
            $this->obcore->send_email($new_noti['email_address'], $this->config->item('site_name') . ' ' . lang('notify_new_notification'), lang('notices_email_verify_msg') . site_url('notices/verify/' . $new_noti['verify_code']));

			return true;
		}
		return false;
	}


	public function verify_email($code)
	{


		if ( $email = $this->db->where('verify_code', $code)->limit(1)->get('notifications')->row() )
		{
			if ( $this->db->where('id', $email->id)->update('notifications', ['verified' => 1]) )
			{
				$this->obcore->send_email( $email->email_address, $this->config->item('site_name') . ' ' . lang('notify_success'), lang('notices_success_verifed_msg') );
				return true;
			}
		}
		return false;
	}

	public function unsub($email_address)
	{
		if ($exists = $this->db->where('email_address', $email_address)->limit(1)->get('notifications')->row())
		{
			$this->obcore->send_email( $exists->email_address, $this->config->item('site_name') . ' ' . lang('notify_unsub_sbj'), lang('notices_success_unsub_msg') );

			if ($this->db->delete('notifications', ['email_address' => $email_address]))
			{
				return true;
			}
		}
		return false;
	}

}
