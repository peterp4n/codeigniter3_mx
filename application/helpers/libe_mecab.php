<?php


/*
 * mecab php바인딩후 태그와 택스트로 분리하기
 *
 * @param string $str 문자열
 * @param object mecab mecab객체
 * @param array $code 걸러내고자 하는 코드값 NNG등
 * @return array $str_arr 배열로 리턴
 *
 *	$code= array('NNG');
 *	$str= '조상의산소에서잠이든꿈, 잠을잔꿈,잠자는꿈';
 *	$result = mecab_incoding($str, $code);
 *
 */
function mecab_incoding($str, $code = array()){

	$option = 2;
	switch($option) {
		case '1' :
		case '2' :
			$arg = [
				'-N' => 3,
				'-l' => 1,
			];
			if($option==2) {
				$arg[] = '-a';
			}
			break;
		case '3' :
			$arg = [
				'--node-format' => 'Node (%pi): %m' . PHP_EOL,
				'--bos-format' => 'BOS (%pi)' . PHP_EOL,
				'--eos-format' => 'EOS (%pi)' . PHP_EOL,
				'--unk-format' => 'Unknown (%pi): %m' . PHP_EOL,
			];
			break;
		case '4' :
			$arg = [
				'-O' => 'wakati'
			];
			break;
		default :
			$arg = [];
			break;
	}
	$mecab = new \MeCab\Tagger($arg);

	$str_arr = array();
	//형태소분석하여 결과값 도출

	$result = $mecab->parse($str);

	//결과값에서 줄단위로 분리
	preg_match_all('/[^EOS](.*)\n/', $result, $find_code);

	//각줄별로 루프를 돌며 텍스트와 태그(코드)값분리
	for($i=0; $i < count($find_code[0]); $i++) {
		preg_match('/(.*)(?=\t)/', $find_code[0][$i], $find_text); // text
		preg_match('/(?<=\t)([^\,]+)/', $find_code[0][$i], $find_tag); // tag
		//걸러내고자하는 코드가 있을시
		if(count($code) > 0) {
			//걸러내려는 코드안에 태그가 포함되는지
			if(in_array($find_tag[0],$code)
				//중복되는 텍스트가 있는지
				&& in_array($find_text[0],$str_arr) === false) {
				$str_arr[] = $find_text[0];
				// 태그값은 필요 없어 주석
				//$str_arr[$i]["code"] = $find_tag[0];
			}
		} else {
			//중복되는 텍스트가 있는지
			if(in_array($find_text[0],$str_arr) === false) {
				$str_arr[] = $find_text[0];
				//태그값은 필요 없어 주석
				//$str_arr[$i]["code"] = $find_tag[0];
			}
		}
	}
	return $str_arr;
}
