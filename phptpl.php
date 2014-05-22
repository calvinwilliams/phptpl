<?php
/**
 * phptpl - PHP Cute Template Engine
 * AUTHOR    : calvin(calvinwilliams.c@gmail.com)
 * COPYRIGHT : by calvin
 * LICENSE   : LGPL (http://www.gnu.org/licenses/lgpl.html)
 * VERSION   : v1.1.1 2014-02-16 create
 */

// PHPģ�����棬����ԴΪ�ַ���
function phptpl_str( $in_str = null , $str_replace_array = null , $ereg_replace_array = null , $preg_replace_array = null , $if_exist_array = null , $section_replace_array = null , $print_flag = false )
{
	if( $in_str == null )
		return null;
	
	$out_str = $in_str ;
	
	// �����ļ�����
	$find_offset = 0 ;
	$begin_str = "<!-- INCLUDE " ;
	$end_str = " -->" ;
	while(1)
	{
		$begin_pos = strpos( $out_str , $begin_str , $find_offset ) ;
		$end_pos = strpos( $out_str , $end_str , $find_offset + $begin_pos + strlen($begin_str) ) ;
		if( $begin_pos === false || $end_pos === false )
		{
			break;
		}
		
		$segment_str1 = substr( $out_str , 0 , $begin_pos ) ;
		$segment_str2 = substr( $out_str , $begin_pos + strlen($begin_str) , $end_pos - $begin_pos - strlen($begin_str) ) ;
		$segment_str3 = substr( $out_str , $end_pos + strlen($end_str) ) ;
		
		$out_str = $segment_str1 . file_get_contents($segment_str2) . $segment_str3 ;
		
		$find_offset = $begin_pos + 1 ;
	}
	
	// �����������
	if( $if_exist_array != null )
	{
		$find_offset = 0 ;
		
		foreach( $if_exist_array as $key => $value )
		{
			$begin_str = "<!-- IF " . $key . " -->" ;
			$middle_str = "<!-- ELSE " . $key . " -->" ;
			$end_str = "<!-- ENDIF " . $key . " -->" ;
			while(1)
			{
				$begin_pos = strpos( $out_str , $begin_str , $find_offset ) ;
				$middle_pos = strpos( $out_str , $middle_str , $find_offset ) ;
				$end_pos = strpos( $out_str , $end_str , $find_offset ) ;
				if( $begin_pos === false || $end_pos === false )
				{
					break;
				}
				
				if( $middle_pos === false )
				{
					if( $value == false )
					{
						$segment_str1 = substr( $out_str , 0 , $begin_pos ) ;
						$segment_str3 = substr( $out_str , $end_pos + strlen($end_str) ) ;
						$out_str = $segment_str1 . $segment_str3 ;
					}
				}
				else
				{
					if( $value == true )
					{
						$segment_str1 = substr( $out_str , 0 , $middle_pos ) ;
						$segment_str3 = substr( $out_str , $end_pos + strlen($end_str) ) ;
						$out_str = $segment_str1 . $segment_str3 ;
					}
					else
					{
						$segment_str1 = substr( $out_str , 0 , $begin_pos ) ;
						$segment_str3 = substr( $out_str , $middle_pos + strlen($middle_str) ) ;
						$out_str = $segment_str1 . $segment_str3 ;
					}
				}
				
				$find_offset = $begin_pos + 1 ;
			}
		}
	}
	
	// ������ϸ
	if( $section_replace_array != null )
	{
		$find_offset = 0 ;
		
		foreach( $section_replace_array as $key => $value )
		{
			$begin_str = "<!-- BEGIN " . $key . " SECTION -->" ;
			$end_str = "<!-- END " . $key . " SECTION -->" ;
			while(1)
			{
				$begin_pos = strpos( $out_str , $begin_str , $find_offset ) ;
				$end_pos = strpos( $out_str , $end_str , $find_offset ) ;
				if( $begin_pos === false || $end_pos === false )
				{
					break;
				}
				
				$segment_str1 = substr( $out_str , 0 , $begin_pos ) ;
				$segment_str2 = substr( $out_str , $begin_pos + strlen($begin_str) , $end_pos - $begin_pos - strlen($begin_str) ) ;
				$segment_str3 = substr( $out_str , $end_pos + strlen($end_str) ) ;
				
				$segment_str2 = $section_replace_array[$key]( $segment_str2 , false ) ;
				$out_str = $segment_str1 . $segment_str2 . $segment_str3 ;
				
				$find_offset = $begin_pos + 1 ;
			}
		}
	}
	
	// �����ַ����滻
	if( $str_replace_array != null )
	{
		$replace_from_array = array() ;
		$replace_to_array = array() ;
		
		foreach( $str_replace_array as $key => $value )
		{
			$value = $str_replace_array[$key] ;
			
			$replace_from_array[] = $key ;
			$replace_to_array[] = $value ;
		}
		
		$out_str = str_replace( $replace_from_array , $replace_to_array , $out_str ) ;
	}
	
	// �����ַ���PCRE�����滻
	if( $ereg_replace_array != null )
	{
		$replace_from_array = array() ;
		$replace_to_array = array() ;
		
		foreach( $ereg_replace_array as $key => $value )
		{
			$value = $ereg_replace_array[$key] ;
			
			$replace_from_array[] = $key ;
			$replace_to_array[] = $value ;
		}
		
		$out_str = ereg_replace( $replace_from_array , $replace_to_array , $out_str ) ;
	}
	
	// �����ַ���POSIX�����滻
	if( $preg_replace_array != null )
	{
		$replace_from_array = array() ;
		$replace_to_array = array() ;
		
		foreach( $preg_replace_array as $key => $value )
		{
			$value = $preg_replace_array[$key] ;
			
			$replace_from_array[] = $key ;
			$replace_to_array[] = $value ;
		}
		
		$out_str = preg_replace( $replace_from_array , $replace_to_array , $out_str ) ;
	}
	
	/* �������������־ */
	if( $print_flag == true )
		print $out_str;
	
	return $out_str;
}

// PHPģ�����棬����ԴΪģ���ļ���
function phptpl_file( $in_pathfilename = null , $str_replace_array = null , $ereg_replace_array = null , $preg_replace_array = null , $if_exist_array = null , $section_replace_array = null , $print_flag = false )
{
	if( $in_pathfilename == null )
		return null;
	
	$out_str = file_get_contents($in_pathfilename) ;
	
	return phptpl_str( $out_str , $str_replace_array , $ereg_replace_array , $preg_replace_array , $if_exist_array , $section_replace_array , $print_flag );
}

// ׷���ַ������鵽ģ���������
function append_array_to_phptpl_array( $prefix , $source_array , $postfix , & $replace_array )
{
	foreach( $source_array as $key => $value )
	{
		$replace_array[$prefix.$key.$postfix] = $value ;
	}
}

?>
