<?php 
/*
VARIABLES API:

callback=json // Esta es obligatoria tal y como está
language=ES // El idioma en el que queremos buscar. Otros idiomas EN, FR, CA, IT, DE
word=cadena de texto a buscar // aquí escribimos la palabra exacta o la cadena de texto a buscar
catalog=colorpictos // bwpictos para pictogramas de Blanco y Negro
nresults=10 //Numero de resultado que quiero obtener como máximo
thumbnailsize=150 //Tamaño de la minuatura de la que quiero me genere URL
TXTlocate=1 // Tipo de búsqueda 1-Comienza por 2-Contiene 3-Termina por 4-Es igual a
KEY=xxxxxxxxxxxxxxxxx //Clave de la API

VARIABLES OPCIONALES:

tipo_palabra=2 // 1- Nombres propios 2-Nombres Comunes 3-Acciones 4-Descriptivos 5-Contenido Social 6-Miscelanea
*/
require ('../classes/querys/query.php');
require ('../configuration/key.inc');
require ('../classes/crypt/5CR.php');
$encript = new E5CR($llave);
$query=new query();

/***************************************************/
/*    CODIFICACION DIFERENTES IDIOMAS           */
/***************************************************/
require('../classes/utf8/utf8.class.php');
define("MAP_DIR","../classes/utf8/MAPPING");
define("CP1250",MAP_DIR . "/CP1250.MAP");
define("CP1251",MAP_DIR . "/CP1251.MAP");
define("CP1252",MAP_DIR . "/CP1252.MAP");
define("CP1253",MAP_DIR . "/CP1253.MAP");
define("CP1254",MAP_DIR . "/CP1254.MAP");
define("CP1255",MAP_DIR . "/CP1255.MAP");
define("CP1256",MAP_DIR . "/CP1256.MAP");
define("CP1257",MAP_DIR . "/CP1257.MAP");
define("CP1258",MAP_DIR . "/CP1258.MAP");
define("CP874", MAP_DIR . "/CP874.MAP");
define("CP932", MAP_DIR . "/CP932.MAP");
define("CP936", MAP_DIR . "/CP936.MAP");
define("CP949", MAP_DIR . "/CP949.MAP");
define("CP950", MAP_DIR . "/CP950.MAP");
define("GB2312", MAP_DIR . "/GB2312.MAP");
define("BIG5", MAP_DIR . "/BIG5.MAP");


$utfConverter = new utf8(CP1251); //defaults to CP1250.
$utfConverter->loadCharset(CP1256);

$utfConverter_ru = new utf8(CP1251); //defaults to CP1250.
$utfConverter_ru->loadCharset(CP1251);

$utfConverter_ch = new utf8(GB2312); 
$utfConverter_ch->loadCharset(GB2312);
/***************************************************/
/***************************************************/

/***************************************************/
$dominio=$_SERVER['SERVER_NAME'];

switch ($dominio) {
	
	case 'www.catedu.es':
	$url_dominio='www.catedu.es/arasaac';
	break;
	
	case 'catedu.es':
	$url_dominio='catedu.es/arasaac';
	break;
	
	case 'www.arasaac.org':
	$url_dominio='www.arasaac.org';
	break;
	
	case 'arasaac.org':
	$url_dominio='arasaac.org';
	break;
	
	case 'www.arasaac.net':
	$url_dominio='www.arasaac.net';
	break;
	
	case 'arasaac.net':
	$url_dominio='arasaac.net';
	break;
	
	case 'localhost':
	$url_dominio='localhost/saac';
	break;

}
/*callback=json&language=EN&word=house&catalog=colorpictos&nresults=10&thumbnailsize=150&TXTlocate=1&KEY=***REMOVED****/

if (isset($_GET['language'])) $idioma=$_GET['language'];
if (isset($_GET['word'])) $palabra_buscar=$_GET['word'];
if (isset($_GET['KEY'])) $key=$_GET['KEY'];

//************************************************************

$array_subcategorias='';
$array_subcategorias=array();

