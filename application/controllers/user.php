<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
    
  public function __construct() {
    parent::__construct();
    $this->load->model('User_model');
    
  }

  public function index(){
      $this->is_logged_in();

      $this->manageUser();
  }

  function manageUser(){
      // Check the login
      $this->is_logged_in();

      if($this->session->userdata('role') == 'admin'){
          $data['query'] =  $this->User_model->getList();
          $data['arrStoreList'] =  $this->_arrStoreList;
          
          $this->load->view('view_header');
          $this->load->view('view_user', $data);
          $this->load->view('view_footer');
      }
  }

  function pageChangePassword(){
      $this->load->view('view_header');
      $this->load->view('view_changepassword');
      $this->load->view('view_footer');
  }

  function delUser(){
      if($this->session->userdata('role') == 'admin'){
          $id = $this->input->get_post('del_id');
          $returnDelete = $this->User_model->delete( $id );
          if( $returnDelete === true ){
              $this->session->set_flashdata('falsh', '<p class="alert alert-success">One item deleted successfully</p>');    
          }
          else{
              $this->session->set_flashdata('falsh', '<p class="alert alert-danger">Sorry! deleted unsuccessfully : ' . $returnDelete . '</p>');    
          }
      }
      else{
          $this->session->set_flashdata('falsh', '<p class="alert alert-danger">Sorry! You have no rights to deltete</p>');    
      }
      redirect('user/manageUser');
      exit;
  }

  function createUser(){
     if($this->session->userdata('role') == 'admin'){
      $this->form_validation->set_rules('name', 'Username', 'callback_username_check');
      $this->form_validation->set_rules('password', 'Password', 'required|matches[cpassword]');
      
      if ($this->form_validation->run() == FALSE){       
          echo validation_errors('<div class="alert alert-danger">', '</div>');
          exit;
      }
      else{
            if($this->User_model->createUser()){
                echo '<div class="alert alert-success">This user created successfully</div>';
                exit;
            }
            else{
                echo '<div class="alert alert-danger">Sorry ! something went wrong </div>';
                exit;
            }
          }
     }
     else{
         echo '<div class="alert alert-danger">Invalid user</div>';
         exit;
     }
  }

  public function username_check($str){       
      $query =  $this->db->get_where('user', array('user_name'=>$str));
             
      if (count($query->result())>0)
      {
          $this->form_validation->set_message('username_check', 'The %s already exists');
          return FALSE;
      }
      else
      {
          return TRUE;
      }
  }
      
  function changePassword(){
    $this->form_validation->set_rules('header_new_cppassword', 'Password', 'required');
    $this->form_validation->set_rules('header_new_password', 'Password', 'required|matches[header_new_cppassword]');

    if ($this->form_validation->run() == FALSE){       
        echo validation_errors('<div class="alert alert-danger">', '</div>');
        exit;
    }
            
    $val = sha1($this->input->post('header_new_password'));
    $pk =  $this->session->userdata('id');
    $data = array(
           'password' => $val
    );
    
    $this->User_model->update( $pk, $data );
    echo '<p class="alert alert-success">Change password has done successfully</p>';    
    exit;
  }
     
  function update( $key ){
    if($this->session->userdata('role') == 'admin'){
      $val = $this->input->post('value');
      if( $key == 'password' ) $val = sha1( $val );
      $pk =  $this->input->post('pk');
      $data = array(
        $key => $val
      );

      $this->User_model->update( $pk, $data );
    }
  }
}            

