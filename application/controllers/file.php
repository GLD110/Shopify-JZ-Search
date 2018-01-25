<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model( 'Order_model' );
    }
    
    public function index(){
        $this->is_logged_in();
        
        $this->manage();
    }
    
    public function manage( ){
        // Check the login
        $this->is_logged_in();
        
        $data['query'] =  $this->Order_model->getFileList();
        $this->load->view('view_header');
        $this->load->view('view_file', $data );
        $this->load->view('view_footer');
    }
}            

