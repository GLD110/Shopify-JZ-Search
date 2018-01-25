<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store extends MY_Controller {
    
  public function __construct() {
      parent::__construct();
      $this->load->model('Store_model');
  }
  
  public function index(){
      $this->is_logged_in();

      $this->manage();
  }

  function manage(){
    
      // Check the login
      $this->is_logged_in();

      $data['arrStoreList'] = $this->_arrStoreList;
      $data['isAdmin'] = $this->session->userdata('role') == 'admin' ? true : false;
      
      $this->load->view('view_header');
      $this->load->view('view_store', $data);
      $this->load->view('view_footer');
  }
 
  function del(){
      $id = $this->input->get_post('del_id');
      
      // Delete table
      $obj = $this->Store_model->getInfo( $id );
      
      // Delete Webhook
      $this->load->model( 'Process_model' );
      $this->Process_model->uninstall( $obj->shop, $obj->app_id, $obj->app_secret );

      // Delete Record  
      $returnDelete = $this->Store_model->delete( $id );
      if( $returnDelete === true ){
          $this->session->set_flashdata('falsh', '<p class="alert alert-success">One store is deleted successfully</p>');    
      }
      else{
          $this->session->set_flashdata('falsh', '<p class="alert alert-danger">Sorry! deleted unsuccessfully : ' . $returnDelete . '</p>');    
      }
      
      redirect('store');
      exit;
  }
 
  function add(){
    $this->form_validation->set_rules('shop', 'Store Domain', 'required');
    $this->form_validation->set_rules('app_id', 'API key', 'required');
    $this->form_validation->set_rules('app_secret', 'API Password', 'required');
    
    if ($this->form_validation->run() == FALSE){       
      echo validation_errors('<div class="alert alert-danger">', '</div>');
      exit;
    }
    else{
      $shop = trim($this->input->post('shop') );
      $shop = trim( $shop, "http://" );
      $shop = trim( $shop, "https://" );
      
      // Add webhook
      $this->load->model( 'Process_model' );
      $this->Process_model->install( $shop, trim($this->input->post('app_id')), trim($this->input->post('app_secret')) );
      
      // Get Store Info
      $shopInfo = $this->Shopify_model->accessAPI( 'shop.json' );

      // Add data to token
      $data = array(
        'shop' => $shop,
        'shop_info' => json_encode( $shopInfo->shop ),
      );
      
      foreach( $this->config->item('SETTING_ITEMS') as $settings_item )
      {
        if( $settings_item['type'] == 'webhook' ) continue;
        
        $data[ $settings_item['field'] ] = trim($this->input->post($settings_item['field']));
      }
        
      //$data = $this->input->post();    
        
      if($this->Store_model->add( $data )){
        echo '<div class="alert alert-success">This Store is added successfully</div>';
        exit;
      }
      else{
        echo '<div class="alert alert-danger">Sorry ! something went wrong </div>';
        exit;
      }
    }
  }
      
  function update( $type, $pk ){
    $data = array();
    $value = trim($this->input->post('value'));
    switch( $type )
    {
      case 'shop' : 
        // Rename table
        $obj = $this->Store_model->getInfo( $pk );
      
        $data['shop'] = $value; 
        break;
      default:
        $data[ $type ] = $value;
        break;
    }

    $this->Store_model->update( $pk, $data );
  }
  
  function webhook( $id, $action){
    $obj = $this->Store_model->getInfo( $id );
    
    // Load Process model
    $this->load->model( 'Process_model' );
    
    // Access Webhook
    if( $action == 'install' ){
      $this->Process_model->install( $obj->shop, $obj->app_id, $obj->app_secret );
      echo 'success';
    }
    if( $action == 'uninstall' ){
      $this->Process_model->uninstall( $obj->shop, $obj->app_id, $obj->app_secret );
    }
    if( $action == 'get' ){
      $this->get( $obj->shop, $obj->app_id, $obj->app_secret );
    }
  }
    
  public function get( $shop, $app_id, $app_secret )
  {
    // Get access token
    $this->load->model( 'Shopify_model' );
    $this->Shopify_model->setStore( $shop, $app_id, $app_secret );
    
    // Delete webhooks
    $return = $this->Shopify_model->accessAPI( 'webhooks.json' );
    print_r( $return );    
  }          
}             