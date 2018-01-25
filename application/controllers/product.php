<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MY_Controller {
    
  public function __construct() {
    parent::__construct();
    $this->load->model( 'Product_model' );
    $this->load->model( 'Sku_model' );
    
    // Define the search values
    $this->_searchConf  = array(
      'name' => '',
      'sku' => '',
      'shop' => $this->_default_store,
      'page_size' => $this->config->item('PAGE_SIZE'),
      'sort_field' => 'product_id',
      'sort_direction' => 'DESC',
    );
    $this->_searchSession = 'product_app_page';
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

    // Get data
    $this->Product_model->rewriteParam($this->_searchVal['shop']);
    $arrCondition =  array(
      'name' => $this->_searchVal['name'],
      'sku' => $this->_searchVal['sku'],
      'sort' => $this->_searchVal['sort_field'] . ' ' . $this->_searchVal['sort_direction'],
      'page_number' => $page,
      'page_size' => $this->_searchVal['page_size'],              
    );
    $data['query'] =  $this->Product_model->getList( $arrCondition );
    $data['total_count'] = $this->Product_model->getTotalCount();
    $data['page'] = $page;
    
    // Store List    
    $arr = array();
    foreach( $this->_arrStoreList as $shop => $row ) $arr[ $shop ] = $shop;
    $data['arrStoreList'] = $arr;
    
    // Define the rendering data
    $data = $data + $this->setRenderData();
    
    // Load Pagenation
    $this->load->library('pagination');

    $this->load->view('view_header');
    $this->load->view('view_product', $data );
    $this->load->view('view_footer');
  }
  
  public function update( $type, $pk )
  {
    $data = array();
    
    switch( $type )
    {
        case 'type' : $data['type'] = $this->input->post('value'); break;
        case 'title' : $data['title'] = $this->input->post('value'); break;
        case 'sku' : $data['sku'] = $this->input->post('value'); break;
        case 'item_per_square' : $data['item_per_square'] = str_replace( ',', '.', $this->input->post('value') ); break;
    }
    $this->Product_model->update( $pk, $data );
  }
  
  public function sync( $shop, $page = 1 )
  {
    $this->load->model( 'Process_model' );
    
    // Set the store information
    $this->Product_model->rewriteParam( $shop );
    
    $this->load->model( 'Shopify_model' );
    $this->Shopify_model->setStore( $shop, $this->_arrStoreList[$shop]->app_id, $this->_arrStoreList[$shop]->app_secret );
    
    // Get the lastest day
    $last_day = $this->Product_model->getLastUpdateDate();
    
    // Retrive Data from Shop
    $count = 0;

    // Make the action with update date or page
    $action = 'products.json?';
    if( $last_day != '' && $last_day != $this->config->item('CONST_EMPTY_DATE') && $page == 1 )
    {
      $action .= 'limit=250&updated_at_min=' . urlencode( $last_day );
    }
    else
    {
      $action .= 'limit=20&page=' . $page;
    } 

    // Retrive Data from Shop
    $productInfo = $this->Shopify_model->accessAPI( $action );

    // Store to database
    if( isset($productInfo->products) && is_array($productInfo->products) )
    {
      foreach( $productInfo->products as $product )
      {
        $this->Process_model->product_create( $product, $this->_arrStoreList[$shop] );        
      }
    }
    
    // Get the count of product
    if( $last_day != '' && $last_day != $this->config->item('CONST_EMPTY_DATE') && $page == 1 )
    {
      $count = 0;
    }
    else
    {
      if( isset( $productInfo->products )) $count = count( $productInfo->products );
      $page ++;  
    }

    if( $count == 0 )
      echo 'success';
    else
      echo $page . '_' . $count;
  }
    
  function manageSku(){
      // Check the login
      $this->is_logged_in();

      if($this->session->userdata('role') == 'admin'){
          $data['query'] =  $this->Sku_model->getList();
          $data['arrStoreList'] =  $this->_arrStoreList;
          
          $this->load->view('view_header');
          $this->load->view('view_sku', $data);
          $this->load->view('view_footer');
      }
  } 
    
  function delSku(){
      if($this->session->userdata('role') == 'admin'){
          $id = $this->input->get_post('del_id');
          $returnDelete = $this->Sku_model->delete( $id );
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
      redirect('product/manageSku');
      exit;
  }

  function createSku(){
     if($this->session->userdata('role') == 'admin'){
      $this->form_validation->set_rules('prefix', 'Prefix', 'callback_prefix_check');
      //$this->form_validation->set_rules('password', 'Password', 'required|matches[cpassword]');
      
      if ($this->form_validation->run() == FALSE){       
          echo validation_errors('<div class="alert alert-danger">', '</div>');
          exit;
      }
      else{
            if($this->Sku_model->createSku()){
                echo '<div class="alert alert-success">This sku created successfully</div>';
                //redirect('product/manageSku');
                exit;
            }
            else{
                echo '<div class="alert alert-danger">Sorry ! something went wrong </div>';
                exit;
            }
          }
     }
     else{
         echo '<div class="alert alert-danger">Invalid sku</div>';
         exit;
     }
  }    
    
  function updateSku( $key ){
    if($this->session->userdata('role') == 'admin'){
      $val = $this->input->post('value');
      if( $key == 'prefix' )
      $prefix =  $this->input->post('prefix');
      $data = array(
        $key => $val
      );

      $this->Sku_model->update( $prefix, $data );
    }
  }    
}            

