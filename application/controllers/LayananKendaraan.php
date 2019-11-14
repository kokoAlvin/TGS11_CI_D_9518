<?php
//require (APPPATH.'/libraries/REST_Controller.php');
use Restserver \Libraries\REST_Controller ;

Class LayananKendaraan extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('LayananKendaraanModel');
        $this->load->library('form_validation');
    }

    public function index_get(){
        return $this->returnData($this->db->get('services')->result(), false);
    }

    public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->LayananKendaraanModel->rules();
        if($id == null){
            array_push($rule, [
                'field' => 'name',
                'label' => 'name',
                'rules' => 'required'
            ],
            [
                'field' => 'price',
                'label' => 'price',
                'rules' => 'required'
            ],
            [
                'field' => 'type',
                'label' => 'type',
                'rules' => 'required'
            ]);
        }
        else{
            array_push($rule, 
            [
                'field' => 'price',
                'label' => 'price',
                'rules' => 'required'
            ]);
        }
        $validation->set_rules($rule);
        if(!$validation->run()){
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $service = new ServiceData();
        $service->name = $this->post('name');
        $service->price = $this->post('price');
        $service->type = $this->post('type');
        if($id == null){
            $response = $this->LayananKendaraanModel->store($service);
        }
        else{
            $response = $this->LayananKendaraanModel->update($service, $id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null){
        if($id == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->LayananKendaraanModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg, $error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }
}

Class ServiceData{
    public $name;
    public $price;
    public $type;
}