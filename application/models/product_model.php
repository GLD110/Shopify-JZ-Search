<?php
class Product_model extends Master_model
{
    protected $_tablename = 'product';
    private $_total_count = 0;
    private $_arrProductKey = array();
    
    function __construct() {
      parent::__construct();
      
      // Get the variant id list
      $query = $this->getList( array() );
      
      if( $query->num_rows > 0 )
      foreach( $query->result() as $row )
      {
        $this->_arrProductKey[ '_' . $row->variant_id ] = $row->id; 
      }
    }

    public function getTotalCount(){ return $this->_total_count; }
    
    /**
    * Get the list of product/ varints
    * array(
    *     'supplier' => '',   // String
    *     'name' => '',       // String
    *     'sku' => '',        // String
    *     'supplier_category' => '',   // String
    *     'price' => '',               // String "{from} {to}"
    *     'product_id' => '',             // String
    *     'variant_id' => '',             // String
    *     'sort' => '',                   // String "{column} {order}"
    *     'product_only' => '',           // Boolean true/false : default :false
    *     'page_number' => '',            // Int, default : 0
    *     'page_size' => '',              // Int, default Confing['PAGE_SIZE'];
    *     'is_imported' => '',            // Int, 0: all, 1: published, 2: not-published / default : 0
    *     'is_queue' => '',               // Int, 0: all, 1: queue, 2: not-queue, / default : 0
    *     'is_stock' => '',               // Int, 0: all, 1: in stock, 2: out of stock / default 0
    );
    */
    public function getList( $arrCondition )
    {
        $where = array( 'shop' => $this->_shop );
        
        // Build the where clause
        if( !empty( $arrCondition['name'] ) ) $where["title LIKE '%" . str_replace( "'", "\\'", $arrCondition['name'] ) . "%'"] = '';
        if( !empty( $arrCondition['sku'] ) ) $where["sku LIKE '%" . str_replace( "'", "\\'", $arrCondition['sku'] ) . "%'"] = '';
        if( !empty( $arrCondition['variant_id'] ) ) $where['variant_id'] = $arrCondition['variant_id'];
        
        // Product only - Group by, Get total records
        if( isset( $arrCondition['page_number'] ) )
        {
            // Get the count of records
            foreach( $where as $key => $val )
            if( $val == '' )
                $this->db->where( $key );
            else
                $this->db->where( $key, $val );
            $query = $this->db->get( $this->_tablename);
            $this->_total_count = $query->num_rows();
        }
        
        // Sort
        if( isset( $arrCondition['sort'] ) ) $this->db->order_by( $arrCondition['sort'] );
        $this->db->order_by( 'product_id', 'DESC' );

        // Limit
        if( isset( $arrCondition['page_number'] ) )
        {
            $page_size = isset( $arrCondition['page_size'] ) ? $arrCondition['page_size'] : $this->config->item('PAGE_SIZE');
            $this->db->limit( $page_size, $arrCondition['page_number'] );
        }

        foreach( $where as $key => $val )
        if( $val == '' )
            $this->db->where( $key );
        else
            $this->db->where( $key, $val );
        $query = $this->db->get_where( $this->_tablename );
        
        return $query;
    }
    
    // Mark all the products to be unexist
    public function markUnexist()
    {
        $this->db->where( 'shop', $this->_shop );
        $this->db->update( $this->_tablename, array( 'is_exist' => 0 ) );
    }
    
    // Clean the unexist products
    public function cleanUnexist()
    {
      $arrWhere = array(
        'shop' => $this->_shop,
        'is_exist' => 0,
      );
        
      $this->db->delete( $this->_tablename, $arrWhere );
    }
    
    // Get last updated date
    public function getLastUpdateDate()
    {
        $return = '';
        
        $this->db->select( 'updated_at' );
        $this->db->order_by( 'updated_at DESC' );
        $this->db->limit( 1 );
        $this->db->where( 'shop', $this->_shop );
        
        $query = $this->db->get( $this->_tablename );
        
        if( $query->num_rows() > 0 )
        {
            $res = $query->result();
            
            $return = $res[0]->updated_at;
        }
        
        return $return;
    }    
    
    // Add product to database
    public function add( $product )
    {
      // Get the images as array
      $arrImage = array();
      foreach( $product->images as $item ) $arrImage[ $item->id ] = $item->src;
      
      foreach( $product->variants as $variant )
      {
        // Get image id
        $image_url = '';
        if( !empty($variant->image_id) ) $image_url = $arrImage[$variant->image_id];
        if( $image_url == '' && isset( $product->image->src ))
        {
          $image_url = $product->image->src;
        } 
        
        // Remove the existing product
        if( in_array( '_' . $variant->id, array_keys( $this->_arrProductKey )))
        {
          $this->delete( $this->_arrProductKey[ '_' . $variant->id ] );
        }
        
        // Add the new variant
        $newProductInfo = array(
          'title' => $product->title,
          'variant_title' => $variant->title,
          'product_id' => $product->id,
          'variant_id' => $variant->id,
          'sku' => $variant->sku,
          'body_html' => base64_encode($product->body_html),
          'categories' => implode( ',', $product->categories ),
          'handle' => $product->handle,
          'price' => $variant->price,
          'position' => $variant->position,
          'updated_at' => date( $this->config->item('CONST_DATE_FORMAT'), strtotime($variant->updated_at)),
          'is_exist' => 1,
          'image_url' => $image_url,
          'data' => base64_encode( json_encode( $variant ) ),
        );
        
        parent::add( $newProductInfo );
      }      
    }
    
    // Delete the product from product_id
    public function deleteProduct( $product_id )
    {
      $this->db->delete( $this->_tablename, array( 'product_id' => $product_id, 'shop' => $this->_shop ) );
      if( $this->db->affected_rows() > 0 )
          return true;
      else
          return false;
      
    }
    
    // Get the variant object from variant_id
    public function getVariant( $variant_id )
    {
      $returnObj = '';
      
      $query = $this->getList( array( 'variant_id' => $variant_id ) );
      
      if( $query->num_rows() > 0 )
      foreach( $query->result() as $row )
      {
        $row->data = json_decode( base64_decode($row->data));
        $returnObj = $row;
      }
      
      return $returnObj;
    }
    
    // Get variant ID from SKU
    public function getVariantIdFromSku( $sku )
    {
      $return = '';
      
      $query = $this->getList( array( 'sku' => $sku ) );
      
      if( $query->num_rows() > 0 )
      foreach( $query->result() as $row )
      {
        $return = $row->variant_id;
      }
      
      return $return;
    }
    // ********************** //
}  
?>
