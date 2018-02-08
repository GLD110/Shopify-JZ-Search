<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vehicle extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Vehicle_model');
    $this->load->model( 'Make_model' );
    $this->load->model( 'Model_model' );
    $this->load->model( 'Year_model' );
    $this->load->model( 'Product_model' );

  }

  public function index(){
      $this->is_logged_in();

      //Import Vehicle date from CSV
      //$this->Vehicle_model->importCSV($this->csv_to_array($this->config->item('app_path') . 'uploads/csv/vehicle.csv'));exit;

      $this->manageVehicle();
  }

  function csv_to_array($filename='', $delimiter=',')
  {
  	if(!file_exists($filename) || !is_readable($filename))
  		return FALSE;

  	$header = NULL;
  	$data = array();
  	if (($handle = fopen($filename, 'r')) !== FALSE)
  	{
  		while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
  		{
  			if(!$header)
  				$header = $row;
  			else
  				$data[] = array_combine($header, $row);
  		}
  		fclose($handle);
  	}
  	return $data;
  }

  function manageVehicle(){
      // Check the login
      $this->is_logged_in();

      if($this->session->userdata('role') == 'admin'){
          $data['query'] =  $this->Vehicle_model->getList();
          $data['arrStoreList'] =  $this->_arrStoreList;

          //Make List
          $make_arr = array();
          $make_arr[0] = '';
          $temp_arr =  $this->Make_model->getList();
          $temp_arr = $temp_arr->result();

          foreach( $temp_arr as $make ) {
            $make_s = trim(preg_replace('/\s\s+/', ' ', $make->prefix));
            $make_arr[ $make->id ] = $make_s;
          }
          $data['make'] = $make_arr;

          //model List
          $model_arr = array();
          $model_arr[0] = '';
          $temp_arr =  $this->Model_model->getList();
          $temp_arr = $temp_arr->result();

          foreach( $temp_arr as $model ) {
            $model_s = trim(preg_replace('/\s\s+/', ' ', $model->prefix));
            $model_arr[ $model->id ] = $model_s;
          }
          $data['model'] = $model_arr;

          $this->load->view('view_header');
          $this->load->view('view_vehicle', $data);
          $this->load->view('view_footer');
      }
  }

  function delVehicle(){
    if($this->session->userdata('role') == 'admin'){
        $id = $this->input->get_post('del_id');
        $returnDelete = $this->Vehicle_model->delete( $id );
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
    redirect('vehicle/manageVehicle');
    exit;
  }

  function createVehicle(){
    if($this->session->userdata('role') == 'admin'){
     $this->form_validation->set_rules('prefix', 'Prefix', 'callback_prefix_check');
     //$this->form_validation->set_rules('password', 'Password', 'required|matches[cpassword]');

     if ($this->form_validation->run() == FALSE){
         echo validation_errors('<div class="alert alert-danger">', '</div>');
         exit;
     }
     else{
           if($this->Vehicle_model->createVehicle()){
               echo '<div class="alert alert-success">This Vehicle created successfully</div>';
               //redirect('vehicle/manageVehicle');
               exit;
           }
           else{
               echo '<div class="alert alert-danger">Sorry ! something went wrong </div>';
               exit;
           }
         }
    }
    else{
        echo '<div class="alert alert-danger">Invalid Vehicle</div>';
        exit;
    }
  }

  function updateVehicle( $key ){
    if($this->session->userdata('role') == 'admin'){
      $val = $this->input->post('value');
      $pk =  $this->input->post('pk');
      $data = array(
        $key => $val
      );

      $this->Vehicle_model->update( $pk, $data );
    }
  }

  public function getVehicles(){

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST");
    header('Content-Type: application/json');

    if( isset( $_POST ) ){
      $arrCondition =  array(
        'shop' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "shop" ])),
        'make' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "make" ])),
        'model' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "model" ])),
        'year' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "year" ])),
      );

      // Get data
      $temp =  $this->Vehicle_model->getVehicles( $arrCondition );
      $vehicle_list = $temp->result();

      echo json_encode($vehicle_list);
    }
  }

  public function get_MMY(){

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST");
    header('Content-Type: application/json');

    if( isset( $_POST[ "make" ] ) && !(isset( $_POST[ "model" ] )) ){

      $arrCondition =  array(
        'shop' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "shop" ])),
        'make' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "make" ]))
      );

      // Get data
      $temp =  $this->Vehicle_model->getVehicles( $arrCondition );
      $vehicle_temp = array('0'=>array('model' => ''));
      $vehicle_list = $temp->result();
      $vehicle_list = array_merge($vehicle_temp, $vehicle_list);    

      echo json_encode($vehicle_list);
    }

    if( isset( $_POST[ "model" ] ) && !(isset( $_POST[ "year" ] ))){

      $arrCondition =  array(
        'shop' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "shop" ])),
        'make' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "make" ])),
        'model' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "model" ])),
      );

      // Get data
      $temp =  $this->Vehicle_model->getVehicles( $arrCondition );
      $vehicle_list = $temp->result();

      //year List
      $year_arr = array();
      $year_arr[0] = '';
      $temp_arr =  $this->Year_model->getList();
      $temp_arr = $temp_arr->result();

      foreach( $temp_arr as $year ) {
        foreach($vehicle_list as $vehicle){
          $year_s = trim(preg_replace('/\s\s+/', ' ', $year->prefix));
          if(( $vehicle->start_year <= $year_s ) && ( $vehicle->end_year >= $year_s ))
            array_push($year_arr, $year_s);
        }
      }
      echo json_encode($year_arr);
    }

    if( isset( $_POST[ "year" ] ) ){

      $arrCondition =  array(
        'shop' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "shop" ])),
        'make' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "make" ])),
        'model' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "model" ])),
        'year' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "year" ])),
        'category' => trim(preg_replace('/\s\s+/', ' ', $_POST[ "category" ])),
      );

      // Get data
      $temp =  $this->Product_model->getVehicle_Products( $arrCondition );
      $product_list = $temp->result();

      echo json_encode($product_list);
    }
  }
}
