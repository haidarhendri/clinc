<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_users extends OB_AdminController {

	public function __construct()
	{
		parent::__construct();

		if ( ! $this->ion_auth->has_permission('users'))
		{
			$this->session->set_flashdata('error', lang('permission_check_failed'));
			redirect();
		}

		$this->template->append_css('default.css');
		$this->template->append_css('ie10-viewport-bug-workaround.css');


		$this->template->append_js('ie10-viewport-bug-workaround.js');

		$this->load->model('Admin_m');

		$this->template->set('active_link', 'users');

		$this->load->helper('form');

		$this->load->library('form_validation');

		$this->load->language('auth', $this->session->language);
		$this->load->language('ion_auth', $this->session->language);

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');
	}


	public function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata('error', "'You must be logged in to view this page.'");
			redirect('auth/login');
		}
		else
		{
			$data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error ');

			$data['groups']	= $this->ion_auth->groups()->result();

			foreach ($data['groups'] as & $group)
			{
				$group->permissions = $this->Admin_m->get_groups_permissions($group->id);
			}

			$data['permissions'] = $this->ion_auth->permissions()->result();

			$data['users'] = $this->ion_auth->users()->result();
			foreach ($data['users'] as $k => $user)
			{
				$data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}
			$this->template->build('admin/users/index', $data);
		}
	}


	public function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			$this->session->set_flashdata('success', $this->ion_auth->messages());
			redirect("admin_users", 'refresh');
		}
		else
		{
			$this->session->set_flashdata('error', $this->ion_auth->errors());
			redirect("admin_users", 'refresh');
		}
	}

	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			$data['csrf'] = $this->_get_csrf_nonce();
			$data['user'] = $this->ion_auth->user($id)->row_array();

			$this->template->build('admin/users/deactivate_user', $data);
		}
		else
		{
			if ($this->input->post('confirm') == 'yes')
			{
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}

				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			redirect('admin_users', 'refresh');
		}
	}

	public function create_user()
    {
        $this->data['title'] = $this->lang->line('create_user_heading');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('/', 'refresh');
        }

        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        if($identity_column!=='email')
        {
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true)
        {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($identity, $password, $email, $additional_data))
        {
            $this->session->set_flashdata('success', $this->ion_auth->messages());
            redirect("admin_users", 'refresh');
        }
        else
        {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name'),
                'class'	=> 'form-control',
                'placeholder'	=> 'First Name'
            );
            $this->data['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('last_name'),
                'class'	=> 'form-control',
                'placeholder'	=> 'Last Name'
            );
            $this->data['identity'] = array(
                'name'  => 'identity',
                'id'    => 'identity',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('identity'),
                'class'	=> 'form-control',
                'placeholder'	=> 'Email'
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('email'),
                'class'	=> 'form-control',
                'placeholder'	=> 'Email'
            );
            $this->data['company'] = array(
                'name'  => 'company',
                'id'    => 'company',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('company'),
                'class'	=> 'form-control',
                'placeholder'	=> 'Company'
            );
            $this->data['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('phone'),
                'class'	=> 'form-control',
                'placeholder'	=> 'Phone'
            );
            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => '',
                'class'	=> 'form-control',
                'placeholder'	=> 'Password'
            );
            $this->data['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
                'value' => '',
                'class'	=> 'form-control',
                'placeholder'	=> 'Confirm Password'
            );

            $this->template->build('admin/users/create_user', $this->data);
        }
    }

	public function edit_user($id)
	{
		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('/', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('email', $this->lang->line('login_identity_label'), 'required|valid_email');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'email' 	 => $this->input->post('email'),
					'last_name'  => $this->input->post('last_name'),
					'company'    => $this->input->post('company'),
					'phone'      => $this->input->post('phone'),
				);

				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}



				if ($this->ion_auth->is_admin())
				{
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData)) {

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $id);
						}

					}
				}

			   if($this->ion_auth->update($user->id, $data))
			    {
				    $this->session->set_flashdata('success', $this->ion_auth->messages() );
				    if ($this->ion_auth->is_admin())
					{
						redirect('admin_users', 'refresh');
					}
					else
					{
						redirect('/', 'refresh');
					}

			    }
			    else
			    {
				    $this->session->set_flashdata('error', $this->ion_auth->errors() );
				    if ($this->ion_auth->is_admin())
					{
						redirect('admin_users', 'refresh');
					}
					else
					{
						redirect('/', 'refresh');
					}

			    }

			}
		}

		$this->data['csrf'] = $this->_get_csrf_nonce();

		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$this->data['user'] = (array) $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;
		$this->data['email'] = array(
			'name'  => 'email',
			'id'    => 'email',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('email', $user->email),
			'class' => 'form-control',
			'placeholder'	=> 'Email Address'
		);
		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
			'class' => 'form-control',
			'placeholder'	=> 'First Name'
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
			'class' => 'form-control',
			'placeholder'	=> 'Last Name'
		);
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
			'class' => 'form-control',
			'placeholder'	=> 'Company'
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
			'class' => 'form-control',
			'placeholder'	=> 'Phone'
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password',
			'class' => 'form-control',
			'placeholder'	=> 'Password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password',
			'class' => 'form-control',
			'placeholder'	=> 'Confirm Password'
		);
		$this->template->build('admin/users/edit_user', $this->data);
	}

	public function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('admin_users', 'refresh');
		}

		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id)
			{
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("admin_users", 'refresh');
			}
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
				'class' => "form-control",
				'placeholder'	=> 'Group Name'
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
				'class' => "form-control",
				'placeholder'	=> 'Group Description'
			);


			$this->template->build('admin/users/create_group', $this->data);
		}
	}

	public function edit_group($id)
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('/', 'refresh');
		}

		if(!$id || empty($id))
		{
			redirect('admin_users', 'refresh');
		}

		$data['title'] = $this->lang->line('edit_group_title');

		$group = $this->ion_auth->group($id)->row();

		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					unset($_POST['group_name']);
					unset($_POST['group_description']);

					$this->Admin_m->update_group_perms($id, $_POST);

					$this->session->set_flashdata('success', $this->lang->line('edit_group_saved'));
				}
				else
				{
					$this->session->set_flashdata('error', $this->ion_auth->errors());
				}
				redirect("admin_users", 'refresh');
			}
		}

		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

		$data['group_name'] = array(
			'name'    	=> 'group_name',
			'id'      	=> 'group_name',
			'type'    	=> 'text',
			'value'   	=> $this->form_validation->set_value('group_name', $group->name),
			$readonly 	=> $readonly,
			'class' 	=> "form-control",
			'placeholder'	=> 'Group Description',

		);
		$data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
			'class' => "form-control",
			'placeholder'	=> 'Group Description'
		);

		$data['group_id'] = $group->id;

		$data['group_perms'] = $this->Admin_m->get_group_perms($group->id);

		$this->template->build('admin/users/edit_group', $data);
	}

	public function edit_perm($id)
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('/', 'refresh');
		}

		if(!$id || empty($id))
		{
			redirect('admin_users', 'refresh');
		}

		$data['title'] = $this->lang->line('edit_perm_heading');

		$perm = $this->ion_auth->permission($id)->row();

		// validate form input
		$this->form_validation->set_rules('perm_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{

				$perm_update = $this->ion_auth->update_perm($id, $_POST['perm_name'], $_POST['perm_description']);

				if($perm_update)
				{
					$this->session->set_flashdata('success', $this->lang->line('edit_perm_saved'));
				}
				else
				{
					$this->session->set_flashdata('error', $this->ion_auth->errors());
				}
				redirect("admin_users", 'refresh');
			}
		}

		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$data['perm_name'] = array(
			'name'    	=> 'perm_name',
			'id'      	=> 'perm_name',
			'type'    	=> 'text',
			'value'   	=> $this->form_validation->set_value('perm_name', $perm->name),
			'class' 	=> "form-control",
			'placeholder'	=> 'Permission Description',

		);
		$data['perm_description'] = array(
			'name'  => 'perm_description',
			'id'    => 'perm_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('perm_description', $perm->description),
			'class' => "form-control",
			'placeholder'	=> 'Permission Description'
		);

		$data['perm_id'] = $perm->id;

		$this->template->build('admin/users/edit_perm', $data);
	}



	public function remove_group($id)
	{
		$group = $this->db->get_where('groups', ['id' => $id])->row();

		if ($group->name == 'admin' || $group->name == 'members' || $group->name == 'contributors' || $group->name == 'editors')
		{
			$this->session->set_flashdata('error', $this->lang->line('group_protected'));
			redirect('admin_users', 'refresh');
		}
		if ($this->db->where('id', $id)->delete('groups') && $this->db->where('group_id', $id)->delete('users_groups') )
		{
			$this->session->set_flashdata('success', $this->lang->line('group_removed'));
			redirect('admin_users', 'refresh');
		}
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('group_not_removed'));
			redirect('admin_users', 'refresh');
		}
	}


	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
