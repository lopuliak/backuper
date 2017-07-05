<?php

/* format : an_rem-mach_ru.content.2016-03-10.7z */




/* Конфиг */

$config = array(

	'an_rem-mach_ru' => array(
		'day' => 12,
		'month' => 6,
	),
		
	'an_rem-mach_ru1' => array(
		'day' => 10,
		'month' => 2,
	),
		
);


/* Текущая дата */
define("TODAY", date("Y-m-d"));

/* Список файлов в текущей папке */
$file_list = scandir(__DIR__);

/* Файл со скриптом */
$curr_file = str_replace(__DIR__."/","",__FILE__);
 
foreach ($config as $site => $params){
	
	/* Формируем массив с дневными датами */
	for ($i = 0; $i < $params['day']; $i++) {
		$d = new DateTime(TODAY);
		$d->modify("-$i day");
		$file_dates[$site][] = $d->format("Y-m-d");
	}
	
	/* Формируем массив с месячными датами */
	for ($i = 0; $i < $params['month']; $i++) {
		$d = new DateTime(TODAY);
		$d->modify("-$i month");
		$file_dates[$site][] = $d->format("Y-m")."-01";
	}
	
	/* Формируем массив удаляемых файлов */
	foreach ($file_list as $key => $file_name){
		if($file_name == $curr_file || $file_name == '..' || $file_name == '.' || $file_name == 'files_to_del.log'){
			unset($file_list[$key]);
		}		
		$segments = explode('.',$file_name);
		if( count( array_intersect ( $segments, $file_dates[$site] ) ) > 0 && in_array($site,$segments) ) {
			unset($file_list[$key]);
		}
			
	}

}
	
/* Удаляем файлы */
foreach ($file_list as $file_name){
	@unlink($file_name);
}

file_put_contents($_SERVER['DOCUMENT_ROOT']."/files_to_del.log","");
file_put_contents($_SERVER['DOCUMENT_ROOT']."/files_to_del.log", print_r($file_list,true).PHP_EOL, FILE_APPEND); 
?>

