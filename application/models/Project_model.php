<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends CI_Model
{
    private $_table = "project";

    public $project_id;
    public $name;
    public $price;
    public $image = "default.jpg";
    public $description;
    public $project_started;
    public $project_ended;
    public $client_id;
    public $proj_status_id;

    public function rules()
    {
        return [
            ['field' => 'name',
            'label' => 'Name',
            'rules' => 'required'],

            ['field' => 'price',
            'label' => 'Price',
            'rules' => 'numeric'],
            
            ['field' => 'description',
            'label' => 'Description',
            'rules' => 'required'],

            ['field' => 'project_started',
            'label' => 'Date Start',
            'rules' => 'required'],

            ['field' => 'project_ended',
            'label' => 'Date Ended',
            'rules' => 'required'],

            ['field' => 'client_id',
            'label' => 'Client',
            'rules' => 'required']
        ];
    }

    public function getAll()
    {
        // $this->db->select('project.*, proj_status.status as status_project, client.name as client_name');
        // $this->db->from('project','proj_status','client');
        // $this->db->join('proj_status','project.proj_status_id=proj_status.proj_status_id');
        // $this->db->join('client','project.client_id=client.client_id');
        // $query = $this->db->get();
        // if($id != null) {
        //     $this->db->where('project_id',$id);
        // }
        // return $query;
        // return $this->db->get($this->_table1)->result();
        return $this->db->query("SELECT project.project_id,project.name,project.price,project.image,
        project.description,project.project_started,project.project_ended,proj_status.status as status,client.name AS cn,
        client.address as address,client.email as email,client.industry as industry,client.client_id as client_id
        FROM project,proj_status,client WHERE project.proj_status_id=proj_status.proj_status_id 
        AND project.client_id=client.client_id")->result();
    }
    
    public function getById($id)
    {
        return $this->db->get_where($this->_table, ["project_id" => $id])->row();
    }

    public function save()
    {
        $post = $this->input->post();
        $this->project_id = uniqid();
        $this->name = $post["name"];
		$this->price = $post["price"];
		// $this->image = $this->_uploadImage();
        $this->description = $post["description"];
        $this->project_started = $post["project_started"];
        $this->project_ended = $post["project_ended"];
        $this->client_id = $post["client_id"];
        $this->proj_status_id = $post["proj_status_id"];
        $this->db->insert($this->_table, $this);
    }

    public function update()
    {
        $post = $this->input->post();
        $this->project_id = $post["id"];
        $this->name = $post["name"];
		$this->price = $post["price"];
		
		
		// if (!empty($_FILES["image"]["name"])) {
        //     $this->image = $this->_uploadImage();
        // } else {
        //     $this->image = $post["old_image"];
		// }

        $this->description = $post["description"];
        $this->project_started = $post["project_started"];
        $this->project_ended = $post["project_ended"];
        $this->client_id = $post["client_id"];
        $this->proj_status_id = $post["proj_status_id"];
        $this->db->update($this->_table, $this, array('project_id' => $post['id']));
    }

    public function delete($id)
    {
		$this->_deleteImage($id);
        return $this->db->delete($this->_table, array("project_id" => $id));
	}
	
	private function _uploadImage()
	{
		$config['upload_path']          = './upload/project/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['file_name']            = $this->project_id;
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
		$project = $this->getById($id);
		if ($project->image != "default.jpg") {
			$filename = explode(".", $project->image)[0];
			return array_map('unlink', glob(FCPATH."upload/project/$filename.*"));
		}
    }

    public function get($id = null)
    {
        $this->db->from('project');
        if($id != null) {
            $this->db->where('project_id', $id);
        }
        $query = $this->db->get();
        return $query;
    }



}
