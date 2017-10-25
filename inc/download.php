<?php
global $wpdb;
echo "Hi"

ignore_user_abort(true);
set_time_limit(0); 

$download_file = $_GET['download_file'];
$file_id = $_GET['id'];

$results = $wpdb -> get_results("SELECT id, category, doc_name, zip_code, doc_name FROM $table_doc WHERE id = '$file_id' LIMIT 1;");

$data = $results[0];

$path = get_bloginfo( 'stylesheet_directory' ) . "/assets/docs/" . $data -> zip_code . "/" . $data -> category . "/";

$dl_file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).]|[\.]{2,})", '', $download_file); 
$dl_file = filter_var($dl_file, FILTER_SANITIZE_URL);
$fullPath = $path.$dl_file;
 
if ($fd = fopen ($fullPath, "r")) {
    $fsize = filesize($fullPath);
$path_parts = pathinfo($fullPath);
$ext = strtolower($path_parts["extension"]);
switch ($ext) {
case "pdf":
header("Content-type: application/pdf");
header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a file download
break;
// add more headers for other content types here
default:
header("Content-type: application/octet-stream");
header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
break;
}
header("Content-length: $fsize");
header("Cache-control: private"); //use this to open files directly
while(!feof($fd)) {
$buffer = fread($fd, 2048);
echo $buffer;
}
}
fclose ($fd);
   
exit;

?>