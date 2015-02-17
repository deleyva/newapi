<?php 
/* VARIABLES API:
**************************************************************************************************
callback=json // Esta es obligatoria tal y como está
busqueda=basico
buscador=1
titulo_descripcion_basico=titulo // aquí escribimos la palabra exacta o la cadena de texto a buscar en el título o descripción
autor_basico=autor // aquí escribimos el autor a buscar
idiomas_basico=en // El idioma en el que están los materiales: es, en, fr, ca, it, de

**************************************************************************************************
area_curricular_basico=xxx 

1-Lengua y Literatura  
2-Matemáticas 
4-Conocimiento del Medio Natural y Social 
6-Educación Artística 
7-Conocimiento si mismo y autonomía personal 
8-Taller 
9-E.Física 
10-Religión
**************************************************************************************************

**************************************************************************************************
subarea_curricular_basico=xxx 
//Para el Área 1 Lengua y Literatura
13-Habilidades Prelingüísticas
14-Fonética-Fonología
15-Semántica
16-Morfosintaxis
17-Pragmática
18-Lectura y Escritura
//Para el Área Curricular 6 Educación artística
19-Música
20-Plástica
**************************************************************************************************

**************************************************************************************************
tipo_basico=xxx

1	Cuento
2	Aplicación Informática
3	Material Audiovisual
4	Presentación
5	Juego individual
6	Juego colectivo
20	Canción
19	Cuaderno
12	Protocolo
13	Test de evaluación
14	Animación
15	Ficha
16	Tablero
18	Libro
21	Pizarra Digital Interactiva (P
22	Tablero TICO
23	Smart Notebook
24	Actividad LIM
25	Señaléctica
26	Rutinas
27	Secuencias
28	JClic
29	Actividad PICAA
30	PictodroidLite
31	AraBoard

**************************************************************************************************

**************************************************************************************************
dirigido_basico=xxx 

1-TGD 
2-Deficiencia Visual 
3-Definciencia Motórica 
4-Deficiencia Auditiva 
5-Discapacidad Psíquica 
6-Plurideficientes 
9-Deficiencia producción del habla 
10-Acnees/refuerzo educativo 
11-Ancianos 
12-Inmigrantes
**************************************************************************************************

**************************************************************************************************
nivel_basico=xxx 

1-E.Infantil 
2-E.Especial 
3-E.Primaria 
4-ESO 
5-E.Adultos 
6-Transición Vida Adulta 
7-Ancianos 
8-Centros Ocupacionales
**************************************************************************************************

**************************************************************************************************
saa_basico=xxx // 1-Fotografía 6-Lectura/Escritura 7-Bimodal 9-Lengua de Signos 10-Braille 12-Pictogramas ARASAAC
**************************************************************************************************

nresults=10 //Numero de resultado que quiero obtener como máximo
KEY=xxxxxxxxxxxxxxxxx //Clave de la API de MATERIALES


EJEMPLOS DE BUSQUEDA
http://localhost/saac/api_materiales/index.php?callback=json&titulo_descripcion_basico=&autor_basico=&idiomas_basico=&area_curricular_basico=0&subarea_curricular_basico=0&tipo_basico=15&dirigido_basico=0&nivel_basico=0&saa_basico=0&KEY=6YOIFy4EH9rZBFH872Md

*/
require ('../classes/querys/query.php');
$query=new query();

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

if (isset($_GET['KEY'])) $key=$_GET['KEY'];

//************************************************************

$array_subcategorias='';
$array_subcategorias=array();

//************************************************************
//************************************************************
// CLAVE BORRADA - '***REMOVED***' , 	//Javier Marco
//**************************************************************
$valid_keys = array(
	'XXXXXXXXXXXXXXXXXXXX' , 	//ARABOARD
    'XXXXXXXXXXXXXXXXXXXX'  	//ARASAAC 
);

