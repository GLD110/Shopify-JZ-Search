<?php
class Process_model extends CI_Model
{
  function __construct() {
  }
  
  public function order_create( $order, $shopInfo, $method = 'Order Created' )
  {
    $CI =& get_instance();
    
    // Define the order models
    $CI->load->model( 'Order_model' );
    $CI->Order_model->rewriteParam( $shopInfo->shop );

    // Create Order
    $is_new = $CI->Order_model->add( $order );
    
    // If the order status is paid, we proceed it
    // if( $shopInfo->order_status != 'all' && $shopInfo->order_status != $order->financial_status ) return;
  }
  
  public function product_create( $product, $shopInfo, $method = 'Product Create' )
  {
    $CI =& get_instance();
    
    // Define the order models
    $CI->load->model( 'Product_model' );
    $CI->Product_model->rewriteParam( $shopInfo->shop );
        
    // Define the Collections
    $product->categories = array();
    
    // Create Product
    $CI->Product_model->add( $product );
    
  }
  
  public function adjust()
  {
    $this->load->model( 'Shopify_model' );
    $this->load->model( 'Inventory_model' );
    $this->load->model( 'Store_model' );
    $this->load->model( 'Log_model' );
    
    // Clear it
    $this->db->query( 'TRUNCATE TABLE `inventory`' );
    
    // get the store list
    $query = $this->Store_model->getList();

    if( $query->num_rows() > 0 )
    foreach( $query->result() as $shopInfo )
    {
      $this->Shopify_model->setStore( $shopInfo->shop, $shopInfo->app_id, $shopInfo->app_secret );
      $this->Inventory_model->rewriteParam( $shopInfo->shop );
      
      $page = 1;
      $down_count = 0;
      $all_count = 0;
      $last_adjust_order_date = time();
      $strOrderList = '';

      do{
        $action = 'orders.json?limit=250&status=open&fulfillment_status=unshipped';
        $action .= '&page=' . $page;
        if( $shopInfo->order_status != 'all' ) $action .= '&financial_status=' . $shopInfo->order_status;
        if( $shopInfo->last_adjust_order_date != '' ) $action .= '&created_at_min=' . $shopInfo->last_adjust_order_date;

        // Retrive Data from Shop
        $orderInfo = $this->Shopify_model->accessAPI( $action );

        foreach( $orderInfo->orders as $order )
        {
          // Get the last order date
          if( $last_adjust_order_date > strtotime( $order->created_at ) ) $last_adjust_order_date = strtotime( $order->created_at );
          
          $order_name = trim( $order->name , '#' );
          $strOrderList .= $order->name . ' ';
          
          $all_count ++;
          foreach( $order->line_items as $line_item )
          {
            $data = array( 
              'order_id' => $order->id,
              'order_name' => trim($order->name, '#'),
              'sku' => $line_item->sku,
              'quantity' => $line_item->quantity,
              'variant_id' => isset( $line_item->variant_id ) ? $line_item->variant_id : '',
            );

            $this->Inventory_model->add( $data );
          }
        }

        $down_count = count( $orderInfo->orders );
        $page ++;
      }while( $down_count > 0 );
      
      // Update the Store Information
      $this->Store_model->update( $shopInfo->id, array( 'last_adjust_order_date' => date( 'Y-m-d', $last_adjust_order_date)) );
      
      // Log
      $message = '';  
      $message .= $strOrderList;
      $message .= ' : ' . $all_count . ' Open Orders';
      
      $this->Log_model->add( 'CronJob', 'Adjustment', $shopInfo->shop, $message );
    }
  }
  
  public function install( $shop, $app_id = '', $app_secret = '', $access_token = '' )
  {
    // ********* Register the Script Tags ********* //
    $CI =& get_instance();
    $CI->load->model( 'Shopify_model' );
    
    if( $app_id != '' ) $CI->Shopify_model->setStore( $shop, $app_id, $app_secret );
    if( $access_token != '' ) $CI->Shopify_model->rewriteParam( $shop, $access_token );

    // Define base url
    $base_url = $this->config->item('base_url') . $this->config->item('index_page');
    if( substr( $base_url, -1 ) != '/' ) $base_url .= '/';
    
    // Webhook
    foreach( $this->config->item('WEBHOOK_LIST') as $topic => $address )
    {
      $arrParam = array(
        'webhook' => array(
          'topic' => $topic,
          'address' => $base_url . 'endpoint/' . $address,
          'format' => 'json',
        ),
      );
      $return = $this->Shopify_model->accessAPI( 'webhooks.json', $arrParam, 'POST' );
    }
    
    $arrParam = array(
      'webhook' => array(
        'topic' => 'app/uninstalled',
        'address' => $base_url . 'install/uninstall',
        'format' => 'json',
      ),
    );
    
    $return = $this->Shopify_model->accessAPI( 'webhooks.json', $arrParam, 'POST' );
    
    // Script Tag
    foreach( $this->config->item('SCRIPT_TAG_LIST') as $script )
    {
      $arrParam = array(
        'script_tag' => array(
            'event' => 'onload',
            'display_scope' => 'all',
            'src' => base_url( $script ),
        ),
      );
      $return = $this->Shopify_model->accessAPI( 'script_tags.json', $arrParam, 'POST' );
    }
  }
      
  public function uninstall( $shop, $app_id = '', $app_secret = '', $access_token = '' )
  {
    // Get access token
    $CI =& get_instance();
    $CI->load->model( 'Shopify_model' );

    if( $app_id != '' ) $CI->Shopify_model->setStore( $shop, $app_id, $app_secret );
    if( $access_token != '' ) $CI->Shopify_model->rewriteParam( $shop, $access_token );
    
    // Delete webhooks
    $return = $CI->Shopify_model->accessAPI( 'webhooks.json' );

    if( isset( $return->webhooks ) && count( $return->webhooks ) > 0 )
    foreach( $return->webhooks as $webhook )
    {
      $returnDelete = $CI->Shopify_model->accessAPI( 'webhooks/' . $webhook->id . '.json', array(), 'DELETE' );
    }
    
    // Delete Script Tag
    $return = $CI->Shopify_model->accessAPI( 'script_tags.json' );
    
    if( isset( $return->script_tags ) && count( $return->script_tags ) > 0 )
    foreach( $return->script_tags as $tag )
    {
      $returnDelete = $CI->Shopify_model->accessAPI( 'script_tags/' . $tag->id . '.json', array(), 'DELETE' );
    }
  }     
}  
?>
