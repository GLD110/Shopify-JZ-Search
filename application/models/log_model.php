<?php
class Log_model extends Master_model
{
    protected $_tablename = 'log';
    private $_total_count = 0;
    
    function __construct() {
    }

    public function getTotalCount(){ return $this->_total_count; }

    /**
    * Get the Style information and error message
    * 
    */
    public function getList( $arrCondition )
    {
        $where = array();

        // Build the where clause
        if( !empty( $arrCondition['type'] ) && $arrCondition['type'] != 'ALL' ) $where['type'] = $arrCondition['type'];
        //if( !empty( $arrCondition['action'] ) ) $where['action'] = $arrCondition['action'];
        if( !empty( $arrCondition['input'] ) ) $where["input LIKE '%" . str_replace( "'", "\\'", $arrCondition['input'] ) . "%'"] = '';
        
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
        $this->db->order_by( 'log_date', 'DESC' );

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
    * Add the log entry
    * 
    * @param mixed $post
    */
    public function add( $type, $action, $input, $message )
    {
      $data = array(
        'type' => $type,
        'input' => $input,
        'message' => $message,
        'action' => $action,
        'log_date' => date( $this->config->item('CONST_DATE_FORMAT')),
      );
      
      parent::add( $data );
    }
    
    public function clearLog()
    {
      $sql = 'TRUNCATE TABLE `' . $this->_tablename . '`';
      $this->db->query( $sql );
    }
}
?>
