<?php
class Shopify_model extends CI_Model
{
  private $_tablename = 'token';
  private $_access_token = '';
  private $_shop = '';
  public $error_code = '';
  
  function __construct() {
    $this->_shop = $this->session->userdata('shop');
    $this->_access_token = $this->session->userdata('access_token');
    
    parent::__construct();
  }

  public function rewriteParam( $shop, $access_token )
  {
      $this->_shop = $shop;
      $this->_access_token = $access_token;
  }

  function setStore( $domain, $app_id, $app_secret )
  {
    $this->_baseurl = 'https://' . $app_id . ':' . $app_secret . '@' . $domain . '/admin/';
  }
  
  // Access
  function accessAPI ( $action, $arrParam = array(), $method = 'GET' )
  {
    // Build the Param Querystring
    $strParam = '';
    if( $method != 'GET' )
    {
      if( $action != 'access_token' )
      $strParam = json_encode( $arrParam );
    }
    
    // Build the access url
    $url = $this->config->item('PUBLIC_MODE') ? 'https://' . $this->_shop . $this->config->item('API_BASEURL') : $this->_baseurl;
    $url .= $action;
    
    // ********** Curl Access ********** //

      // Init the session
      $curl = curl_init();

      // Set configuration value
      curl_setopt($curl, CURLOPT_URL, $url );                                         // Required : Set the access url
      if( $method == 'POST' ) curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');      // Optional : Set POST
      if( $method == 'PUT' ) curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');        // Optional : Set PUT
      if( $method == 'DELETE' ) curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');  // Optional : Set DELETE
      if( $strParam != '' ) curl_setopt($curl, CURLOPT_POSTFIELDS, $strParam);        // Required : POST Parameter String
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );                                 // Required : Enable the HTTP response as return value
      curl_setopt($curl, CURLOPT_USERAGENT, $this->config->item('APP_NAME') );        // Optional : Add the client Agent name as APP_NAME
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0 );                                 // Required : Ignore the SSL Certificate Verify
      
      $header = array(
        'Content-Type: application/json',                                                                                
        'Accept: application/json',                                                                                
        'Content-Length: ' . strlen($strParam)                                                                      
      );
      
      // Set Access Token Header for public mode        
      if( $this->config->item('PUBLIC_MODE') )
      {
        $header[] = 'X-Shopify-Access-Token:' . $this->_access_token;
      }

      curl_setopt($curl, CURLOPT_HTTPHEADER, $header);          
      
      // Access the remote URL
      $result = curl_exec($curl);
      $error_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

      // Close the session
      curl_close($curl);
    
    // ********************************* //
    
    // return the object decoded as JSON format
    return json_decode( $result );
  }

  /**
  * Get Access token from the database
  * 
  */
  function getAccessToken( $shop = '' )
  {
    $access_token = '';
    
    if( $this->config->item('PUBLIC_MODE') )
    {
      $this->db->where( 'shop', $shop );
      $res = $this->db->get( $this->_tablename );
      
      if( $res->num_rows() > 0 )
      foreach( $res->result() as $row )
      {
        $access_token = $row->token;
      }
    }
    
    return $access_token;
  }
  
  /**
  * Set the access token
  * 
  * @param mixed $shop
  * @param mixed $access_token
  */
  function setAccessToken( $shop, $access_token )
  {
    // Delete the old token
    $this->deleteAccessToken( $shop );
    
    // Insert the token information
    $data = array(
      'shop'=>$shop,
      'token'=>$access_token,
    );
    
    $this->db->insert( $this->_tablename, $data);
    if($this->db->affected_rows()>0){
      return true;
    }
    else{
      return FALSE;
    }
  }
  
  /**
  * Delete the access token
  * 
  * @param mixed $shop
  */
  function deleteAccessToken( $shop )    
  {
    // Delete the old token
    $this->db->where( 'shop', $shop );
    $this->db->delete( $this->_tablename );
  }    
}
?>
