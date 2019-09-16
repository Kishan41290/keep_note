<?php  if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ckuploader extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('manage/admin_model');
		$this->admin_model->check_seesion(current_url(), array(ROLE_SUPPER_ADMIN));
		$this->_supported_extensions = array('jpg', 'jpeg', 'gif', 'png', 'pdf');
	}
	public function index()
	{
		show_404();
	}
	
	public function upload()
    {
        $callback = 'null';
        $url = '';
        $get = array();
 
        // for form action, pull CKEditorFuncNum from GET string. e.g., 4 from
        // /form/upload?CKEditor=content&CKEditorFuncNum=4&langCode=en
        // Convert GET parameters to PHP variables
        $qry = $_SERVER['REQUEST_URI'];
        parse_str(substr($qry, strpos($qry, '?') + 1), $get);
 
        if (!isset($_POST) || !isset($get['CKEditorFuncNum'])) {
            $msg = $this->lang->line('ckeditor_notfound');
        } else {
            $callback = $get['CKEditorFuncNum'];
 
            try {
                $url = $this->_move_image($_FILES['upload']);
                $msg = "File uploaded successfully to: {$url}";
 
                // Persist additions to file manager CMS here.
 
            } catch (Exception $e) {
                $url = '';
                $msg = $e->getMessage();
            }
        }
 
        $output = '<html><body><script type="text/javascript">' .
            'window.parent.CKEDITOR.tools.callFunction(' .
            $callback .
            ', "' .
            $url .
            '", "' .
            $msg .
            '");</script></body></html>';
 
        echo $output;
    }
	
	private function _move_image($temp_location)
    {
        $IMAGE_UPLOAD_DIR = $this->config->item('ckuploadfile');
		$filename = basename($temp_location['name']);
        $info = pathinfo($filename);
        $ext = strtolower($info['extension']);
 
        if (isset($temp_location['tmp_name']) &&
            isset($info['extension']) &&
            in_array($ext, $this->_supported_extensions)) {
            echo $new_file_path = $IMAGE_UPLOAD_DIR . "/$filename";
            if (!is_dir($IMAGE_UPLOAD_DIR) ||
               !is_writable($IMAGE_UPLOAD_DIR)) {
                // Attempt to auto-create upload directory.
                if (!is_writable($IMAGE_UPLOAD_DIR) ||
                    FALSE === @mkdir($IMAGE_UPLOAD_DIR, null , TRUE)) {
                    throw new Exception('Error: File permission issue, ' .
                        'please consult your system administrator');
                }
            }
 
            if (move_uploaded_file($temp_location['tmp_name'], $new_file_path)) {
                return '/' . $new_file_path;
            }
        }
 
        throw new Exception('File could not be uploaded.');
    }
}

/* End of file home.php */
/* Location: ./application/controllers/manage/home.php */