<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Endpoint extends MY_Controller {

  private $_shop = '';
  private $_inputInfo = array();
  private $_message = '';
  private $_shopifydelay = 0.9;
  
  private $_log_file = false;
  private $_log_message = true;
      
  public function __construct() {
    parent::__construct();
    
    ini_set( 'max_execution_time', '40000' );
    
    // Shopify Delay
    $this->_shopifydelay = $this->_shopifydelay * 1000000;
    
    // Load Model    
    $this->load->model( 'Log_model' );
    
    // Define a header
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST");    
    header('Content-Type: application/json');
    
    // Get the shop from the HTTP Header or private shop  
    $this->_shop = isset( $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] ) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : $this->config->item('PRIVATE_SHOP');

    // Get the Input Stream
    $this->_inputInfo = json_decode( file_get_contents('php://input') );

    /*if( !isset($this->_inputInfo->id ) )
    {
      $strTemp = '{"id":3745073799,"email":"denis.buhler@outlook.com","closed_at":null,"created_at":"2016-07-16T10:54:21+10:00","updated_at":"2016-07-16T10:56:22+10:00","number":27,"note":"","token":"fdb162880342c6176dfbf4b4dec7ee99","gateway":"Bank Deposit","test":false,"total_price":"73.21","subtotal_price":"63.21","total_weight":503,"total_tax":"0.91","taxes_included":true,"currency":"AUD","financial_status":"paid","confirmed":true,"total_discounts":"0.00","total_line_items_price":"63.21","cart_token":"431917b7277ea05887f42caa3781b56b","buyer_accepts_marketing":false,"name":"#1027","referring_site":"","landing_site":"\/","cancelled_at":null,"cancel_reason":null,"total_price_usd":"55.73","checkout_token":"bc86eca72eafa28a006129b60aa55d41","reference":null,"user_id":null,"location_id":null,"source_identifier":null,"source_url":null,"processed_at":"2016-07-16T10:54:21+10:00","device_id":null,"browser_ip":null,"landing_site_ref":null,"order_number":1027,"discount_codes":[],"note_attributes":[{"name":"What color are you prefer to","value":"Black"}],"payment_gateway_names":["Bank Deposit"],"processing_method":"manual","checkout_id":9429137607,"source_name":"web","fulfillment_status":null,"tax_lines":[{"title":"MwSt","price":"0.91","rate":0.1}],"tags":"","contact_email":"denis.buhler@outlook.com","order_status_url":"https:\/\/checkout.shopify.com\/11549496\/checkouts\/bc86eca72eafa28a006129b60aa55d41\/thank_you_token?key=9642fb35e8476430081810785e0a254e","line_items":[{"id":7092196167,"variant_id":21168134279,"title":"Baseus QI Wireless Charger Receiver for iPhone","quantity":3,"price":"5.07","grams":41,"sku":"168840702","variant_title":"FOR LIGHTNING INPUT DEVICE \/ LIGHT GRAY","vendor":"Ecango-CB","fulfillment_service":"manual","product_id":6656012551,"requires_shipping":true,"taxable":false,"gift_card":false,"name":"Baseus QI Wireless Charger Receiver for iPhone - FOR LIGHTNING INPUT DEVICE \/ LIGHT GRAY","variant_inventory_management":"shopify","properties":[],"product_exists":true,"fulfillable_quantity":3,"total_discount":"0.00","fulfillment_status":null,"tax_lines":[{"title":"MwSt","price":"0.00","rate":0.1}],"origin_location":{"id":876106887,"country_code":"AU","province_code":"QLD","name":"Chief Products","address1":"P.O. Box 278","address2":"","city":"Mudgeeraba","zip":"4213"},"destination_location":{"id":1705958343,"country_code":"DE","province_code":"","name":"denis buhler","address1":"werneuchener","address2":"","city":"Berlin","zip":"13055"}},{"id":7092196231,"variant_id":21168477383,"title":"A046 360 Degrees Car Windscreen Holder Dashboard Mount Stand for Cell Phone GPS2","quantity":4,"price":"12.00","grams":95,"sku":"159380902","variant_title":"HEAVY GRAY \/ Large \/ IRON","vendor":"Chief Products","fulfillment_service":"manual","product_id":6656143175,"requires_shipping":true,"taxable":false,"gift_card":false,"name":"A046 360 Degrees Car Windscreen Holder Dashboard Mount Stand for Cell Phone GPS2 - HEAVY GRAY \/ Large \/ IRON","variant_inventory_management":"shopify","properties":[],"product_exists":true,"fulfillable_quantity":4,"total_discount":"0.00","fulfillment_status":null,"tax_lines":[{"title":"MwSt","price":"0.00","rate":0.1}],"origin_location":{"id":876106887,"country_code":"AU","province_code":"QLD","name":"Chief Products","address1":"P.O. Box 278","address2":"","city":"Mudgeeraba","zip":"4213"},"destination_location":{"id":1705958343,"country_code":"DE","province_code":"","name":"denis buhler","address1":"werneuchener","address2":"","city":"Berlin","zip":"13055"}}],"shipping_lines":[{"id":3157854663,"title":"Standard Shipping","price":"10.00","code":"Standard Shipping","source":"shopify","phone":null,"delivery_category":null,"carrier_identifier":null,"tax_lines":[{"title":"MwSt","price":"0.91","rate":0.1}]}],"billing_address":{"first_name":"denis","address1":"werneuchener","phone":"15910963664","city":"Berlin","zip":"13055","province":null,"country":"Germany","last_name":"buhler","address2":"","company":null,"latitude":52.5405416,"longitude":13.4949562,"name":"denis buhler","country_code":"DE","province_code":null},"shipping_address":{"first_name":"denis","address1":"werneuchener","phone":"15910963664","city":"Berlin","zip":"13055","province":null,"country":"Germany","last_name":"buhler","address2":"","company":null,"latitude":52.5405416,"longitude":13.4949562,"name":"denis buhler","country_code":"DE","province_code":null},"fulfillments":[],"client_details":{"browser_ip":"104.237.91.157","accept_language":"en-US,en;q=0.8","user_agent":"Mozilla\/5.0 (Windows NT 10.0; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/51.0.2704.103 Safari\/537.36","session_hash":"e7052c1bb5643da2eb2d4480febd3540","browser_width":1920,"browser_height":955},"refunds":[],"customer":{"id":3929218311,"email":"denis.buhler@outlook.com","accepts_marketing":false,"created_at":"2016-07-08T00:37:00+10:00","updated_at":"2016-07-16T10:54:22+10:00","first_name":"denis","last_name":"buhler","orders_count":2,"state":"disabled","total_spent":"0.00","last_order_id":3745073799,"note":null,"verified_email":true,"multipass_identifier":null,"tax_exempt":false,"tags":"","last_order_name":"#1027","default_address":{"id":4293092359,"first_name":"denis","last_name":"buhler","company":null,"address1":"werneuchener","address2":"","city":"Berlin","province":null,"country":"Germany","zip":"13055","phone":"15910963664","name":"denis buhler","province_code":null,"country_code":"DE","country_name":"Germany","default":true}}}';
//      $strTemp = '{"rate":{"origin":{"country":"AU","postal_code":"4213","province":"QLD","city":"Mudgeeraba","name":null,"address1":"P.O. Box 278","address2":"","address3":null,"phone":"61755227340","fax":null,"address_type":null,"company_name":"KevShop"},"destination":{"country":"US","postal_code":"7000","province":"TAS","city":"Hobart","name":"Maree Anne  Davis","address1":"98 Argyle Street","address2":"","address3":null,"phone":"","fax":null,"address_type":null,"company_name":""},"items":[{"name":"Default","sku":"YO0625501","quantity":2,"grams":35,"price":1000,"vendor":"KevShop","requires_shipping":true,"taxable":true,"fulfillment_service":"manual","properties":null,"product_id":5309944903,"variant_id":16435836679},{"name":"Large","sku":"YO0625502","quantity":1,"grams":50,"price":2000,"vendor":"KevShop","requires_shipping":true,"taxable":true,"fulfillment_service":"manual","properties":null,"product_id":5309940615,"variant_id":16435812679}],"currency":"AUD"}}';
      
      $this->_inputInfo = json_decode( $strTemp );
    }*/

    // Log the request 
    if( $this->_log_file )   
    {
      $this->Log_model->add( 'Webhook', $this->_shop, $_SERVER['REQUEST_URI'] . json_encode( $this->_inputInfo ), '' );
    }
    
  }
  
  public function __destruct()
  {
  }
  
  // Load shopify model  
  private function _loadShopify()
  {
    // Define the model
    $this->load->model( 'Shopify_model' );
    $this->Shopify_model->setStore( $this->_shop, $this->_arrStoreList[$this->_shop]->app_id, $this->_arrStoreList[$this->_shop]->app_secret );
  }      
  
  // Get the Shop information
  private function _getShopInfo()
  {
    // Load the shopify model
    $this->_loadShopify();

    return $this->Shopify_model->accessAPI( 'shop.json' );
  }

  
  public function index(){
  }
  
  /** 
  * Checkout popup
  * 
  */
  public function order_create( $method = 'Order Created' )
  {
    header( "HTTP/1.1 200 OK" );	
    // Load Model
    $this->load->model( 'Process_model' );
    
    // Log the system
    $this->Log_model->add( 'Webhook', $method, trim( $this->_inputInfo->name, '#'), $this->_shop );        

    // Access the Process
    $this->Process_model->order_create( $this->_inputInfo, $this->_arrStoreList[ $this->_shop ], $method );     
  }
  
  public function order_paid()
  {
    header( "HTTP/1.1 200 OK" );  
    $this->order_create( 'Order Paid' );    
    
  }
  
  public function order_update()
  {
    header( "HTTP/1.1 200 OK" );  
    usleep( 10000000 );
    
    // Skip blank update within 10 seconds
    if( $this->_inputInfo->created_at == $this->_inputInfo->updated_at)  return;
    
    $created_at = strtotime( $this->_inputInfo->created_at ) + 0;
    $updated_at = strtotime( $this->_inputInfo->updated_at ) + 0;
    if( $updated_at - $created_at < 10 )  return;
    
    $this->order_create( 'Order Updated' );    
  }
    
  public function order_cancel()
  {
    header( "HTTP/1.1 200 OK" );
    $this->Log_model->add( 'Webhook', 'Order Cancelled', $this->_inputInfo->name, $this->_shop );
    
    // Update the order status
    $this->load->model( 'Order_model' );
    $this->Order_model->rewriteParam( $this->_shop );
    $this->Order_model->updateStatus( $this->_inputInfo->id, array( 'status' => 'cancelled' ) );   
  }

  public function product_create()
  {
    header( "HTTP/1.1 200 OK" );  
    // Log
    $this->Log_model->add( 'Webhook', 'Product Create', $this->_inputInfo->id, '' );
        
    $this->load->model( 'Process_model' );
    $this->Process_model->product_create( $this->_inputInfo, $this->_arrStoreList[ $this->_shop ], 'Product Create' );   
  }
  
  public function product_update()
  {
    header( "HTTP/1.1 200 OK" );  
    $this->load->model( 'Process_model' );
    $this->Process_model->product_create( $this->_inputInfo, $this->_arrStoreList[ $this->_shop ], 'Product Update' );          
  }

  public function product_delete()
  {
    header( "HTTP/1.1 200 OK" );   
    // Log
    $this->Log_model->add( 'Webhook', 'Product Delete', $this->_inputInfo, '' );
    
    // Define the product model
    $this->load->model( 'Product_model' );
    $this->Product_model->rewriteParam( $this->_shop );
    
    // Delete Product
    $this->Product_model->deleteProduct( $this->_inputInfo );   
  }
  
}
    
