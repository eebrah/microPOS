<?php

include_once( "Base.class.php" );

const DIALOG_TYPE_INFO = "info";
const DIALOG_TYPE_ERROR = "error";
const DIALOG_TYPE_SUCCESS = "success";

function redirect($target = '') {
	
	$host = $_SERVER[ 'HTTP_HOST' ];
	$uri = rtrim( dirname( $_SERVER[ 'PHP_SELF' ] ), '/\\' );

	// If no headers are sent, send one
	if( !headers_sent() ) {
		header( "Location: http://" . $host . $uri . "/" . $target );
		exit;

	}	

}

function dialog($content = '', 
                $header = 'info', 
                $type = 'info', 
                $link = '#', 
                $button_links = Array()) {
	
	$returnString = '	
<div class="form dialog ' . $type . '" style="padding-bottom: 0;">
	<a class="dialog_close" href="' . $link . '"></a>
	<h3 style="margin-bottom: 0;">' . $header . '</h3>
	<div class="content">' . $content . '
	</div>';
	
	if( count( $button_links ) > 0 ) {
		
		$returnString .= '
	<div class="dialog_buttons" style="">';

		foreach( array_keys( $button_links ) as $key ) {
			
			$returnString .= '
		<a href="' . $button_links[ $key ] . '" class="button" style="; padding: 0.5em 2em;" >' . $key . '</a>';
	
		}
		
		$returnString .= '
	</div>';
	
	}
	
	$returnString .= '
</div>';

	return $returnString;

}


function genTable( $data, $classes = 'fancy' ) {
	
	$returnStr = '
<table class="' . $classes . '">
	<thead>
		<tr>
			<th>#</th>';
	
	$keys = array_keys( $data[ 0 ] );
	
	foreach( $keys as $key ) {
		
		$returnStr .= '<th>' . $key . '</th>';
	
	}
	
	$returnStr .= '
		</tr>
	</thead>
	<tbody>';
	
	$count = 1;
	
	foreach( $data as $row ) {
		
		$returnStr .= '
		<tr>
			<td>' . $count . '</td>';
			
		foreach( $keys as $key ) {
			
			$returnStr .= '
			<td>' . str_replace( '&', '&amp;', $row[ $key ] ) . '</td>';
		
		}
			
		$returnStr .= '
		</tr>';
		
		$count++;
		
	}
	
	$returnStr .= '
	</tbody>
</table>';

	return $returnStr;
	
}


?>
