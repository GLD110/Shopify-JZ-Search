<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Category_model');
    }

    public function index(){
        $this->is_logged_in();

        $this->manage();
    }

    function manage(){
        // Check the login
        $this->is_logged_in();

        $data['query'] =  $this->Category_model->getList( '', 'sort_order' );

        $this->load->view('view_header');
        $this->load->view('view_category', $data);
        $this->load->view('view_footer');
    }

    function del(){
        $id = $this->input->get_post('del_id');
        $returnDelete = $this->Category_model->delete( $id );
        if( $returnDelete === true ){
            $this->session->set_flashdata('falsh', '<p class="alert alert-success">One Category is deleted successfully</p>');
        }
        else{
            $this->session->set_flashdata('falsh', '<p class="alert alert-danger">Sorry! Category unsuccessfully : ' . $returnDelete . '</p>');
        }

        redirect('category');
        exit;
    }

    function add(){
        $this->form_validation->set_rules('code', 'Category Code', 'required');
        $this->form_validation->set_rules('name', 'Category Name', 'required');

        if ($this->form_validation->run() == FALSE){
            echo validation_errors('<div class="alert alert-danger">', '</div>');
            exit;
        }
        else{
            if($this->Category_model->add()){
                echo '<div class="alert alert-success">This Category is added successfully</div>';
                exit;
            }
            else{
                echo '<div class="alert alert-danger">Sorry ! something went wrong </div>';
                exit;
            }
        }
    }

    // function update( $type, $pk ){
    //     $data = array();
    //
    //     switch( $type )
    //     {
    //         case 'code' : $data['code'] = strtolower( str_replace( array( ' ', ',' ), '_', $this->input->post('value') ) ); break;
    //         case 'name' : $data['name'] = $this->input->post('value'); break;
    //         case 'sort_order' : $data['sort_order'] = str_replace( ',', '.', $this->input->post('value') ); break;
    //     }
    //     $this->Category_model->update( $pk, $data );
    // }

    function upload( $pk )
    {
        // Get the Category object
        $objCategory = $this->Category_model->getInfo( $pk );

        // Load the file upload library
        $config['upload_path'] = './' . $this->config->item( 'CONST_UPLOAD_FOLDER' ) . '/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']    = '20480';

        $this->load->library('upload', $config);

        if ( $this->upload->do_upload( 'file_image_' . $pk ))
        {
            $upload_data = $this->upload->data();

            // Delete the file
            if( $objCategory->image_url != '' )
            {
                unlink( $upload_data['file_path'] . $objCategory->image_url );
            }

            // Update the filename
            $new_filename = 'category_' . $pk . $upload_data['file_ext'];
            rename( $upload_data['full_path'], $upload_data['file_path'] . $new_filename );


            // Define the update data
            $update_data = array(
                'image_url' => $new_filename,
            );

            $this->Category_model->update( $pk, $update_data );
        }

        $this->manage();
    }
}
