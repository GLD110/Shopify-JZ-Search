<?php
class Make_model extends Master_model
{
    protected $_tablename = 'make';
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
        $sql = 'SELECT * FROM ' . $this->_tablename . ' ORDER BY `prefix` ASC ';

        $query = $this->db->query($sql);

        return $query;
    }

    function createMake(){
        $prefix = $this->input->post('prefix');
        $is_active = $this->input->post('is_active');
        $created = date("Y/m/d");

        $data = array(
            'prefix'=>$prefix,
            'is_active'=>$is_active,
            'd_o_c'=>$created,
            'shop'=>$this->_shop,
        );

        $this->db->insert( $this->_tablename, $data);
        if($this->db->affected_rows()>0){
            return true;
        }
        else{
            return FALSE;
        }
    }
}
?>
