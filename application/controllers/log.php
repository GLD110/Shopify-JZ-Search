<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log extends MY_Controller {
    
  public function __construct() {
      
    parent::__construct();
    $this->load->model( 'Log_model' );
    
    // Define the search values
    $this->_searchConf  = array(
      'type' => 'ALL',
      'input' => '',
      'page_size' => $this->config->item('PAGE_SIZE'),
      'sort_field' => 'log_date',
      'sort_direction' => 'DESC',
    );
    $this->_searchSession = 'log_sel';
  }
  
  public function index(){
    $this->is_logged_in();
    
    $this->manage();
  }
  
  public function manage( $page =  0 ){

    // Check the login
    $this->is_logged_in();

    // Init the search value
    $this->initSearchValue();
    
    $arrCondition =  array(
      'type' => $this->_searchVal['type'],
      'input' => $this->_searchVal['input'],
      'page_number' => $page,
      'page_size' => $this->_searchVal['page_size'],              
      'sort' => $this->_searchVal['sort_field'] . ' ' . $this->_searchVal['sort_direction'],
    );
    $data['query'] =  $this->Log_model->getList( $arrCondition );
    $data['total_count'] = $this->Log_model->getTotalCount();
    $data['page'] = $page;
    
    // Define the rendering data
    $data = $data + $this->setRenderData();
    
    // Load Pagenation
    $this->load->library('pagination');

    $this->load->view('view_header');
    $this->load->view('view_log', $data );
    $this->load->view('view_footer');
  }
  
  public function clear()
  {
    $this->Log_model->clearLog();
    echo 'success';
  }
}            

