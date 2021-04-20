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
	$dane=null;
	# obslugujemy kolejnych klientow, jak tylko sie podlacza -----------
	do
		{
		$pid = pcntl_fork();
		if ($pid == -1) {
			die('could not fork');
		} else if ($pid) {	
			print ("\n");
			$dane = stream_socket_recvfrom($server, 1500, STREAM_PEEK);
			
		} else 
		{
			
			

				$file=  "";
				$doZapisu="";
				for($i=0; $i<8; $i=$i+2)
				{
					$file = $file . strval($dane[$i]);	
				}
				$file = (string)$file;
				$file .= ".txt";
				for($i=8; $i<strlen($dane); $i++)
				{
					$doZapisu .= $dane[$i];
				}
				print ("Nazwa pliku =" . $file);
				print("Plik do zapisania = " . $doZapisu);
	
				$fp=fopen($file,"a");
				flock($fp,2);
				fwrite($fp,bin2hex($doZapisu));
				flock($fp,3);
				fclose($fp);
			
			
			
		}
		
	}while( $client = stream_socket_recvfrom( $server,1,0,$str  ));

	# przekazujemy informacje o obecnym czasie - - - - - - - - - - -
	fwrite( $client, "Current time: " . time() . "\n");
	fclose( $client );
	//}
	#-------------------------------------------------------------------
	fclose( $server );
	#===================================================================
?>
