<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hook
{
	public function __construct()
	{
	}

	// 시스템 작동초기입니다.벤치마크와 후킹클래스들만 로드된 상태로서,
	// 라우팅을 비롯한 어떤 다른 프로세스도 진행되지않은 상태입니다.
	public function pre_system()
	{
	}

	// 컨트롤러가 호출되기 직전입니다.
	// 모든 기반클래스(base classes), 라우팅 그리고 보안점검이 완료된 상태입니다.
	public function pre_controller()
	{
	}

	// 컨트롤러가 인스턴스화 된 직후입니다.즉 사용준비가 완료된 상태가 되겠죠.
	// 하지만, 인스턴스화 된 후 메소드들이 호출되기 직전입니다.
	public function post_controller_constructor()
	{
		//$ci =& get_instance();
	}

	// 컨트롤러가 완전히 수행된 직후입니다.
	public function post_controller()
	{
	}

	// _display() 함수를 재정의 합니다.최종적으로 브라우저에 페이지를 전송할 때 사용됩니다.
	// 이로서 당신만의 표시 방법( display methodology)을사용할 수 있습니다.
	// 주의 : CI 부모객체(superobject)를 $this->CI =& get_instance() 로 호출하여 사용한 후에
	// 최종데이터 작성은 $this->CI->output->get_output() 함수를 호출하여 할 수 있습니다.
	public function display_override()
	{
	}

	// 출력(Output)Library 에 있는 _display_cache() 함수 대신 당신의 스크립트를 호출할 수 있도록 해줍니다.
	// 이로서 당신만의 캐시 표시 메커니즘(cache display mechanism)을 적용할 수 있습니다.
	public function cache_override()
	{
	}

	// 최종 렌더링 페이지가 브라우저로 보내진후에 호출됩니다.
	public function post_system()
	{
	}


	// 서비스 점검
	public function service_check()
	{
	}
}
