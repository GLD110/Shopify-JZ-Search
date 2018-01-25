<?php
class Store_model extends Master_model
{
  protected $_tablename = 'token';
  
  function __construct() {
    parent::__construct();
  }
  
    /**
    * Get the list of account
    * 
    * @param mixed $team_id
    */
    public function getList()
    {
      $sql = 'SELECT * FROM ' . $this->_tablename;
          
      $query = $this->db->query($sql);

      return $query;
    }
  
    public function add( $data )
    {
      $this->db->insert( $this->_tablename, $data);
      if($this->db->affected_rows()>0)
      {    
         return true;
      }
      else
      {
         return false;
      }
    }  
}  
?>