<?php
class Output_model extends Master_model
{
    protected $_tablename = 'settings';
    private $_total_count = 0;
    
    function __construct() {
        parent::__construct();
    }
    
    /**
        Get settings of Output
    */
    public function getList()
    {
        $where = array();

        // Build the where clause
        $where['shop'] = $this->_shop;
        
        // Select fields
        $this->db->select( '*' );

        foreach( $where as $key => $val )
        if( $val == '' )
            $this->db->where( $key );
        else
            $this->db->where( $key, $val );
        $query = $this->db->get( $this->_tablename );
        
        return $query;
    }
    
    /**
    * Save and Update settings
    * 
    */
    function save($data)
    {
        $query = $this->db->get_where( $this->_tablename, 'shop = \'' . $this->_shop . '\'');
        $result = $query->result();
        
        if( count( $result ) <= 0 )
            $this->db->insert( $this->_tablename, $data);
        else
            $this->db->update( $this->_tablename, $data);
        
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
}
