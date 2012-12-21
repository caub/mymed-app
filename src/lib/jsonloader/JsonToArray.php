<?php

/**
 * @author teresko
 * https://github.com/fracture/fracture/blob/master/src/lib/fracture/transcription/jsontoarray.php
 *
 */

namespace lib\jsonloader;

class JsonToArray{

	static function fetchConfig( $filepath )
	{
		if ( !file_exists( $filepath ) )
		{
			throw new \Exception( "File '$filepath' not found!" );
		}

		$json = file_get_contents( $filepath );
		$data = json_decode( $json, TRUE );

		if ( ! is_array( $data ) )
		{
			throw new \Exception( "Not valid JSON from '$filepath' file!" );
		}

		return $data;
	}
	 
}


?>