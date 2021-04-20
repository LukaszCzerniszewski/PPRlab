#!/usr/bin/php

<?php
	# zmienne predefiniowane -------------------------------------------
	$host = "127.1.0.0";
	$port = 12345;
	
	# tworzymy gniazdo -------------------------------------------------
	if( ! ( $server = stream_socket_server( "udp://$host:$port", $errno, $errstr, STREAM_SERVER_BIND ) ) ){
	  print "stream_socket_server(): $errstr\n";
	  exit( 1 );
	}
	echo $server;

	# obslugujemy kolejnych klientow, jak tylko sie podlacza -----------
	//while( 
		//$client = stream_socket_accept( $server );  -------- 
		// {
		# wyswietlamy informacje o klientach - - - - - - - - - - - - - -
		// $str = stream_socket_get_name( $client, 1 );
		// list( $addr, $port ) = explode( ':', $str );
	
		// print "Addres: $addr Port: $port\n";	
		print ("\n");
		$dane = stream_socket_recvfrom($server, 1500, STREAM_PEEK);
		print $dane;
			
			$file=  "";
			$doZapisu="";
			for($i=0; $i<8; $i=$i+2)
			{
			$file = $file . strval($dane[$i]);	
			}
			$file = (string)$file;
			$file .= ".txt";
			for($i=9; $i<strlen($dane); $i++)
			{
				$doZapisu .= $dane[$i];
			}
			print("Plik do zapisania = " . $doZapisu);

			$fp=fopen($file,"a");
			flock($fp,2);
			fwrite($fp,bin2hex($doZapisu));
			flock($fp,3);
			fclose($fp);

		# przekazujemy informacje o obecnym czasie - - - - - - - - - - -
		//fwrite( $client, "Current time: " . time() . "\n");
		//fclose( $client );
	//}
	#-------------------------------------------------------------------
	fclose( $server );
	#===================================================================
?>
