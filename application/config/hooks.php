<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

/*
 *
 *    pre_system 시스템 작동초기입니다.벤치마크와 후킹클래스들만 로드된 상태로서, 라우팅을 비롯한 어떤 다른 프로세스도 진행되지않은 상태입니다.
 *    pre_controller 컨트롤러가 호출되기 직전입니다. 모든 기반클래스(base classes), 라우팅 그리고 보안점검이 완료된 상태입니다.
 *    post_controller_constructor 컨트롤러가 인스턴스화 된 직후입니다.즉 사용준비가 완료된 상태가 되겠죠. 하지만, 인스턴스화 된 후 메소드들이 호출되기 직전입니다.
 *    post_controller 컨트롤러가 완전히 수행된 직후입니다.
 *    display_override _display() 함수를 재정의 합니다.최종적으로 브라우저에 페이지를 전송할 때 사용됩니다. 이로서 당신만의 표시 방법( display methodology)을사용할 수 있습니다.
      주의 : CI 부모객체(superobject)를 $this->CI =& get_instance() 로 호출하여 사용한 후에 최종데이터 작성은 $this->CI->output->get_output() 함수를 호출하여 할 수 있습니다.
 *    cache_override 출력라이브러리(Output Library) 에 있는 _display_cache() 함수 대신 당신의 스크립트를 호출할 수 있도록 해줍니다.
      이로서 당신만의 캐시 표시 메커니즘(cache display mechanism)을 적용할 수 있습니다.
 *    post_system최종 렌더링 페이지가 브라우저로 보내진후에 호출됩니다.
 *
 *
 * 클래스(class) 당신이 호출하고자 하는 클래스의 이름입니다. 만약 클래스 대신 순차 함수(procedural function)를 사용한다면 이 항목을 공백으로 해주세요.
 * 함수(function) 호출하고자 하는 함수의 이름입니다.
 * 파일이름(filename) 당신의 스크립트(클래스, 함수)를 정의해둔 파일이름입니다.
 * 파일경로(filepath) 파일경로(파일명을 제외한 디렉토리경로)입니다. Note: 당신의 스크립트는 반드시 application/ 폴더 안에 있어야 합니다.
                    파일경로는 그 폴더로부터의 상대경로로 설정됩니다. 예를 들어,당신의 스크립트가 application/hooks/ 에 있다면,파일경로는 hooks 가 됩니다.
					당신의 스크립트가 application/hooks/utilities/ 에 있다면 파일경로는 ‘hooks/utilities’ 가 되며 끝부분에 슬래시(/)가 붙지않습니다.
 * 파라미터들(params) 스크립트로 전달하고자 하는파라미터. 파라미터는 옵션입니다.
*/

if(AJAX !== 'xmlhttprequest') {

	$hook['pre_system'][] = [
		'class'    => 'Hook',
		'function' => 'pre_system',
		'filename' => 'Hook.php',
		'filepath' => 'hooks',
		//'params'   => [],
	];

	$hook['pre_controller'][] = [
		'class'    => 'Hook',
		'function' => 'pre_controller',
		'filename' => 'Hook.php',
		'filepath' => 'hooks',
		//'params'   => [],
	];

	$hook['post_controller_constructor'][] = [
		'class'    => 'Hook',
		'function' => 'post_controller_constructor',
		'filename' => 'Hook.php',
		'filepath' => 'hooks',
		//'params'   => [],
	];

	$hook['post_controller'][] = [
		'class'    => 'Hook',
		'function' => 'post_controller',
		'filename' => 'Hook.php',
		'filepath' => 'hooks',
		//'params'   => [],
	];

	$hook['post_system'] = [
		'class'    => 'Hook',
		'function' => 'post_system',
		'filename' => 'Hook.php',
		'filepath' => 'hooks',
		//'params'   => [],
	];

	/*
	$hook['display_override'][] = [
		'class'    => 'Hook',
		'function' => 'display_override',
		'filename' => 'Hook.php',
		'filepath' => 'hooks',
		//'params'   => [],
	];

	$hook['cache_override'] = [
		'class'    => 'Hook',
		'function' => 'cache_override',
		'filename' => 'Hook.php',
		'filepath' => 'hooks',
		//'params'   => [],
	];

	*/

}
