<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function p($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}
//弹窗函数
//$msg提醒的文本内容
//$url跳转的页面地址
function show_msg($msg,$url){
    echo '<script>alert("'.$msg.'");location.href="'.$url.'";</script>';
    exit();
}

/**
	 * 
	 * 字符截取
	 * @param string $string  需要截取的字符串
	 * @param int 	 $start	  截取的开始位置
	 * @param int 	 $length  截取的长度
	 * @param string $charset 编码
	 * @param string $dot	  如果截取的长度小于总长度就加这个字符
	 */
	function str_cut(&$string, $start, $length, $charset = "utf-8", $dot = '...') {
		if(function_exists('mb_substr')) {
			if(mb_strlen($string, $charset) > $length) {
				return mb_substr ($string, $start, $length, $charset) . $dot;
			}
			return mb_substr ($string, $start, $length, $charset);
		}else if(function_exists('iconv_substr')) {
			if(iconv_strlen($string, $charset) > $length) {
				return iconv_substr($string, $start, $length, $charset) . $dot;
			}
			return iconv_substr($string, $start, $length, $charset);
		}
		$charset = strtolower($charset);
		switch ($charset) {
			case "utf-8" :
				preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $ar);
				if(func_num_args() >= 3) {
					if (count($ar[0]) > $length) {
						return join("", array_slice($ar[0], $start, $length)) . $dot;
					}
					return join("", array_slice($ar[0], $start, $length));
				} else {
					return join("", array_slice($ar[0], $start));
				}
				break;
			default:
				$start = $start * 2;
				$length   = $length * 2;
				$strlen = strlen($string);
				for ( $i = 0; $i < $strlen; $i++ ) {
					if ( $i >= $start && $i < ( $start + $length ) ) {
						if ( ord(substr($string, $i, 1)) > 129 ) $tmpstr .= substr($string, $i, 2);
						else $tmpstr .= substr($string, $i, 1);
					}
					if ( ord(substr($string, $i, 1)) > 129 ) $i++;
				}
				if ( strlen($tmpstr) < $strlen ) $tmpstr .= $dot;
				
				return $tmpstr;
		}
	}