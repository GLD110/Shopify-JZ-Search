<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Collection extends MY_Controller {
    
  private $_arrStoreList = array();
  
  public function __construct() {
    parent::__construct();
    $this->load->model( 'Collection_model' );
    
    // Define the search values
    $this->_searchConf  = array(
        'title' => '',
        'page_size' => $this->config->item('PAGE_SIZE'),
        'sort_field' => 'updated_at',
        'sort_direction' => 'DESC',
    );
    $this->_searchSession = 'collection';
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
         'title' => $this->_searchVal['title'],
         'page_number' => $page,
         'page_size' => $this->_searchVal['page_size'],              
         'sort' => $this->_searchVal['sort_field'] . ' ' . $this->_searchVal['sort_direction'],
    );
    $data['query'] =  $this->Collection_model->getList( $arrCondition );
    $data['total_count'] = $this->Collection_model->getTotalCount();
    $data['page'] = $page;
    
    // Define the rendering data
    $data = $data + $this->setRenderData();
    
    // Load Pagenation
    $this->load->library('pagination');

    $this->load->view('view_header');
    $this->load->view('view_collection', $data );
    $this->load->view('view_footer');
  }
  
  public function sync()
  {
    $this->load->model( 'Shopify_model' );
    
    // Get the lastest day
    $last_day = $this->Collection_model->getLastUpdateDate();
    
    $param = 'limit=250';
    if( $last_day != '' ) $param .= '&updated_at_min=' . urlencode( $last_day );
    $action = 'custom_collections.json?' . $param;

    // Retrive Data from Shop
    $collectionInfo = $this->Shopify_model->accessAPI( $action );

    // Store to database
    if( isset($collectionInfo->custom_collections) && is_array($collectionInfo->custom_collections) )
    {
      foreach( $collectionInfo->custom_collections as $collection )
      {
        $this->Collection_model->addCollection( $collection );
      }
    }
    
    echo 'success';
  }  
}            

