<?php
//archivo de conexio a la base de datos
require_once('../seguridad/c.php');
//nucleo del sistema con funciones vitales y procesamiento logico
require_once('../seguridad/dodo_core.php');
//inicio de sesion o recuperacion es caso de abierta


//----------------------------------------------------------------
//Declaracion de Variables por defecto
//----------------------------------------------------------------
set_time_limit(0);
$db_count 						= -1;
$ftp_count 						= -1;

//Datos MySql
$complemeto_nombre="servidor_principal_";

$db_count++;
$db[$db_count]['db_user'] 		= 'root';
$db[$db_count]['db_password'] 	= 'comu20xx';
$db[$db_count]['db_name'] 		= $database_seguro;
$db[$db_count]['sql_file'] 		= $complemeto_nombre."dump_".date('Y-m-d_H:i')."_{$db[$db_count]['db_name']}.sql";

//DATOS FTP 
$ftp_count++;
$ftp[$ftp_count]['ftps'] 				= false;
$ftp[$ftp_count]['ftp_server'] 			= "ftp.comunicamosmas.com";
$ftp[$ftp_count]['ftp_user'] 			= "sistemas@comunicamosmas.com";
$ftp[$ftp_count]['ftp_password'] 		= "Yopal13640632";
$ftp[$ftp_count]['ftp_passive_mode'] 	= true;
$ftp[$ftp_count]['ftp_remote_folder'] 	= "";	//e.g. /mysite/backups

foreach($db as $db_item)
{
	//Creando el archivo SQL y comprimiendolo para que pese menos
	exec("mysqldump -h 127.0.0.1 -u {$db_item['db_user']} -p{$db_item['db_password']} --allow-keywords --add-drop-table --complete-insert --hex-blob --quote-names {$db_item['db_name']} > {$db_item['sql_file']}");
	exec("gzip {$db_item['sql_file']}");

	//----------------------------------------------------------------
	// Transferencia FTP: Transfiriendo el archivo al servidor FTP
	//----------------------------------------------------------------

	if($ftp_count >= 0)
	{
		foreach($ftp as $ftp_item)
		{
			//Iniciando Conexion
			if($ftp_item['ftps'])
				$connection_id = ftp_ssl_connect($ftp_item['ftp_server']);
			else
				$connection_id = ftp_connect($ftp_item['ftp_server']);
			if(!$connection_id)
				echo "Error: Can't connect to {$ftp_item['ftp_server']}\n";
			//Autenticandose 
			$login_result = ftp_login($connection_id, $ftp_item['ftp_user'], $ftp_item['ftp_password']);
			if(!$login_result)
				echo "Error: Login wrong for {$ftp_item['ftp_server']}\n";
			//Modo Pasivo
			ftp_pasv($connection_id, $ftp_item['ftp_passive_mode']);
			//Subiendo Archivo
			if (!ftp_put($connection_id, $ftp_item['ftp_remote_folder']."/".$db_item['sql_file'].'.gz', $db_item['sql_file'].'.gz', FTP_BINARY))
			{
				echo "Error: While uploading {$db_item['sql_file']}.gz to {$ftp_item['ftp_server']}.\n";
			}
			//Cerrando Conexion
			ftp_close($connection_id);
		}
	}

	//Borrando el SQL original  
	if(file_exists($db_item['sql_file']))
		unlink($db_item['sql_file']);

	//Borrando el GZ generado de la compresion 
	if(file_exists($db_item['sql_file'].'.gz'))
		unlink($db_item['sql_file'].'.gz');

}
?>
