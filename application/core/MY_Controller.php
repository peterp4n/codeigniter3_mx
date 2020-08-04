<?php defined('BASEPATH') or exit('No direct script access allowed');
class MY_Controller extends CI_Controller
{

	/*
	 *  현재 시간
	 */
	public $today = '';

	/*
	 * csrf
	 */
	public $csrf = '';

	public function __construct()
	{
		parent::__construct();

		// 프로파일
		$sections = array(
			'get' => true,
			'post' => true,
			'session_data' => true,
			'benchmarks' => true,
			'config' => true,
			'controller_info' => true,
			'http_headers' => true,
			'memory_usage' => true,
			'queries' => true,
			'uri_string' => true,
			'query_toggle_count' => true,
		);
		$this->output->set_profiler_sections($sections);
		$this->output->enable_profiler($this->config->item('profiler'));

		// get today
		$this->today = date("Y-m-d H:i:s");

		// csrf
		$this->csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);

	}

	public function _header()
	{
		$view_file = '';
		$data = array();
		$this->load->view($view_file, $data);
	}

	public function _footer()
	{
		$view_file = '';
		$data = array();
		$this->load->view($view_file, $data);
	}

	public function _pop_header()
	{
		$view_file = '';
		$data = array();
		$this->load->view($view_file, $data);
	}

	public function _pop_footer()
	{
		$view_file = '';
		$data = array();
		$this->load->view($view_file, $data);
	}

	public function _remap($method)
	{
		// check exists method
		if (method_exists($this, $method)) {
			if ($this->input->is_ajax_request()) {
				//method
				$this->$method();
			} else {
				$uri_string = $this->uri->uri_string;
				switch (PATH_INFO) {
					case '/' :
					case '/method1':
					case '/method2':
						// header
						$this->_pop_header();

						$this->$method();

						// footer
						$this->_pop_footer();
						break;
					case '/language':
						$this->$method();
						break;
					default:
						// header
						$this->_header();

						// method
						$this->$method();

						// footer
						$this->_footer();
						break;
				}
			}
		} else {
			$msg = strtolower(get_class($this)) . '/' . $method;
			show_404($msg);
		}
	}
}
