#!/usr/bin/php

<?php
   

	#===================================================================
	# Wersja z wywolaniami zblizonymi do C
	#===================================================================
	
	# zmienne predefiniowane -------------------------------------------
	$host = "127.1.0.0";
	$port = 12345;
	
	# tworzymy gniazdo -------------------------------------------------
	if( ! ( $server = socket_create( AF_INET, SOCK_STREAM, SOL_TCP ) ) ){
		print "socket_create(): " 		. socket_strerror( socket_last_error( $server ) ) . "\n";
		exit( 1 );
	}
	
	# ustawiamy opcje gniazda (REUSEADDR) ------------------------------
	if( ! socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1) ) {
		print "socket_set_option(): " 	. socket_strerror(socket_last_error( $server ) ) . "\n";
		exit( 1 );
	}
	
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
		
		# wyswietlamy informacje o polaczeniu  - - - - - - - - - - - - -
		socket_getpeername( $client, $addr, $port );
		print "Addres: $addr Port: $port\n";
		print socket_read($port);
		
		# przekazujemy informacje o biezacym czasie  - - - - - - - - - -
		$msg = "Current time: " . time();
		socket_write( $client, $msg, strlen( $msg ) );
		socket_close( $client );
	}
	#-------------------------------------------------------------------
	socket_close( $server );
	#===================================================================
?>
