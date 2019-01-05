<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model( 'Product_model' );
    $this->load->model( 'Make_model' );
    $this->load->model( 'Model_model' );
    $this->load->model( 'Year_model' );

    // Define the search values
    $this->_searchConf  = array(
      'name' => '',
      'make' => '',
      'model' => '',
      'year' => '',
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
      'make' => trim(preg_replace('/\s\s+/', ' ', $this->_searchVal['make'])),
      'model' => trim(preg_replace('/\s\s+/', ' ', $this->_searchVal['model'])),
      'year' => trim(preg_replace('/\s\s+/', ' ', $this->_searchVal['year'])),
      'sort' => $this->_searchVal['sort_field'] . ' ' . $this->_searchVal['sort_direction'],
      'page_number' => $page,
      'page_size' => $this->_searchVal['page_size'],
    );

    //var_dump($this->_searchVal['model']);exit;

    $data['query'] =  $this->Product_model->getList( $arrCondition );
    $data['total_count'] = $this->Product_model->getTotalCount();
    $data['page'] = $page;

    // Store List
    $arr = array();
    foreach( $this->_arrStoreList as $shop => $row ) $arr[ $shop ] = $shop;
    $data['arrStoreList'] = $arr;

    //Make List
    $make_arr = array();
    $make_arr[0] = '';
    $temp_arr =  $this->Make_model->getList();
    $temp_arr = $temp_arr->result();
    foreach( $temp_arr as $make ) $make_arr[ $make->id ] = $make->prefix;
    $data['make_arr'] = $make_arr;

    $arrCondition2 =  array(
      'name' => $this->_searchVal['name'],
      'make' => trim(preg_replace('/\s\s+/', ' ', $this->_searchVal['make']))
    );

    // Get data
    $temp =  $this->Product_model->getList( $arrCondition2 );
    $product_list = $temp->result();

    //model List
    $model_arr = array();
    $model_arr[0] = '';
    $temp_arr =  $this->Model_model->getList();
    $temp_arr = $temp_arr->result();

    foreach( $temp_arr as $model ) {
      foreach($product_list as $product){
        $model_s = trim(preg_replace('/\s\s+/', ' ', $model->prefix));
        if(strpos($product->tags, $model_s))
          $model_arr[ $model->id ] = $model->prefix;
      }
    }

    //year List
    $year_arr = array();
    $year_arr[0] = '';
    $temp_arr =  $this->Year_model->getList();
    $temp_arr = $temp_arr->result();

    foreach( $temp_arr as $year ) {
      foreach($product_list as $product){
        $year_s = trim(preg_replace('/\s\s+/', ' ', $year->prefix));
        if(strpos($product->tags, $year_s))
          $year_arr[ $year->id ] = $year->prefix;
      }
    }

    //Model List
    /*$model_arr = array();
    $model_arr[0] = '';
    $temp_arr =  $this->Model_model->getList();
    $temp_arr = $temp_arr->result();
    foreach( $temp_arr as $model ) $model_arr[ $model->id ] = $model->prefix;*/
    $data['model_arr'] = $model_arr;

    //Year List
    /* $year_arr = array();
    // $year_arr[0] = '';
    // $temp_arr =  $this->Year_model->getList();
    // $temp_arr = $temp_arr->result();
    // foreach( $temp_arr as $year ) $year_arr[ $year->id ] = $year->prefix;*/
    $data['year_arr'] = $year_arr;

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

  public function get_Make(){

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST");
    header('Content-Type: application/json');

    //Make List
    $make_arr = array();
    $make_arr[0] = '';
    $temp_arr =  $this->Make_model->getList();
    $temp_arr = $temp_arr->result();
    foreach( $temp_arr as $make ) $make_arr[ $make->id ] = $make->prefix;
    echo json_encode( $make_arr );
  }

  // public function get_MMY(){
  //
  //   header("Access-Control-Allow-Origin: *");
  //   header("Access-Control-Allow-Methods: GET, POST");
  //   header('Content-Type: application/json');
  //
  //   if( isset( $_POST[ "make" ] ) && !(isset( $_POST[ "model" ] )) ){
  //     $arrCondition =  array(
  //       'shop' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "shop" ])),
  //       'make' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "make" ]))
  //     );
  //
  //     // Get data
  //     $temp =  $this->Product_model->getList( $arrCondition );
  //     $product_list = $temp->result();
  //
  //     //Model List
  //     $model_arr = array();
  //     $model_arr[0] = '';
  //     $temp_arr =  $this->Model_model->getList();
  //     $temp_arr = $temp_arr->result();
  //     foreach( $temp_arr as $model ) {
  //       foreach($product_list as $product){
  //         $model_s = trim(preg_replace('/\s\s+/', ' ', $model->prefix));
  //         if(strpos($product->tags, $model_s))
  //           $model_arr[ $model->id ] = $model->prefix;
  //       }
  //     }
  //     echo json_encode($model_arr);
  //   }
  //
  //   if( isset( $_POST[ "model" ] ) && !(isset( $_POST[ "year" ] ))){
  //     $arrCondition =  array(
  //       'shop' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "shop" ])),
  //       'make' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "make" ])),
  //       'model' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "model" ])),
  //     );
  //
  //     // Get data
  //     $temp =  $this->Product_model->getList( $arrCondition );
  //     $product_list = $temp->result();
  //
  //     //year List
  //     $year_arr = array();
  //     $year_arr[0] = '';
  //     $temp_arr =  $this->Year_model->getList();
  //     $temp_arr = $temp_arr->result();
  //
  //     foreach( $temp_arr as $year ) {
  //       foreach($product_list as $product){
  //         $year_s = trim(preg_replace('/\s\s+/', ' ', $year->prefix));
  //         if(strpos($product->tags, $year_s))
  //           $year_arr[ $year->id ] = $year->prefix;
  //       }
  //     }
  //     echo json_encode($year_arr);
  //   }
  //
  //   if( isset( $_POST[ "year" ] ) ){
  //     $arrCondition =  array(
  //       'shop' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "shop" ])),
  //       'make' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "make" ])),
  //       'model' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "model" ])),
  //       'year' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "year" ])),
  //     );
  //
  //     // Get data
  //     $temp =  $this->Product_model->getList( $arrCondition );
  //     $product_list = $temp->result();
  //
  //     echo json_encode($product_list);
  //   }
  // }

  function manageMake(){
      // Check the login
      $this->is_logged_in();

      if($this->session->userdata('role') == 'admin'){
          $data['query'] =  $this->Make_model->getList();
          $data['arrStoreList'] =  $this->_arrStoreList;

          $this->load->view('view_header');
          $this->load->view('view_make', $data);
          $this->load->view('view_footer');
      }
  }

  function delMake(){
      if($this->session->userdata('role') == 'admin'){
          $id = $this->input->get_post('del_id');
          $returnDelete = $this->Make_model->delete( $id );
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
      redirect('product/manageMake');
      exit;
  }

  function createMake(){
     if($this->session->userdata('role') == 'admin'){
      $this->form_validation->set_rules('prefix', 'Prefix', 'callback_prefix_check');
      //$this->form_validation->set_rules('password', 'Password', 'required|matches[cpassword]');

      if ($this->form_validation->run() == FALSE){
          echo validation_errors('<div class="alert alert-danger">', '</div>');
          exit;
      }
      else{
            if($this->Make_model->createMake()){
                echo '<div class="alert alert-success">This make created successfully</div>';
                //redirect('product/manageMake');
                exit;
            }
            else{
                echo '<div class="alert alert-danger">Sorry ! something went wrong </div>';
                exit;
            }
          }
     }
     else{
         echo '<div class="alert alert-danger">Invalid make</div>';
         exit;
     }
  }

  function updateMake( $key ){
    if($this->session->userdata('role') == 'admin'){
      $val = $this->input->post('value');
      $pk =  $this->input->post('pk');
      $data = array(
        $key => $val
      );

      $this->Make_model->update( $pk, $data );
    }
  }

  function manageModel(){
      // Check the login
      $this->is_logged_in();

      if($this->session->userdata('role') == 'admin'){
          $data['query'] =  $this->Model_model->getList();
          $data['arrStoreList'] =  $this->_arrStoreList;

          $this->load->view('view_header');
          $this->load->view('view_model', $data);
          $this->load->view('view_footer');
      }
  }

  function delModel(){
      if($this->session->userdata('role') == 'admin'){
          $id = $this->input->get_post('del_id');
          $returnDelete = $this->Model_model->delete( $id );
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
      redirect('product/manageModel');
      exit;
  }

  function createModel(){
     if($this->session->userdata('role') == 'admin'){
      $this->form_validation->set_rules('prefix', 'Prefix', 'callback_prefix_check');
      //$this->form_validation->set_rules('password', 'Password', 'required|matches[cpassword]');

      if ($this->form_validation->run() == FALSE){
          echo validation_errors('<div class="alert alert-danger">', '</div>');
          exit;
      }
      else{
            if($this->Model_model->createModel()){
                echo '<div class="alert alert-success">This model created successfully</div>';
                //redirect('product/manageModel');
                exit;
            }
            else{
                echo '<div class="alert alert-danger">Sorry ! something went wrong </div>';
                exit;
            }
          }
     }
     else{
         echo '<div class="alert alert-danger">Invalid model</div>';
         exit;
     }
  }

  function updateModel( $key ){
    if($this->session->userdata('role') == 'admin'){
      $val = $this->input->post('value');
      $pk =  $this->input->post('pk');
      $data = array(
        $key => $val
      );

      $this->Model_model->update( $pk, $data );
    }
  }

  function manageYear(){
      // Check the login
      $this->is_logged_in();

      if($this->session->userdata('role') == 'admin'){
          $data['query'] =  $this->Year_model->getList();
          $data['arrStoreList'] =  $this->_arrStoreList;

          $this->load->view('view_header');
          $this->load->view('view_year', $data);
          $this->load->view('view_footer');
      }
  }

  function delYear(){
      if($this->session->userdata('role') == 'admin'){
          $id = $this->input->get_post('del_id');
          $returnDelete = $this->Year_model->delete( $id );
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
      redirect('product/manageYear');
      exit;
  }

  function createYear(){
     if($this->session->userdata('role') == 'admin'){
      $this->form_validation->set_rules('prefix', 'Prefix', 'callback_prefix_check');
      //$this->form_validation->set_rules('password', 'Password', 'required|matches[cpassword]');

      if ($this->form_validation->run() == FALSE){
          echo validation_errors('<div class="alert alert-danger">', '</div>');
          exit;
      }
      else{
            if($this->Year_model->createYear()){
                echo '<div class="alert alert-success">This year created successfully</div>';
                //redirect('product/manageYear');
                exit;
            }
            else{
                echo '<div class="alert alert-danger">Sorry ! something went wrong </div>';
                exit;
            }
          }
     }
     else{
         echo '<div class="alert alert-danger">Invalid year</div>';
         exit;
     }
  }

  function updateYear( $key ){
    if($this->session->userdata('role') == 'admin'){
      $val = $this->input->post('value');
      $pk =  $this->input->post('pk');
      $data = array(
        $key => $val
      );

      $this->Year_model->update( $pk, $data );
    }
  }
}
