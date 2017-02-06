<?php

include('config.php');

// Connect to database

$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

//mysqli_query("SET GLOBAL time_zone = 'Europe/Madrid'; ");


// Check the connection

if (mysqli_connect_errno()) {
    printf("Connection error: %s\n", mysqli_connect_error());
    exit();
}


// General Functions ///////////////////////////////////////////////////////////////////////////////

function send($msj) {

	$queryArray = [

	'chat_id' => CHAT_ID,

	'text' => $msj,

	];

	$url = 'https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?'.http_build_query($queryArray);
	$result = file_get_contents($url);

var_export($result);
}

function getMinutes($fecha1, $fecha2)
{
    $fecha1 = strtotime($fecha1);
    $fecha2 = strtotime($fecha2);
    return round(($fecha2 - $fecha1) / 60);
}

function check_server( $host ){

			$ch = curl_init( $host );

			curl_setopt($ch, CURLOPT_NOBODY, true);

			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);

			curl_setopt($ch, CURLOPT_TIMEOUT, 120);

			curl_exec($ch);

			$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			$start = curl_getinfo($ch, CURLINFO_TOTAL_TIME);

			curl_close($ch);
	
			$status->retcode = $retcode;
			$status->start = $start;
            $status->url = $host;

	return($status);

}

function check_valid( $server ){
    
    global $DEBUG;
    
    $days = preg_split('/,/', $server->day, -1, PREG_SPLIT_NO_EMPTY);

    $hours = preg_split('/,/', $server->hour, -1, PREG_SPLIT_NO_EMPTY);

    $minutes = preg_split('/,/', $server->minute, -1, PREG_SPLIT_NO_EMPTY);
    
    $log = array();
      
    $valid = true;

	if (!empty($hours) && $valid != false) {

		foreach ($hours as $hour) {

			if (strlen($hour) == 1 && $hour != "*") {

				$hour = "0" . $hour;

			}

			if ($hour == date('H') || $hour == "*") {

				$valid = true;

            } else {

				$valid = false;

			}

		}

	}
    
    if($DEBUG) {
        
        if($valid){ echo "\nBUENA HORA\n"; } else { echo "\nMALA HORA\n"; }
        
    }

	if (!empty($minutes) && $valid != false) {

		foreach ($minutes as $minute) {

			if (strlen($minute) == 1 && $minute != "*") {

				$minute = "0" . $minute;

			}

			if ($minute == date('i') || $minute == "*") {

				$valid = true;


			} else {

				$valid = false;

			}

		}

	}
    
    if($DEBUG) {
       if($valid){ echo "\nBUEN MINUTO\n"; } else { echo "\nMAL MINUTO\n"; }
    }

	return ($valid);

	// end

}

// RAMON START:


$msg = array();

if ( date('H:i')=='09:00'){

	array_push($msg, "Buenos dias chicos!\n");

}

if ( date('H:i')=='20:00'){

	array_push($msg, "Buenas noches chicos!\n");			

}


//Recuperamos todos los servidores desde la base de datos

$sql = "SELECT
bot.id,
bot.name,
bot.active,
bot.url_active,
bot.url,
bot.sql_active,
bot.host,
bot.user,
bot.password,
bot.database,
bot.last_time_seen_online,
bot_conf.day,
bot_conf.hour,
bot_conf.minute,
bot_conf.last_execution,
bot_conf.id AS job_id
FROM
bot
INNER JOIN bot_conf ON bot.id = bot_conf.bot_id";

if ($result = $mysqli->query($sql)) { 
	
    while($server = $result->fetch_object()){ 
 
		$active = 0;

		$fast = 1;

		$valid = false;

		$valid = check_valid( $server );


		if ($valid) {  //Si es válido, entonces ejecutamos

			$status = check_server( $server->url );
            
            if($DEBUG) { var_export($status); }
            		
			if (200 == $status->retcode) {

				$active = 1;
                echo "IS ONLINE\n\n";

			} else {

				$active = 0;
                echo "IS OFFLINE\n\n";


			}
                                    
            $msg_txt = "";
                        
            if($server->url_active==1){
                
                

                if($active == 1){
                    

                } else {

                    $msg_txt .= "\xF0\x9F\x86\x98 ".$server->name."";

                }


            } else { 
                
               

                if($active == 1){
                                    
                    $fecha2 = date('Y-m-d H:i:s');
                    $fecha1 = $server->last_time_seen_online ;
                    $tiempo = getMinutes( $fecha1, $fecha2);

                    $msg_txt .="\xE2\x9C\x85 ".$server->name." : ".$tiempo." min offline";
                    
                    $tiempo = "";

                } else {
                    

                }

            }
            
            
            if ($active == 1) {

				if ($start > 30) {

					$fast = 0;
                   
                    $msg_txt = "Funciona pero está tardando " . round($status->start) . " segundos en cargar ";

				}

			} 
            
            
            if($msg_txt!=""){
                
			       array_push($msg, $msg_txt );
                   echo "MSG: ".$msg_txt;
                
            }
                                  
            
        
           if($active==1){ $extra_query=",last_time_seen_online=NOW() ";  } 
            
                $sql = "UPDATE bot SET bot.url_active='$active'$extra_query WHERE bot.id='$server->id'";

                $mysqli->query($sql); 

                echo $mysqli->error;
		
                $sql = "UPDATE bot_conf SET bot_conf.last_execution=NOW() WHERE bot_conf.id='$server->job_id'";

                $mysqli->query($sql); 

                echo $mysqli->error;
            
        } else {
            
            echo "SKIPPING\n\n";
            
        }
	
	
                	
	}

}  else { 
	
	if($DEBUG){	
    	echo $mysqli->error;
        echo $sql;
    }
}

$result->close(); 
    

if(count($msg)>=1){   // comprobamos si hay mensajes para notificar
			
	$message_complete = "";

	foreach ($msg as $message) {   // montamos lineas del mensaje

		$message_complete .= $message."\n"; // linea a linea

	}
		
	send($message_complete );//".date('d/m/y H:i')
			
	echo "\n\n<----------------".$message_complete."\n\n";
}
?>
