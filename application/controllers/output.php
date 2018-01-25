<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Output extends MY_Controller {
    
    public function __construct() {
      parent::__construct();
      $this->load->model( 'Output_model' );
      $this->load->model( 'Log_model' );    
      
      // Define the search values
      $this->_searchConf  = array(
        'shop' => $this->_default_store,
      );
      $this->_searchSession = 'output';
    }
    
    public function index(){
      $this->is_logged_in();
      
      $this->manage();
    }
    
    public function manage( $page =  0 ){
      // Check the login
      $this->is_logged_in();

      // Init the search value
      $this->initSearchValue();

      // Get data
      $this->Output_model->rewriteParam($this->_searchVal['shop']);
      if(isset($this->Output_model->getList()->result()[0]))        
          $data['settings'] =  $this->Output_model->getList()->result()[0];
      else
          $data['settings'] = null;
        
      // Define the rendering data
      $data = $data + $this->setRenderData();

      // Store List    
      $arr = array();
      foreach( $this->_arrStoreList as $shop => $row ) $arr[ $shop ] = $shop;
      $data['arrStoreList'] = $arr;

      $this->load->view('view_header');
      $this->load->view('view_output', $data );
      $this->load->view('view_footer');
    }
    
    public function save( )
    {
      // Check the login
      $this->is_logged_in();
        
      //Get Post data    
      $input = $this->input->post();  
        
      // Init the search value
      $this->initSearchValue();
        
        //var_dump($this->input->post()['in_shop']);exit;
        
      // save and update the output settings
      if($input['vendor_mail'] != '' || $input['ftp_uri'] != ''){    
          $arrList = $this->Output_model->save($input);
      }
      
      $this->manage();
    }   
    
    public function order_output($shop=''){
        
        $shop = $this->_default_store;
        $this->load->model( 'Order_model' );
        $created_at = date('Y-m-d');

        $arrCondition =  array(                    
           'created_at' => $created_at         
        );        
        $this->Order_model->rewriteParam($shop);
        
        $data['query'] =  $this->Order_model->getList( $arrCondition );
        $result = $data['query']->result();
         
        $this->Output_model->rewriteParam($this->_default_store);
        if(isset($this->Output_model->getList()->result()[0]))        
            $data =  $this->Output_model->getList()->result()[0];   
            
        if(sizeof($result) > 0){
            if(!($data->ftp_uri == ''))
                $this->send_file_ftp($this->create_csv_file($result));
            if(!($data->vendor_mail == ''))
                $this->send_csv_mail($this->create_csv_file($result), "Please check the attachment", $data->vendor_mail);
            //set exported_status = 1;
            $this->Order_model->setExported( $result );
        }
    }
    
    /*
        Create csv from database and send it as attachment
    */
    
    function create_csv_file($data) {
        
        $datetime=date("Y-m-d H-i-s");
        $file_name = "export-" .$datetime.".csv";
        $csv_filename = $this->config->item('app_path')."uploads/csv/".$file_name;

        // Open temp file pointer
        if (!$fp = fopen($csv_filename, 'w+')) return FALSE;

        fputcsv($fp, array('No', 'Order Number', 'Product Name', 'Customer', 'E-Mail', 'Total', 'Products', 'Country', 'Fulfillment Status', 'Checkout Date', 'Financial Status', 'SKU'));

        // Loop data and write to file pointer
        $i = 1;
        foreach($data as $line){
            $row = array($i, $line->order_name, $line->product_name, $line->customer_name, $line->email, $line->amount, $line->fulfillment_status, $line->num_products, $line->country, $line->created_at, $line->financial_status, $line->sku);
            
            fputcsv($fp, $row);
            $i++;
        }
        fclose($fp);
        
        return $file_name;
    }
    
    function send_file_ftp($file_name)
    {
          
        $csv_filename = $this->config->item('app_path')."uploads/csv/".$file_name;
        $csv_fileUrl = $this->config->item('base_url')."uploads/csv/".$file_name;
        
        $this->Output_model->rewriteParam($this->_default_store);
        if(isset($this->Output_model->getList()->result()[0]))        
            $data =  $this->Output_model->getList()->result()[0];        

        if(!($data->ftp_uri == '')){
            $ftp_server = $data->ftp_uri;
            $ftp_user_name = $data->ftp_id;
            $ftp_user_pass = $data->user_pwd;
            $file = $file_name;
            $remote_file = "/uploads/loc-exports/".$file;
            
            //var_dump($remote_file);exit;


            // set up basic connection
            $conn_id = ftp_connect($ftp_server);
            
            // login with username and password
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
            ftp_pasv($conn_id, true);

            // upload a file
            if (ftp_put($conn_id, $remote_file, $csv_filename, FTP_BINARY)) {
                $this->Log_model->add('CronJob', 'Send CSV via FTP', "<a href='$csv_fileUrl'>$file</a>", "successfully uploaded $file\n");
                echo "successfully uploaded $file\n";
            } else {
                $this->Log_model->add('CronJob', 'Send CSV via FTP', "<a href='$csv_fileUrl'>$file</a>", "There was a problem while uploading $file\n");
                echo "There was a problem while uploading $file\n";
            }

            // close the connection
            ftp_close($conn_id);
        }
    }

    function send_csv_mail($csvData, $body, $to = '', $subject = 'Order Report', $from = 'noreply@test.com') {

        // This will provide plenty adequate entropy
        $multipartSep = '-----'.md5(time()).'-----';

        // Arrays are much more readable
        $headers = array(
            "From: $from",
            "Reply-To: $from",
            "Content-Type: multipart/mixed; boundary=\"$multipartSep\""
        );
        
        $csv_filename = $this->config->item('app_path')."uploads/csv/".$csvData;
        $csv_fileUrl = $this->config->item('base_url')."uploads/csv/".$csvData;
        $fp = fopen($csv_filename, 'r');
        // Place stream pointer at beginning
        rewind($fp);

        // Make the attachment
        $attachment = chunk_split(base64_encode(stream_get_contents($fp)));

        // Make the body of the message
        $body = "--$multipartSep\r\n"
            . "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n"
            . "Content-Transfer-Encoding: 7bit\r\n"
            . "\r\n"
            . "$body\r\n"
            . "--$multipartSep\r\n"
            . "Content-Type: text/csv\r\n"
            . "Content-Transfer-Encoding: base64\r\n"
            . "Content-Disposition: attachment; filename=\"Website-Report-" . date("F-j-Y") . ".csv\"\r\n"
            . "\r\n"
            . "$attachment\r\n"
            . "--$multipartSep--";

        // Send the email, return the result
        $this->Log_model->add('CronJob', 'Send CSV via Email', "<a href='$csv_fileUrl'>$csvData</a>", 'CSV was sent to ' . $to);
        return @mail($to, $subject, $body, implode("\r\n", $headers));
    }       
}

