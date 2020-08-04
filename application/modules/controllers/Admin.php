<?  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller 
{
	function __construct()	{
		parent::__construct();
	}


	public function _remap($method){
		$this->segs = $this->uri->segment_array();

		if($this->input->is_ajax_request()){
			if( method_exists($this, $method) ){
				$this->{"{$method}"}(); 
			}
		}else{ //ajax가 아니면

			if( method_exists($this, $method) ){
				$this->{"{$method}"}();
			}

			//$this->output->enable_profiler(true);
		}
	}
	
	function index()
	{
		
		$data = array();
	
		echo "MX admin";
	}//end index 	

 	
}

	
