<?php
class Vehicle_model extends Master_model
{
    protected $_tablename = 'vehicle';
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
        $sql = 'SELECT * FROM ' . $this->_tablename . ' ORDER BY `make` ASC ';

        $query = $this->db->query($sql);

        return $query;
    }

    public function getVehicles($arrCondition)
    {
        $sql = 'SELECT DISTINCT `model` FROM `' . $this->_tablename . '` WHERE `make` = "' . $arrCondition['make'] . '"';

        if(isset($arrCondition['model'])){
          $sql = 'SELECT * FROM `' . $this->_tablename . '` WHERE `make` = "' . $arrCondition['make'] . '"';
          $sql = $sql . ' AND `model` = "' . $arrCondition['model'] . '"';
        }
        if(isset($arrCondition['year']))
          $sql = $sql . ' AND ((`start_year` < "' . $arrCondition['year'] . '" AND `end_year` > "' . $arrCondition['year'] . '") OR (`start_year` = "' . $arrCondition['year'] .'") OR (`start_year` = "' . $arrCondition['year'] .'"))';

        $query = $this->db->query($sql);
        return $query;
    }

    function importCSV(array $csv)
    {
      foreach($csv as $vehicle){
        $data = array(
            'make'=>$vehicle['make'],
            'model'=>$vehicle['model'],
            'start_year'=>$vehicle['start_year'],
            'end_year'=>$vehicle['end_year'],
            'bolt_pattern_cm'=>$vehicle['bolt_pattern_cm'],
            'oem_tire_size'=>$vehicle['oem_tire_size'],
            'oem_wheel_size'=>$vehicle['oem_wheel_size'],
            'plus_tire_size'=>$vehicle['plus_tire_size'],
            'plus_wheel_size'=>$vehicle['plus_wheel_size'],
            'shop'=>$this->_shop,
        );
        $this->db->insert( $this->_tablename, $data);
      }
      if($this->db->affected_rows()>0){
          return true;
      }
      else{
          return FALSE;
      }
    }

    function createVehicle(){
        $make = $this->input->post('sel_make');
        $sql = 'SELECT `prefix` FROM ' . 'make' . ' WHERE `id` = ' . $make;
        $query = $this->db->query($sql);
        $temp = $query->result();
        $make = $temp[0]->prefix;

        $model = $this->input->post('sel_model');
        $sql = 'SELECT `prefix` FROM ' . 'model' . ' WHERE `id` = ' . $model;
        $query = $this->db->query($sql);
        $temp = $query->result();
        $model = $temp[0]->prefix;

        $start_year = $this->input->post('start_year');
        $end_year = $this->input->post('end_year');
        $bolt_pattern_cm = $this->input->post('bolt_pattern_cm');
        $oem_tire_size = $this->input->post('oem_tire_size');
        $oem_wheel_size = $this->input->post('oem_wheel_size');
        $plus_tire_size = $this->input->post('plus_tire_size');
        $plus_wheel_size = $this->input->post('plus_wheel_size');

        $data = array(
          'make'=>$make,
          'model'=>$model,
          'start_year'=>$start_year,
          'end_year'=>$end_year,
          'bolt_pattern_cm'=>$bolt_pattern_cm,
          'oem_tire_size'=>$oem_tire_size,
          'oem_wheel_size'=>$oem_wheel_size,
          'plus_tire_size'=>$plus_tire_size,
          'plus_wheel_size'=>$plus_wheel_size,
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
