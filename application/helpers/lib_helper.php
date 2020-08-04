<?php

/**
 * php 7.3 이상 지원하는 함수
 */
if (!function_exists('array_key_first')) {
	function array_key_first(array $arr) {
		foreach($arr as $key => $unused) {
			return $key;
		}
		return NULL;
	}
}

/**
 * 날짜 유효성 확인
 */
function validateDate($date, $format = 'Y-m-d')
{
	$d = DateTime::createFromFormat($format, $date);
	$day = $d->format($format);
	$check = ($date==$day) ? 1 : 0;
	return $check;
}

/**
 * 암호화
 */
function encrypt($pass, $opt='') {
	return ($opt != '') ? password_verify($pass, $opt) : password_hash($pass, PASSWORD_BCRYPT);
}

/**
 * _GET/_POST 가져오기
 * 사용법 : getVariance($val);
 */
function getVariance($val)
{
	$tmp = false;
	if (isset($_POST[$val])) {
		$tmp = $_POST[$val];
	} elseif (isset($_GET[$val])) {
		$tmp = $_GET[$val];
	} elseif (isset($_SESSION[$val])) {
		$tmp = $_SESSION[$val];
	}
	$tmp = str_replace(chr(39), "&#39;", $tmp);
	$tmp = str_replace(chr(34), "&#34;", $tmp);
	if (!is_array($tmp)) {
		$tmp = trim($tmp);
		$tmp = stripslashes($tmp);
	}
	return $tmp;
}

/**
 * 변수 초기값 설정함수
 * 사용법 : reqStr($strValue, $changeSet)
 * $starValue : 변수값
 * $changeSet : 변경값
 */
function reqStr($strValue, $changeSet)
{
	$tempString = trim($strValue);

	$tempString =  ($tempString = "" || strlen($tempString) < 1) ? $changeSet : trim($strValue);
	return $tempString;
}

/**
 * 디버깅용 함수
 * 사용법 : debug($opt);
 */
function debug($val, $opt="")
{
	if (!defined('REMOTE_ADDR')) {
		define("REMOTE_ADDR", $_SERVER["REMOTE_ADDR"]);
	}
	switch (REMOTE_ADDR) {
		case "127.0.0.1":
		case "remote_ip":
		case "localhost":
			echo '<xmp style="width:100%;background:#000; color:#43db20; font-size:11px; line-height:16px; text-align:left; position:relative; z-index:1000;">';
			if (is_array($val) || is_object($val)) {
				print_r($val);
			} else {
				echo $val;
			}
			echo "</xmp>";
			if ($opt == "T") {
				exit;
			}
			break;
		default:
			break;
	}
}

/**
 * 오라클용 페이지구하기 쿼리
 */
function fn_paging_order($sql, $page, $per_page) {
	$page = ($page!='') ? $page : 1;
	$start = (($page - 1) * $per_page) ;
	$end = $page * $per_page + 1;
	if($per_page > 0) {
		$sql = "SELECT * FROM (
					 SELECT ROWNUM AS RNUM, Z.* FROM (
						  $sql
					) Z WHERE ROWNUM < $end
				) WHERE RNUM > $start ";
	}
	return $sql;
}



/**
 * 원격 데이타 가져오기
 */
function http_request($param, $opt='') {
	$ch = curl_init();
	if(isset($param['header'])) {
		curl_setopt($ch, CURLOPT_HEADER, $param['header']);
	}
	if (isset($param['httpheader']) && is_array($param['httpheader']) > 0) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $param['httpheader']);
	}
	curl_setopt($ch, CURLOPT_URL, $param['url']);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param['postfields']);
	if($opt=='DEL' || $opt=='DELETE') {
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$data = curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	$result = [
		'code' => $code,
		'data' => json_decode($data),
		'raw' => $data,
	];
	return $result;
}

/**
 * 이미지 구분
 */
function imgExpCheck($fileExt){
	return (!preg_match("/\.(gif|jpg|jpeg|png)$/i", $fileExt)) ? 0 : 1;
}

/**
 * 확장자 가져오기
 */
function getExt($filename)
{
	$fullName = explode(".",$filename);
	$fileExt = $fullName[sizeof($fullName)-1];
	return strtolower($fileExt);
}


/**
 * 경로, 원파일명, 다운 1/보임0, 다운속도, 속도제한여부
 */
function fileDown($file, $name, $downview, $speed, $limit)
{
	//do something on download abort/finish
	//register_shutdown_function( 'function_name'  );

	if (!file_exists($file)) {
		die('File not exist!');
	}

	if (is_file($file)===false) {
		Header("HTTP/1.0 500 Internal Server Error");
		die('Error 500');
	}

	$size = filesize($file);
	$name = rawurldecode($name);

	$downview = ($downview) ? "attachment" : "inline";
	$mime_type = mime_content_type($file);
	@ob_end_clean(); /// decrease cpu usage extreme
	Header("Content-Type: $mime_type");
	Header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	Header('Content-Disposition: '.$downview.'; filename="'.iconv("UTF-8", "EUC-KR", $name).'"');
	Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	Header("Accept-Ranges: bytes");
	Header("Pragma: private");

	///  multipart-download and resume-download
	if (isset($_SERVER['HTTP_RANGE'])) {
		if (preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches)) {
			$begin  = intval($matches[1]);
			if (!empty($matches[2])) {
				$end  = intval($matches[2]);
			}
			$new_length = $end - $begin + 1;
			$contentRange = $begin-$end/$size;
		} else {
			list($a, $range) = explode("=", $_SERVER['HTTP_RANGE']);
			str_replace($range, "-", $range);
			$size2 = $size-1;
			$new_length = $size-$range;
			$contentRange = $range.$size2/$size;
		}
		Header("HTTP/1.1 206 Partial Content");
		Header("Content-Length: $new_length");
		header("Content-Range: bytes $contentRange");
	} else {
		$size2=$size-1;
		header("HTTP/1.1 200 OK");
		Header("Content-Length: ".$size);
	}

	$chunksize = 1*(1024*$speed);
	$bytes_send = 0;
	if ($file = fopen($file, 'rb')) {
		if (isset($_SERVER['HTTP_RANGE'])) {
			fseek($file, $range);
		}

		while (!feof($file) and (connection_status()==0)) {
			$buffer = fread($file, $chunksize);
			print($buffer);//echo($buffer); // is also possible
			flush();
			$bytes_send += strlen($buffer);
			if ($limit) {
				sleep(1);
			} // 다운로드 속도제한
		}
		fclose($file);
	} else {
		die('Error can not open file!!');
	}
	if (isset($new_length)) {
		$size = $new_length;
	}
	die('Error can not open file!!');
	Header("Connection: close");
}

