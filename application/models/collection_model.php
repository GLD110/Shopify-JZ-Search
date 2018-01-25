<?php
class Collection_model extends Master_model
{
  protected $_tablename = 'collection';
  private $_total_count = 0;
  private $_arrCollection = array();
  
  function __construct() {
    parent::__construct();
    
    // Get the collections
    $query = parent::getList();
    
    if( $query->num_rows() > 0 )
    foreach( $query->result() as $row )
    {
      $this->_arrCollection[$row->collection_id] = array(
        'id' => $row->id,
        'title' => $row->title,
      );
    }
  }

  public function getTotalCount(){ return $this->_total_count; }
  
  public function getCollectionList(){ return $this->_arrCollection; }
  
  /**
  * Get the list of product/ varints
  * array(
  *     'customer_name' => '',       // String
  *     'sort' => '',                   // String "{column} {order}"
  *     'page_number' => '',            // Int, default : 0
  *     'page_size' => '',              // Int, default Confing['PAGE_SIZE'];
  *     'is_coupon' => '',              // Int, 0: all, 1: discount, 2: other / default : 0
  );
  */
  public function getList( $arrCondition )
  {
    $where = array();

    // Build the where clause
    if( !empty( $arrCondition['title'] ) ) $where['title LIKE \'%' . $arrCondition['title'] . '%\''] = '';
    
    // Get the count of records
    foreach( $where as $key => $val )
    if( $val == '' )
        $this->db->where( $key );
    else
        $this->db->where( $key, $val );
    $query = $this->db->get( $this->_tablename);
    $this->_total_count = $query->num_rows();
    
    // Select fields
    $this->db->select( "*");
    
    // Sort
    if( isset( $arrCondition['sort'] ) ) $this->db->order_by( $arrCondition['sort'] );
    $this->db->order_by( 'updated_at', 'DESC' );

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
    $query = $this->db->get( $this->_tablename );
    
    return $query;
  }
  
  /**
  * Add order and check whether it's exist already
  * 
  * @param mixed $order
  */
  public function addCollection( $collection )
  {
    if( isset( $this->_arrCollection[ $collection->id ] ) )
    {
      // If collection is exist
      if( $this->_arrCollection[ $collection->id ]['title'] == $collection->title )
      {
        // If the collection is the same, skip it
        return;
      }
      else
      {
        // If the title is not the same, update it
        $data = array(
          'title' => $collection->title,
        );
        
        parent::update( $this->_arrCollection[ $collection->id ]['id'], $data );
      }
    }
    else
    {
      // If collection is not exist, add it
      $data = array(
        'collection_id' => $collection->id,
        'title' => $collection->title,
        'body_html' => $collection->body_html,
        'updated_at' => date( $this->config->item('CONST_DATE_FORMAT'), strtotime($collection->updated_at)),
      );
      
      parent::add( $data );
    }
    return true;
  }
  
  // Get last updated date
  public function getLastUpdateDate()
  {
    $return = '';
    
    $this->db->select( 'updated_at' );
    $this->db->order_by( 'updated_at DESC' );
    $this->db->limit( 1 );
    
    $query = $this->db->get( $this->_tablename );
    
    if( $query->num_rows() > 0 )
    {
        $res = $query->result();
        
        $return = $res[0]->updated_at;
    }
    
    return $return;
  }
  
  // ********************** //
}  
?>
