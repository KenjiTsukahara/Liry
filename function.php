<?php
	
	class LiryFunc
	{
		//contents.html
		function getTime($create_time) {
	    
			date_default_timezone_set('Asia/Tokyo');
		    $unix   = strtotime($create_time);
		    $now    = time();
		    $diff_sec   = $now - $unix;
		
		    if($diff_sec < 60){
		        $time2   = $diff_sec;
		        $time2 = floor($time2);
		        $unit   = "秒前";
		        $time = $time2.$unit;
		    }
		    elseif($diff_sec < 3600){
		        $time2   = $diff_sec/60;
		        $time2 = floor($time2);
		        $unit   = "分前";
		        $time = $time2.$unit;
		    }
		    elseif($diff_sec < 86400){
		        $time2   = $diff_sec/3600;
		        $time2 = floor($time2);
		        $unit   = "時間前";
		        $time = $time2.$unit;
		    }else{
		        $time2   = $diff_sec/86400;
		        $time2 = floor($time2);
		        $unit   = "日前";
		        $time = $time2.$unit;
		
		    }
	    
			return $time;
	    	    
    	}
    	
    
	    //find_add.html
		function scrape_img_urls($html) {
		    static $regex;
		    static $callback;
		    if (!$regex) {
		        $regex =
		            '`https?+:(?://(?:(?:[-.0-9_a-z~]|%[0-9a-f][0-9a-f]' .
		            '|[!$&-,:;=])*+@)?+(?:\[(?:(?:[0-9a-f]{1,4}:){6}(?:' .
		            '[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:\d|[1-9]\d|1\d{2}|2' .
		            '[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25' .
		            '[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?' .
		            ':\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))|::(?:[0-9a-f' .
		            ']{1,4}:){5}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:\d|[1' .
		            '-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{' .
		            '2}|2[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\\' .
		            'd|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])' .
		            ')|(?:[0-9a-f]{1,4})?+::(?:[0-9a-f]{1,4}:){4}(?:[0-' .
		            '9a-f]{1,4}:[0-9a-f]{1,4}|(?:\d|[1-9]\d|1\d{2}|2[0-' .
		            '4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-' .
		            '5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?:\d' .
		            '|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))|(?:(?:[0-9a-f]{' .
		            '1,4}:)?+[0-9a-f]{1,4})?+::(?:[0-9a-f]{1,4}:){3}(?:' .
		            '[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:\d|[1-9]\d|1\d{2}|2' .
		            '[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25' .
		            '[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?' .
		            ':\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))|(?:(?:[0-9a-' .
		            'f]{1,4}:){0,2}[0-9a-f]{1,4})?+::(?:[0-9a-f]{1,4}:)' .
		            '{2}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:\d|[1-9]\d|1\\' .
		            'd{2}|2[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4' .
		            ']\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5' .
		            '])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))|(?:(?:' .
		            '[0-9a-f]{1,4}:){0,3}[0-9a-f]{1,4})?+::[0-9a-f]{1,4' .
		            '}:(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:\d|[1-9]\d|1\d' .
		            '{2}|2[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]' .
		            '\d|25[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]' .
		            ')\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))|(?:(?:[' .
		            '0-9a-f]{1,4}:){0,4}[0-9a-f]{1,4})?+::(?:[0-9a-f]{1' .
		            ',4}:[0-9a-f]{1,4}|(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25' .
		            '[0-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?' .
		            ':\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?:\d|[1-9]\\' .
		            'd|1\d{2}|2[0-4]\d|25[0-5]))|(?:(?:[0-9a-f]{1,4}:){' .
		            '0,5}[0-9a-f]{1,4})?+::[0-9a-f]{1,4}|(?:(?:[0-9a-f]' .
		            '{1,4}:){0,6}[0-9a-f]{1,4})?+::|v[0-9a-f]++\.[!$&-.' .
		            '0-;=_a-z~]++)\]|(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0' .
		            '-5])\.(?:\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?:\\' .
		            'd|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5])\.(?:\d|[1-9]\d|' .
		            '1\d{2}|2[0-4]\d|25[0-5])|(?:[-.0-9_a-z~]|%[0-9a-f]' .
		            '[0-9a-f]|[!$&-,;=])*+)(?::\d*+)?+(?:/(?:[-.0-9_a-z' .
		            '~]|%[0-9a-f][0-9a-f]|[!$&-,:;=@])*+)*+|/(?:(?:[-.0' .
		            '-9_a-z~]|%[0-9a-f][0-9a-f]|[!$&-,:;=@])++(?:/(?:[-' .
		            '.0-9_a-z~]|%[0-9a-f][0-9a-f]|[!$&-,:;=@])*+)*+)?+|' .
		            '(?:[-.0-9_a-z~]|%[0-9a-f][0-9a-f]|[!$&-,:;=@])++(?' .
		            ':/(?:[-.0-9_a-z~]|%[0-9a-f][0-9a-f]|[!$&-,:;=@])*+' .
		            ')*+)?+(?:\?+(?:[-.0-9_a-z~]|%[0-9a-f][0-9a-f]|[!$&' .
		            '-,/:;=?+@])*+)?+(?:#(?:[-.0-9_a-z~]|%[0-9a-f][0-9a' .
		            '-f]|[!$&-,/:;=?+@])*+)?+`i'
		        ;
		        $callback = function ($url) {
		            $p = parse_url($url);
		            if (!isset($p['path'])) {
		                return false;
		            }
		            return preg_match('/(?:(?<=\.jpg)|(?<=\.jpeg)|(?<=\.png))\z/i', $p['path']);
		        };
		    }
		    preg_match_all($regex, html_entity_decode($html, ENT_QUOTES, 'UTF-8'), $m);
		    return array_values(array_filter($m[0], $callback));
		}
		
		//find_add_insert
		function ConvertCurrency($country_code,$price){
			
			
		$usd = json_decode(file_get_contents('http://api.aoikujira.com/kawase/json/usd'));
		$gbp = json_decode(file_get_contents('http://api.aoikujira.com/kawase/json/gbp'));
		$eur = json_decode(file_get_contents('http://api.aoikujira.com/kawase/json/eur'));
		
		$arr_usd = (array)$usd;
		$arr_gbp = (array)$gbp;
		$arr_eur = (array)$eur;
	
		switch ($country_code) {
		  case 1:
		    $price_result = $price;
		    break;
		  case 2:
		    $price_result = $arr_usd['JPY'] * $price;
		    break;
		   case 3:
		    $price_result = $arr_gbp['JPY'] * $price;
		    break;
		  case 4:
		    $price_result = $arr_eur['JPY'] * $price;
		    break;
		}
		
		
		return $price_result;	
			
		}
			
	}
	

	$func = new LiryFunc();
	
	
?>