/**
 * 문자 자르기
 */
function str_cut($str, $len, $checkmb=false, $tail='..') {
	preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match);

	$m    = $match[0];
	$slen = strlen($str);  // length of source string
	$tlen = strlen($tail); // length of tail string
	$mlen = count($m); // length of matched characters

	if ($slen <= $len) return $str;
	if (!$checkmb && $mlen <= $len) return $str;

	$ret   = array();
	$count = 0;

	for ($i=0; $i < $len; $i++) {
		$count += ($checkmb && strlen($m[$i]) > 1)?2:1;
		if ($count + $tlen > $len) break;
		$ret[] = $m[$i];
	}

	return join('', $ret).$tail;
}

/**
 * key value converter
 */
function multiple(&$file_post) {

	$file_ary = [];
	if(isset($file_post['name'])) {
		$file_count = count($file_post['name']);
		$file_keys = array_keys($file_post);

		if($file_count > 0) {
			foreach($file_post['name'] as $k=>$v) {
				foreach ($file_keys as $key) {
					$file_ary[$k][$key] = $file_post[$key][$k];
				}
			}
		}
	} else {
		$file_ary = $file_post;
	}
	return $file_ary;
}


/**
 * radio button 가져오기
 */
function fn_radio_form($name, $arraytext, $checked=false, $attr="", $idstart=0, $addValue='', $txtValue='')
{
	$text = '';
	$i=0;
	foreach( $arraytext as $key => $value){
		if(isset($key) && $key!==false) {
			$text .= '<label>';
			$text .= '<input type="radio" name="'.$name.'" id="'.str_replace('[]','',$name).$idstart.$i.'" '.$attr.' value="'.$key.$addValue.'" ';
			$value1 = $value;
			if ($checked == $key || ($checked!='' && $key!='' && strpos(','.$checked,$key ) > 0) ) {
				$text .= 'checked="checked" data="1"';
				if ($txtValue!='') $value1=$txtValue;
			} elseif (trim($checked) === trim($key)) {
				$text .= 'checked="checked" data="1"';
				if ($txtValue!='') $value1=$txtValue;
			} else {
				$text .= 'data="0"';
			}
			$text .= ' title="'.$value.'선택" /> '.$value1.' </label>' . "\n";
		}
		$i++;
	}
	return $text;
}


/**
 * checkbox input 가져오기
 */
function fn_checkbox_form($name, $arraytext, $checked=false, $attr="", $idstart=0, $addValue='', $txtValue='', $labelClass='')
{
	$i=0;
	$text = "";
	foreach( $arraytext as $key => $value){
		if($key) {
			$text .= ($labelClass) ? '<label class="'.$labelClass.'">' : '<label>';
			$text .= '<input type="checkbox" name="'.$name.'" id="'.str_replace('[]','',$name).$idstart.$i.'" '.$attr.' value="'.$key.$addValue.'" ';
			$value1 = $value;
			$cnt = count($checked);
			if($cnt > 0 && is_array($checked)) {
				foreach($checked as $k => $v) {
					if ($v === $key) {
						$text .= 'checked="checked" ';
						if ($txtValue!='') $value1=$txtValue;
					} else {
						$text .= ' ';
					}
				}
			} else {
				if ($checked === $key) {
					$text .= 'checked="checked" ';
					if ($txtValue!='') $value1=$txtValue;
				} elseif ($checked == $key || strpos(','.$checked,','.$key ) > 0 ) {
					$text .= 'checked="checked" ';
					if ($txtValue!='') $value1=$txtValue;
				} else {
					$text .= ' ';
				}
			}
			$text .= ' title="'.$value.'선택" /> '.$value1.' </label>' . "\n";
		}
		$i++;
	}
	return $text;
}

/**
 * select Box 가져오기
 */
function fn_select_form($name, $arraytext, $value = null, $size = 1, $blankName = '', $attr = '', $idstart='')
{
	$str = '';
	$str .= '   <select name="' . $name . '" id="'.str_replace('[]','',$name).$idstart.'"';
	if ($size) {
		$str .= ' size="' . $size . '"';
	}
	$str .= ' ' . $attr . ' >'."\n";
	if ($blankName) {
		$str .= ' <option value="">' . $blankName . '</option>' . "\n";
	}

	if (is_array($arraytext)) {
		foreach ($arraytext as $val => $text) {
			$str .= ' <option ';
			if (!is_null($value)) {
				if ($value === $val) {
					$str .= 'selected="selected" ';
				} elseif ($value == $val) {
					$str .= 'selected="selected" ';
				}
			}
			$str .= 'value="' . $val . '">' . $text . "</option>\n";
		}
	}
	$str .= "   </select>\n";
	return $str;
}


