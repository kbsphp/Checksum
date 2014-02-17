<?
	/*
		Entrypoint into the new AppleInsider.
	*/$start_time = microtime( true );
	require( 'core/ai_core.php' );


	//Start execution time...
	$start_time = microtime( true );
	require( 'core/ai_core.php' );

	//Start up the framework.
	$appleinsider = ai_core::init( );
	$appleinsider->request->route( );
	
	if( !$ai_external ) {
		$appleinsider->request->controller->view->render( );
	} else {
		unset( $appleinsider );
		include( $ai_external_file );
	}
	
	//Calculate execution time.
	$end_time = microtime( true );
	$total_time = $end_time - $start_time;
	
	// Log slow loading pages to a file so we can see wtf is going on.
	if( AI_SLOW_LOG ) {
		if( $total_time >= AI_SLOW_LOG_INTERVAL ) {
			$filename = AI_LOG_PATH.'slow.log';
			$log_string = "{$_SERVER['REQUEST_TIME']}\t{$_SERVER['REQUEST_METHOD']}\t{$_SERVER['REQUEST_URI']}\t{$_SERVER['QUERY_STRING']}\t{$_SERVER['REMOTE_ADDR']}\t{$total_time}\n";			
			file_put_contents( $filename, $log_string, FILE_APPEND );
		}
	}
	
	if( AI_DEBUG && AI_DEBUG_VERBOSE ) {
		
		if( AI_NO_CACHE ) {
			echo '<br /> <strong>Cache is currently disabled</strong><br />';
		}

		if( $ai_external ) {
			echo '<br /> <b>Using External Directory Structure</b>';
		}

		if( !$ai_external ) {
			echo "<br /> Cache Sets: {$appleinsider->cache->sets} (failed: {$appleinsider->cache->failed_sets})";
			echo "<br /> Cache Gets: {$appleinsider->cache->gets} (failed: {$appleinsider->cache->failed_gets})";
		}
		
		echo '<br /> Memory usage: '.number_format((memory_get_peak_usage( )/1024), 2)."KB\n";
		echo '<br /> <br />Rendered in: '.$total_time.' Seconds <br /> <br />';
	}
	
	//echo 'Beta 2 is being disabled for WWDC, just in can case someone is trying to do bad things. Click here to view <a href="http://appleinsider.com">AppleInsider</a>';

?>