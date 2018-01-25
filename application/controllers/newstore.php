<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newstore extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){

        $this->load->view('view_newstore');   
    }
    
    public function register()
    {
        if( isset( $_GET['shop'] ) )
        {
            $request_url = 'https://' . $_GET['shop'] . $this->config->item('API_AUTH_URL');
            $request_url .= '?client_id=' . $this->config->item('APP_CLIENT_ID');
            $request_url .= '&scope=' . $this->config->item('APP_SCOPE');
            $request_url .= '&redirect_uri=' . $this->config->item('APP_REDIRECT_URL');
            $request_url .= '&state=' . $this->config->item('APP_STATE');

            redirect( $request_url );
        }
        
        $this->load->view('view_newstore');   
    }
}            

