#!/usr/bin/php

<?php
   

	#===================================================================
	# Wersja z wywolaniami zblizonymi do C
	#===================================================================
	
	# zmienne predefiniowane -------------------------------------------
	$host = "127.1.0.0";
	$port = 12345;
	
	# tworzymy gniazdo -------------------------------------------------
	if( ! ( $server = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP) ) ){
		print "socket_create(): " 		. socket_strerror( socket_last_error( $server ) ) . "\n";
		exit( 1 );
	}
	
	// # ustawiamy opcje gniazda (REUSEADDR) ------------------------------
	// if( ! socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1) ) {
	// 	print "socket_set_option(): " 	. socket_strerror(socket_last_error( $server ) ) . "\n";
	// 	exit( 1 );
	// }
	
	# mapujemy gniazdo (na port) ---------------------------------------
	if( ! socket_bind( $server, $host, $port ) ){
		print "socket_bind(): " 		. socket_strerror( socket_last_error( $server ) ) . "\n";
		exit( 1 );
	}
	
	# ustawiamy gniazdo w tryb nasluchiwania ---------------------------
	if( ! socket_listen( $server, 5 ) ){
		print "socket_listen(): " 		. socket_strerror( socket_last_error( $server ) ) . "\n";
		exit( 1 );
	}
	
	# obslugujemy kolejnych klientow, jak tylko sie podlacza -----------
	while( $client = socket_accept( $server ) ){
		$pid = pcntl_fork();
		if ($pid == -1) {
			die('could not fork');
	   } else if ($pid) {
			# wyswietlamy informacje o polaczeniu  - - - - - - - - - - - - -
			socket_getpeername( $client, $addr, $port );
			print "Addres: $addr Port: $port\n";
			pcntl_wait($status); //Protect against Zombie children
	   } else {
			
			$dane= socket_read($client,255);
			#$file = substr($dane,0,8);
			#$file = $file . ".txt";
			#print($file);
			
			#print(bin2hex());
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
	   }
		
		
		
		
		# przekazujemy informacje o biezacym czasie  - - - - - - - - - -
		$msg = "Current time: " . time();
		socket_write( $client, $msg, strlen( $msg ) );
		socket_close( $client );
		
	}
	#-------------------------------------------------------------------
	socket_close( $server );
	#===================================================================
?>