//************************************************************
if (isset($key) && in_array($key,$valid_keys)) { //COMPRUEBO SI LA CLAVE DE LA APLICACION PARA LA API ES VALIDA
	if (isset($_GET['callback'])) {
		
		if (isset($_GET['callback']) && $_GET['callback']=='json') {
					
			$texto_buscar=utf8_decode($_GET['titulo_descripcion_basico']);
			$licencia=2;
			$sql='';
			
			if (isset($_GET['area_curricular_basico']) && $_GET['area_curricular_basico'] > 0) {  $sql.="AND material_area_curricular LIKE '%{".$_GET['area_curricular_basico']."}%' ";  }
			if (isset($_GET['subarea_curricular_basico']) && $_GET['subarea_curricular_basico'] > 0) {  $sql.="AND material_subarea_curricular LIKE '%{".$_GET['subarea_curricular_basico']."}%' ";  }
			if (isset($_GET['tipo_basico']) && $_GET['tipo_basico'] > 0) {  $sql.="AND material_tipo LIKE '%{".$_GET['tipo_basico']."}%' ";  }
			if (isset($_GET['dirigido_basico']) && $_GET['dirigido_basico'] > 0) {  $sql.="AND material_dirigido LIKE '%{".$_GET['dirigido_basico']."}%' ";  }
			if (isset($_GET['nivel_basico']) && $_GET['nivel_basico'] > 0) {  $sql.="AND material_nivel LIKE '%{".$_GET['nivel_basico']."}%' ";  }
			if (isset($_GET['saa_basico']) && $_GET['saa_basico'] > 0) {  $sql.="AND material_saa LIKE '%{".$_GET['saa_basico']."}%' ";  }
			if (isset($_GET['idiomas_basico']) && $_GET['idiomas_basico'] !='') {  $sql.="AND material_idiomas LIKE '%{".$_GET['idiomas_basico']."}%' "; }
			
			if (isset($_GET['autor_basico']) && $_GET['autor_basico'] !='') {
				$autores=$query->buscar_autores_nombre(utf8_decode($_GET['autor_basico']));
				
					while ($row_autor=mysql_fetch_array($autores)) {
					
						$sql.="AND material_autor LIKE '%{".$row_autor['id_autor']."}%' "; 
					
					}
			}
		
		
		}
		
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
		
		$contar=$query->buscar_materiales($_SESSION['AUTHORIZED'],$texto_buscar,$licencia,$sql);

		$resultados=$query->buscar_materiales_limit($_SESSION['AUTHORIZED'],$texto_buscar,$licencia,$sql,$inicial,$cantidad);
			
		$total_records = $contar;
		$total_pages = intval($total_records / $cantidad);
		$n_resultados_por_pagina=mysql_num_rows($resultados);
			
			
			if ($total_records > 0 ) {
				
				echo '{
						"materials": [';
								
					$f=0;
					
					while ($row=mysql_fetch_array($resultados)) {
					
						$f++;
						
						echo '{
								';
						
						echo '"materialTITLE": "'.utf8_encode($row['material_titulo']).'",';
						echo '"Description": "'.utf8_encode($row['material_descripcion']).'", ';
						echo '"Objetives": "'.utf8_encode($row['material_objetivos']).'", ';
						
						$mau=str_replace('}{',',',$row['material_autor']);
                      	$mau=str_replace('{','',$mau);
                      	$mau=str_replace('}','',$mau);
                      	$mau=explode(',',$mau);
                      
                      	for ($u=0;$u<count($mau);$u++) { 
                        	if ($mau[$u]!='') {
                         		$data_autor=$query->datos_autor($mau[$u]);
								echo '"Author'.$u.'": "'.utf8_encode($data_autor['autor']).'", '; 
                        	}
                      	}
						
						$ma=str_replace('}{',',',$row['material_archivos']);
                      	$ma=str_replace('{','',$ma);
                      	$ma=str_replace('}','',$ma);
                      	$ma=explode(',',$ma);
                      
                      for ($i=0;$i<count($ma);$i++) { 
                        if ($ma[$i]!='') {
						 $archivo='http://'.$url_dominio.'/zona_descargas/materiales/'.$row['id_material'].'/'.$ma[$i];
						 
						 if ($i<(count($ma)-1)) {
						 	echo '"File'.$i.'": "'.$archivo.'", ';
						 } else {
							echo '"File'.$i.'": "'.$archivo.'"'; 
						 }
						 
                        }
                      }
					  
						
						
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
						"materials": [';
				}
				
				 echo ' ], 
						"itemCount": '.$cantidad.', 
						"page": '.$pg.', 
						"totalItemCount": '.$total_records.', 
						"pageCount": '.$total_pages.'
				}';

//****************************************************************
//****************************************************************

switch ($key) {

    case '***REMOVED***': 	//AraBoard
	$archivo = "araboard.txt";
	break;

	break;
    case '***REMOVED***':  	//ARASAAC 
	$archivo = "arasaac.txt";	
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