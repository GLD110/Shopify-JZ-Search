<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
  // Search Condition
  protected $_searchConf;
  protected $_searchSession = '';
  protected $_searchVal;

  // Store List
  protected $_arrStoreList = array();
  protected $_default_store = '';
  
  public function __construct() {
    parent::__construct();
    
    // Get Store List
    $this->load->model( 'Store_model' );
    $query = $this->Store_model->getList();
    if( $query->num_rows() > 0 )
    foreach( $query->result() as $row )
    {
//      if( $this->session->userdata('role') == 'admin' ||  $this->session->userdata('shop') == $row->shop )
      {
        $this->_arrStoreList[ $row->shop ] = $row;
        if( $this->_default_store == '' ) $this->_default_store = $row->shop;
      }
    }
  }           
  
  /**
  * Init the search values using POST, SESSION
  * 
  */
  protected function initSearchValue()
  {
    $session_data = $this->session->userdata( $this->_searchSession );

    foreach( $this->_searchConf as $key => $defaultVal )
    {
      // Get the value from HTTP POST
      $this->_searchVal[$key] = isset( $_POST['sel_'.$key]) ?  $this->input->get_post('sel_'.$key) : $defaultVal;
      
      // Get values from Session if there is no post value
      if( is_array( $session_data ) && !isset( $_POST['sel_'.$key]) )
      {
        $this->_searchVal[$key] = $session_data[$key];
      }
    }
    
    // Fixing the team id and user id according to it's role
    if( $this->session->userdata('role') != 'admin' && $this->session->userdata('role') != 'observer' )
    {
      if( $this->session->userdata('team_boss') == '0' )
      {
        // In case of enduser, forced to himself
        $this->_searchVal['user_id'] = $this->session->userdata( 'id' );
        $this->_searchVal['team_id'] = $this->session->userdata( 'team_id');
      }
      else
      {
        // In case of teamboss, faced to his team
        $this->_searchVal['team_id'] = $this->session->userdata( 'team_id');
      }
    }
    
    // Save data to the session
    $this->session->set_userdata(
      array( $this->_searchSession => $this->_searchVal )
    );                
  }

  protected function setRenderData()
  {
    $return = array();
    
    foreach( $this->_searchConf as $key => $defaultVal )
    {
      $return['sel_' . $key] = $this->_searchVal[$key];
    }
    
    return $return;
  }
  
  protected function is_logged_in(){

    header("cache-Control: no-store, no-cache, must-revalidate");
    header("cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    $is_logged_in = $this->session->userdata('logged_in');
    
    if(!isset($is_logged_in) || $is_logged_in !== true)
    {
      // Check Cookie
      $this->load->model('User_model');
      $cookie = get_cookie( $this->config->item('loginCookie'));
      if( !empty( $cookie ) && $this->User_model->checkCookie( $cookie ))
      {
          // Login with cookie succcess
          return true;
      }
      
      redirect( 'home/sign_in' );
    }
  }
  
  public function index(){
    $this->is_logged_in();
    $data['query'] = $this->Init_model->init();
    $data['listquery'] = $this->Init_model->listItem();
    $this->load->view('admin/view_header');
    $this->load->view('admin/view_dashbord', $data);
  }
}            

