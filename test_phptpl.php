<?php
/**
 * test phptpl
 */

require "phptpl.php" ;

// �ַ����滻����
$str_replace_array['#TITLE#'] = "test_phptpl" ;
$str_replace_array['#TABLE_HEADER#'] = "MY_TABLE_HEADER" ;
// ��ϸ�滻����
function detail_function( $in_str = null , $print_flag = false )
{
	if( $in_str == null )
		return null;
	
	$out_str = "" ;
	for( $i = 1 ; $i <= 3 ; $i++ )
	{
		$str_replace_array = array() ;
		$str_replace_array['#USER_ID#'] = "MY_TITLE_" . (string)$i ;
		$str_replace_array['#USER_NAME#'] = "MY_USER_NAME_" . (string)$i ;
		
		$out_str .= phptpl_str( $in_str , $str_replace_array , null , null , null , false ) ;	
	}
	
	if( $print_flag == true )
		print $out_str ;
	
	return $out_str;
}
$section_replace_array['#DETAILS#'] = "detail_function" ;
// �����������
$if_exist_array['#LOGIN#'] = true ;
// ִ��ģ�崦���������
phptpl_file( "test_phptpl.html" , $str_replace_array , null , null , $if_exist_array , $section_replace_array , true );
?>