//************************************************************
//************************************************************
// CLAVE BORRADA - '***REMOVED***' , 	//Javier Marco
//**************************************************************
$valid_keys = array(
    '***REMOVED***' , 	//Comunicador CPA iOS
	'***REMOVED***' , 	//Comunicador CPA Android
	'***REMOVED***' , 	//ARABOARD
	'***REMOVED***' , 	//PictogramAgenda
	'***REMOVED***',		//PictogramAgenda iOS
    '***REMOVED***', 	//Vocaliza Online
    '***REMOVED***',  	//ARASAAC 
	'***REMOVED***',  	//Baluh 
	'***REMOVED***', 	//Barbara Zagajšek - Universidad de Zagreb 
	'***REMOVED***',		//Accegal
	'***REMOVED***',     //Elige
	'***REMOVED***',		//Tip-Tap-Talk
	'***REMOVED***', 	//PictoAplicaciones
	'***REMOVED***', 	//Leo con Lula
	'***REMOVED***', 	//Senteacher
);

//************************************************************
if (isset($key) && in_array($key,$valid_keys)) { //COMPRUEBO SI LA CLAVE DE LA APLICACION PARA LA API ES VALIDA
	if (isset($_GET['callback'])) {
		
		if (isset($_GET['callback']) && $_GET['callback']=='json') {
					
			if (!isset($_GET['TXTlocate'])) { //TXTlocate 1-Comienza por 2-Contiene 3-Termina por 4-Es igual a
				$txt_locate=4; //busqueda exacta
			} else { $txt_locate=$_GET['TXTlocate']; }
			
			if (!isset($_GET['tipo_palabra'])) { //1- Nombres propios 2-Nombres Comunes 3-Acciones 4-Descriptivos 5-Contenido Social 6-Miscelanea
				$id_tipo=99; //Busca todos los tipos de palabra
			} else { $id_tipo=$_GET['tipo_palabra']; }
			
			if (!isset($_GET['id_subtema'])) {
				$id_subtema=99999; 
				$busqueda_subtema="";
			} else { 
				$id_subtema=$_GET['id_subtema']; 
				$subtema=$query->datos_subtema($id_subtema);
			}
			
			if (!isset($_GET['word'])) {
				$letra=''; 
				$busqueda="";
			} else { 
				$letra=$_GET['word']; 
			}			
			
			if (!isset($_GET['filtrado'])) {
				$filtrado=1; 
			} else { $filtrado=$_GET['filtrado']; }	
			
			if (!isset($_GET['orden'])) {
				$orden="desc"; 
			} else { $orden=$_GET['orden']; }	
			
			if (!isset($_GET['thumbnailsize'])) {
				$thumbnailsize=50; 
			} else { $thumbnailsize=$_GET['thumbnailsize']; }
		
		
		}
		
	} else { 
		$id_tipo=99;
		$word="";
		$orden="desc";
		$filtrado="1";
		$id_subtema=99999;
		$busqueda="";
		$txt_locate=1;
	}
		
	if (!isset($_GET['pg'])) {
		$pg = 0; // $pg es la pagina actual
	} else { $pg=$_GET['pg']; }
						
		if (!isset($_GET['nresults'])) {
			$cantidad=1000;
		} else { $cantidad=$_GET['nresults']; }
		
		$inicial = $pg * $cantidad;
						
		$limite_inferior="5"; //resultados por debajo de la pagina actual
		$page_limit = $limite_inferior;
						
		$limitpages = $page_limit;
		$page_limit = $pg + $limitpages;
		
		switch ($_GET['catalog']) {
			case 'colorpictos':
			$tipo_pictograma=10; 
			break;
			
			case 'bwpictos':
			$tipo_pictograma=5; 
			break;
			
		}
		
		switch ($idioma) {
			case 'RU':
			$id_language=1;
			$letra=$utfConverter_ru->utf8ToStr($letra);
			break;
			
			case 'RO':
			$id_language=2; 
			break;
			
			case 'AR':
			$id_language=3;
			$letra=$utfConverter->utf8ToStr($letra);
			break;
			
			case 'ZH':
			$id_language=4;
			break;
			
			case 'BG':
			$id_language=5; 
			$letra=$utfConverter_ru->utf8ToStr($letra);
			break;
			
			case 'PL':
			$id_language=6; 
			break;
			
			case 'EN':
			$id_language=7; 
			break;
			
			case 'FR':
			$id_language=8;
			break;
			
			case 'CA':
			$id_language=9;
			break;
			
			case 'EU':
			$id_language=10;
			break;
			
			case 'DE':
			$id_language=11;
			break;
			
			case 'IT':
			$id_language=12;
			break;
			
			case 'PT':
			$id_language=13;
			break;
			
			case 'GA':
			$id_language=14;
			break;
	
			case 'BR':
			$id_language=15;
			break;
			
			case 'FI':
			$id_language=16;
			break;
			
			case 'TR':
			$id_language=17;
			break;
			
			case 'ES':
			$id_language=0;
			break;
			
		}
		
			
			if ($id_language > 0) {
			
				$contar=$query->listar_originales_idioma(false,$id_tipo,$letra,$filtrado,$orden,$id_subtema,$id_language,$tipo_pictograma,$txt_locate,$sql);
				$resultados=$query->listar_originales_idioma_limit(false,$inicial,$cantidad,$id_tipo,$letra,$filtrado,$orden,$id_subtema,$id_language,$tipo_pictograma,$txt_locate,$sql);
							
			} else {
				
				$contar=$query->listar_originales($_SESSION['AUTHORIZED'],$id_tipo,$letra,$filtrado,$orden,$id_subtema,$tipo_pictograma,$sql,$txt_locate);
				$resultados=$query->listar_originales_limit($_SESSION['AUTHORIZED'],$inicial,$cantidad,$id_tipo,$letra,$filtrado,$orden,$id_subtema,$tipo_pictograma,$sql,$txt_locate);
				
			}
						
		$total_records = $contar;
		$total_pages = intval($total_records / $cantidad);
		$n_resultados_por_pagina=mysql_num_rows($resultados);
			
			
			if ($total_records > 0 ) {
				
				echo '{
						"symbols": [';
								
					$f=0;
					
					while ($row=mysql_fetch_array($resultados)) {
					
						$f++;
						
						echo '{
								';
								
						$ruta='img=../../repositorio/originales/'.$row['imagen'].'&id_imagen='.$row['id_imagen'].'&id_palabra='.$row['id_palabra'].'&id_idioma='.$id_language;
						$encript->encriptar($ruta,1);
						
						$ruta_img='size='.$thumbnailsize.'&ruta=../../repositorio/originales/'.$row['imagen'];
						$encript->encriptar($ruta_img,1); //OJO uno(1) es para encriptar variables para URL
						
						echo '"imagePNGURL": "http://'.$url_dominio.'/repositorio/originales/'.$row['imagen'].'",';
																
						if ($id_language > 0) {
				  
				  			switch ($id_language) {
									
									case 1: //RUSO
									$word=$utfConverter_ru->strToUtf8($row['traduccion']);
									break;
									
									case 3: //ARABE
									$word=$utfConverter->strToUtf8($row['traduccion']);
									break;
									
									case 5: //BULGARO
									$word=$utfConverter_ru->strToUtf8($row['traduccion']);
									break;
									
									default:
									$word=$row['traduccion'];
									break;
									
								}
								
							$id_tipo_palabra= $row['id_tipo_palabra'];
							
							echo '"name": "'.$word.'", "wordTYPE": "'.$id_tipo_palabra.'",';
							echo '"CreationDate": "'.$row['fecha_creacion'].'", "ModificationDate": "'.$row['ultima_modificacion'].'",';
							
							if (file_exists('../repositorio/locuciones/'.$id_language.'/'.$row['id_traduccion'].'.mp3')) {
								echo '"soundMP3URL": "http://'.$url_dominio.'/repositorio/locuciones/'.$id_language.'/'.$row['id_traduccion'].'.mp3",';
							}
											  
						} else {
											
							$word=utf8_encode($row['palabra']);
							$id_tipo_palabra= $row['id_tipo_palabra'];
							
							echo '"name": "'.$word.'", "wordTYPE": "'.$id_tipo_palabra.'",';
							
							echo '"CreationDate": "'.$row['fecha_creacion'].'", "ModificationDate": "'.$row['ultima_modificacion'].'",';
							
							if (file_exists('../repositorio/locuciones/'.$id_language.'/'.$row['id_palabra'].'.mp3')) {
								echo '"soundMP3URL": "http://'.$url_dominio.'/repositorio/locuciones/'.$id_language.'/'.$row['id_palabra'].'.mp3",';
							}
					  
						}
						
						
						echo '"thumbnailURL": "http://'.$url_dominio.'/classes/img/thumbnail.php?i='.$ruta_img.'"
						';
						
							if ($f==$n_resultados_por_pagina) {
								echo '}
								';
							} else {
								echo '},
								';
							}
						
						
					} //Cierro el While
				} else { //Si no hay resultados....
					echo '{
						"symbols": [';
				}
				
				 echo ' ], 
						"itemCount": '.$cantidad.', 
						"page": '.$pg.', 
						"totalItemCount": '.$total_records.', 
						"pageCount": '.$total_pages.'
				}';

//****************************************************************
// BORRADO
//     case 'xYmKZrxuf1bcR9g2FS9x': 	//Javier Marco
//	$archivo = "javiermarco.txt";
//	break;
//****************************************************************

switch ($key) {
	case '***REMOVED***': 	//Comunicador CPA iOS
	$archivo = "cpa.txt";
	break;
	case '***REMOVED***': 	//Comunicador CPA Android
	$archivo = "cpa_android.txt";
	break;	
    case '***REMOVED***': 	//AraBoard
	$archivo = "araboard.txt";
	break;
	
	case '***REMOVED***':  	//PictogramAgenda
	$archivo = "pictogramagenda.txt";
	break;
	
	case '***REMOVED***':		//PictogramAgenda iOS
	$archivo = "pictogramagenda_ios.txt";
	break;
	
    case '***REMOVED***': 	//Vocaliza Online
	$archivo = "vovaliza.txt";
	break;
    case '***REMOVED***':  	//ARASAAC 
	$archivo = "arasaac.txt";	
	break;
	case '***REMOVED***':  	//Baluh
	$archivo = "baluh.txt";
	break;
	
	case '***REMOVED***': 	//Barbara Zagajšek - Universidad de Zagreb 
	$archivo = "universidad_zagreb.txt";
	break;
	
	case '***REMOVED***':  	//Accegal
	$archivo = "accegal.txt";
	break;
	
	case '***REMOVED***':      //Elige
	$archivo = "elige.txt";
	break;
	
	case '***REMOVED***':		//Tip-Tap-Talk
	$archivo = "tip_tap_talk.txt";
	break;
	
	case '***REMOVED***':		//PictoAplicaciones
	$archivo = "pictoaplicaciones.txt";
	break;
	
	case '***REMOVED***':    	//Leo con Lula
	$archivo = "leoconlula.txt";
	break;
	
	case '***REMOVED***':	//Senteacher
	$archivo = "senteacher.txt";
	break;
}


	// Abrimos el archivo para solamente leerlo (r de read)
	$abre = fopen($archivo, "r");
	
	// Leemos el contenido del archivo
	$total = fread($abre, filesize($archivo));
	
	// Cerramos la conexión al archivo
	fclose($abre);
	
	// Abrimos nuevamente el archivo
	$abre = fopen($archivo, "w");
	
	// Sumamos 1 nueva visita
	$total = $total + 1;
	
	// Y reemplazamos por la nueva cantidad de visitas 
	$grabar = fwrite($abre, $total);
	
	// Cerramos la conexión al archivo
	fclose($abre);

} //CIERRO LA COMPROBACION DE LA CLAVE VÁLIDA
?>