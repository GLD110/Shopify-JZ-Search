<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install extends CI_Controller {
    
  private $_shop;
  private $_access_token;
      
  public function index(){
      
    if( isset( $_GET['code'] )  )
    {
      $code = $_GET['code'];
      $hmac = $_GET['hmac'];
      $timestamp = $_GET['timestamp'];
      $shop = $_GET['shop'];
      
      // ********** Access to Shopify oAuth Token ********** //
      
        // Build the Param Querystring             
        $strParam = 'client_id=' . $this->config->item( 'APP_CLIENT_ID' );
        $strParam .= '&client_secret=' . $this->config->item( 'APP_CLIENT_SECRET' );
        $strParam .= '&code=' . $code;

        $token_url = 'https://' . $shop . $this->config->item('API_TOKEN_URL');

        // Init the session
        $curl = curl_init();

        // Set configuration value
        curl_setopt($curl, CURLOPT_URL, $token_url );               // Required : Set the access url
        curl_setopt($curl, CURLOPT_POST, 1);                        // Optional : Set POST
        curl_setopt($curl, CURLOPT_POSTFIELDS, $strParam);          // Required : POST Parameter String
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );          // Required : Enable the HTTP response as return value
        curl_setopt($curl, CURLOPT_USERAGENT, $this->config->item('APP_NAME') );           // Optional : Add the client Agent name as APP_NAME
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false );         // Required : Ignore the SSL Certificate Verify

        // Access the remove URL
        $result = curl_exec($curl);
        
        // Close the session
        curl_close($curl);
      
      //  ************************************************** //

      $tokenInfo = json_decode( $result );
      
      // Save the token info to the database
      if( isset($tokenInfo->access_token) )
      {
        // Save current token Cookie
        setcookie( 'access_token', $tokenInfo->access_token, mktime (0, 0, 0, 12, 31, 2017) );
        
        // Save the access token and shop domain to the session
        $this->_shop = $shop;
        $this->_access_token = $tokenInfo->access_token;

        $this->session->set_userdata( array( 
            'shop' => $shop, 
            'access_token' => $tokenInfo->access_token 
        ));
        
        // Save the token to database
        $this->load->model( 'Shopify_model' );
        $this->Shopify_model->rewriteParam( $this->_shop, $this->_access_token );
        $this->Shopify_model->setAccessToken( $this->_shop, $this->_access_token );
        
        // Init the configuration
        $this->load->model( 'Process_model' );
        $this->Process_model->install( $this->_shop, '', '', $this->_access_token );
        
        // Redirect to main page
        redirect( 'home' );        
      }
      else
      {
          var_dump( $result );
      }
    }
  }
  
  public function uninstall()
  {
    // Set the shop
    $inputText = file_get_contents('php://input');
    if( $inputText == '' ){
      $shop = $this->config->item('PRIVATE_SHOP');
    }
    else
    {
      $inputInfo = json_decode( $inputText );
      $shop = $inputInfo->myshopify_domain;
    }
    
    $fp = fopen( 'log.txt', 'w+');
    fwrite( $fp, $inputText );
    fwrite( $fp, "\r\n----------------\r\n" );
    
    fwrite( $fp, $shop );
    
    // Uninstall the database
    
    // Get access token
    $this->load->model( 'Shopify_model' );
    $access_token = $this->Shopify_model->getAccessToken( $shop );
    $this->Shopify_model->rewriteParam( $shop, $access_token );
    
    // Uninstall the configuration
    $this->load->model( 'Process_model' );
    $this->Process_model->uninstall( $this->_shop, '', '', $this->_access_token );
    
    // Delete token
    $this->Shopify_model->deleteAccessToken( $shop );
    
    fclose( $fp );
  }
}            

