<?php 
$file[]="Chiusure_settimana_customa169eeaae9705c4847f663972548ca91.csv";
function create_zip($files = array(),$destination = '',$overwrite = true) {
  //if the zip file already exists and overwrite is false, return false
  if(file_exists($destination) && !$overwrite) { return false; }
  //vars
  $valid_files = array();
  //if files were passed in...
  if(is_array($files)) {
    //cycle through each file
    foreach($files as $file) {
      //make sure the file exists
      if(file_exists($file)) {
        $valid_files[] = $file;
      }
    }
  }
  //if we have good files...
  if(count($valid_files)) {
    //create the archive
    $zip = new ZipArchive();
    if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
      return false;
    }
    //add the files
    foreach($valid_files as $file) {
      $zip->addFile($file,$file);

    }
   

    //close the zip -- done!
    $zip->close();

    //check to make sure the file exists
    return file_exists($destination);
  }
  else
  {
    return false;
  }
}
 $zip_file='export.zip';

$result = create_zip($file,$zip_file);
//header( 'Content-Type: text/plain' );
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), "/\\");

?>
<script type="text/javascript">
  window.location.href='http:\//<?php echo "$host$uri/$zip_file" ?>';
  setTimeout(function(){window.location.href='index.php?module=evvtApps&action=index';},5000);

</script>

?>