<?php
Class CP_Install {
	var $promt_query = "";
	function promt_query($query) {
		$pattern = "#(SELECT|CREATE|ALTER|DROP|UPDATE|INSERT)(.+?);#is"; 
		while( preg_match( $pattern, $query) ) {
			$query = preg_replace_callback( $pattern, array( $this, '_promt_query') , $query);
			$return_query[] .= $matches[1].$matches[2];
		}
	return $this->promt_query;
	}

	function _promt_query($matches=array()) {
		$this->promt_query[] .= $matches[1].$matches[2];
		return "";
	}
}
?>