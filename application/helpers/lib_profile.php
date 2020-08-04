<?php

// memory info
function get_server_memory_usage(){

	$free = shell_exec('free');
	$free = (string)trim($free);
	$free_arr = explode("\n", $free);
	$mem = explode(" ", $free_arr[1]);
	$mem = array_filter($mem);
	$mem = array_merge($mem);
	$mem_usage = $mem[2]/$mem[1]*100;

	return number_format($mem_usage,2);
}

// cpu info
function get_server_cpu_usage(){
	// exec("top -n 1 | grep -i cpu\(s\)| awk '{print $5}' | tr -d \"%id,\" | awk '{print 100-$1}'");
	//$output = shell_exec('cat /proc/loadavg');
	//$loadavg = substr($output,0,strpos($output," "));
	$load = sys_getloadavg();
	return $load[0];
}

function get_ci_file_info() {
	if(AJAX!='XMLHttpRequest') {
		echo '<div style="display:none;">'.SERVER_ADDR.'</div>';
		echo '<div id="debug_info" style="display:none; max-height:80%; overflow-y:scroll; padding:10px; z-index:2000; position:fixed; left:10px; bottom:10px; font-size:16px; color:#333; background:#fff; border:3px dashed #555;">';
		echo 'CPU 사용량  <span style="font-weight:bold; color:red;padding-right:10px">'.get_server_cpu_usage().'%</span>';
		echo '메모리 사용량 <span style="font-weight:bold; color:red;">'.get_server_memory_usage().'%</span><br>';
		debug(get_included_files());
		echo '<button type="button" id="debug_close" style="width:40px; height:30px; background:#555; text-align:center; font-size:13px; line-height:30px; text-indent:0; color:#fff; position:absolute; right:0; top:0;">닫기</button></div>';
		echo '<script>$("#debug_close").click(function(){ $("#debug_info").hide(); }); </script>';
	}
}
