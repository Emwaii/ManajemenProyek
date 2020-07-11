<?php

class User_model extends CI_Model
{
    private $_table = "users";

    public $user_id;
    public $username;
    public $phone;
    public $photo;
    public $full_name;
    public $password;
    public $email;
    public $role;
    public $last_login;
    public $create_at;
    //public $is_active;
    // public $is_active;

    public function rules()
    {
        return [
            ['field' => 'full_name',
            'label' => 'Name',
            'rules' => 'required'],
			
            ['field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[3]'],
            
            ['field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email'],

            ['field' => 'username',
            'label' => 'username',
            'rules' => 'required'],

            ['field' => 'phone',
            'label' => 'phone',
            'rules' => 'required']
            
        ];
    }

    public function getAll()
    {
        return $this->db->get($this->_table)->result();
        // $this->db->query('SELECT *, COUNT(user_id) AS total FROM users')->result();
    }
    
    public function getById($id)
    {
        return $this->db->get_where($this->_table, ["user_id" => $id])->row();
    }

    public function save()
    {
        $post = $this->input->post();
        // $this->user_id = $post["user_id"];
        $this->username = $post["username"];
        $this->full_name = $post["full_name"];
        $this->email = $post["email"];
        // $this->password = password_hash($post["password"], PASSWORD_DEFAULT);
        $this->password = sha1($post["password"]);
        $this->role = $post["role"];
        $this->phone = $post["phone"];
        $this->photo = $this->_uploadImage();
        // $this->is_active = $post["is_active"] ?? "1";
        $this->db->insert($this->_table, $this);
    }

    public function update()
    {
        $post = $this->input->post();
        $this->user_id = $post["id"];
        $this->full_name = $post["full_name"];
        $this->username = $post["username"];
        // $this->password = password_hash($post["password"], PASSWORD_DEFAULT);
        $this->password = ($post["password"]);
        $this->email = $post["email"];
        $this->role = $post["role"];
        $this->phone = $post["phone"];
        // $this->photo = $this->_uploadImage();

        if (!empty($_FILES["image"]["name"])) {
            $this->photo = $this->_uploadImage();
        } else {
            $this->photo = $post["old_image"];
        }
        
        // $this->is_active = $post["is_active"] ?? "1";

        $this->db->update($this->_table, $this, array('user_id' => $post['id']));
    }

    public function updateprofile()
    {
        $post = $this->input->post();
        $this->user_id = $post["id"];
        $this->full_name = $post["full_name"];
        $this->username = $post["username"];
        // $this->password = password_hash($post["password"], PASSWORD_DEFAULT);
        // $this->password = ($post["password"]);
        $this->email = $post["email"];
        $this->role = $post["role"];
        $this->phone = $post["phone"];
        // $this->photo = $this->_uploadImage();

        if (!empty($_FILES["image"]["name"])) {
            $this->photo = $this->_uploadImage();
        } else {
            $this->photo = $post["old_image"];
        }
        
        // $this->is_active = $post["is_active"] ?? "1";

        $this->db->update($this->_table, $this, array('user_id' => $post['id']));
    }

    // public function doLogin(){
	// 	$post = $this->input->post();

    //     $this->db->where('email', $post["email"])
    //             ->or_where('username', $post["email"]);
    //     $user = $this->db->get($this->_table)->row();

    //     if($user){
    //         $isPasswordTrue = password_verify($post["password"], $user->password);
    //         $isAdmin = $user->role == "admin";
    //         $isEmployee = $user->role == "employee";
    //         $isFreelance = $user->role == "freelance";
    //         if(($isPasswordTrue && $isAdmin) || ($isPasswordTrue && $isEmployee) || ($isPasswordTrue && $isFreelance)){ 

    //             $this->session->set_userdata(['user_logged' => $user,$params]);
    //             $this->_updateLastLogin($user->user_id);
    //             return true;
    //         }
	// 	}
	// 	return false;
    // }

    public function isNotLogin(){
        return $this->session->userdata('user_id') === null;
    }

    public function _updateLastLogin(){
        $sql = "UPDATE {$this->_table} SET last_login=now() WHERE user_id={$this->fungsi->user_login()->user_id}";
        $this->db->query($sql);
    }

    private function _uploadImage()
	{
		$config['upload_path']          = './upload/user/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['file_name']            = $this->full_name;
		$config['overwrite']			= true;
		$config['max_size']             = 1024; // 1MB
		// $config['max_width']            = 1024;
		// $config['max_height']           = 768;

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('image')) {
			return $this->upload->data("file_name");
		}
		
		return "default.jpg";
    }

    private function _deleteImage($id)
	{
		$user = $this->getById($id);
		if ($user->photo != "default.jpg") {
			$filename = explode(".", $user->photo)[0];
			return array_map('unlink', glob(FCPATH."upload/user/$filename.*"));
		}
	}
    
    public function delete($id)
    {
		$this->_deleteImage($id);
        return $this->db->delete($this->_table, array("user_id" => $id));
    }
    
    public function get($id = null)
    {
        $this->db->from('users');
        if($id != null) {
            $this->db->where('user_id', $id);
        }
        $query = $this->db->get();
        return $query;
    }

    public function login($post) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $post['username']);
        $this->db->where('password', sha1($post['password']));
        $query = $this->db->get();
        return $query;
    }

    public function register()
    {
        $post = $this->input->post();
        // $this->user_id = $post["user_id"];
        $this->username = $post["username"];
        $this->full_name = $post["full_name"];
        $this->email = $post["email"];
        // $this->password = password_hash($post["password"], PASSWORD_DEFAULT);
        $this->password = sha1($post["password"]);
        $this->role = $post["role"];
        // $this->phone = $post["phone"];
        $this->photo = $this->_uploadImage();
        // $this->is_active = $post["is_active"] ?? "1";
        $this->db->insert($this->_table, $this);
    }


}
