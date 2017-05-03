<?php
/*
 * This is a class that holds mostly static methods to make command-line scripts easy to write quickly.
 *
 * By Ian Monroe, http://www.ianmonroe.com
 *
 */

class CommandLine{
  /*
   * prompt a user for information
   * @returns a String
   *
   */
  static function ask_user($prompt){
    echo PHP_EOL.$prompt.' ';
    $input = trim(fgets(STDIN));
    return $input;
  }


  /*
  * Generates an error message and exits the program.
  */
  static function error_out($msg){
    echo $msg.PHP_EOL;
    exit();
  }

  /*
   * Create a directory
   */
  static function create_directory($directory_path, $perms=0777){
    echo ("Creating the directory: ".$directory_path.PHP_EOL);
    if (!file_exists($directory_path)){
      mkdir($directory_path, $perms, true);
    } else {
      self::error_out('Error: directory already exists or cannot be written.');
    }
  }

  /*
   * create a text file
   */
  static function create_file($file_path_and_name, $file_content){
    echo ("Setting up the file: ".$file_path_and_name.PHP_EOL);
    if (!file_exists($file_path_and_name)){
      file_put_contents($file_path_and_name, $file_content);
    } else {
      self::error_out('Error: file already exists or cannot be written.');
    }
  }

  /*
   * Uses CURL to fetch a URL, and saves it to the file specified by $filename
   */
  static function get_url_and_save($fully_qualified_url, $filename){
    // strange 400 errors can occur if we don't check to make sure our URL is trimmed up.
    $fully_qualified_url = trim($fully_qualified_url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fully_qualified_url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $output = curl_exec($ch);
    //curl_close($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $httpdebug = curl_getinfo($ch);
    if ($httpcode == 200){
      self::create_file($filename, $output);
      echo("Saved file: ".$filename.PHP_EOL);
    } else {
      echo ('error.  http debug: '.var_export($httpdebug, true).PHP_EOL);
    }

  }

  /*
   * Wrapper and error control for file_get_contents().  Returns the file content as a string.
   */
  static function read_file_to_string($filename){
    $output = false;
    try{
      $output = file_get_contents($filename);
    } catch (Exception $e){
      echo ('Error reading file: '.var_export($e, true).PHP_EOL);
      die();
    }
    return $output;
  }

  /*
   * Wrapper and error control for scandir(). Returns an array of files in the directory specified.
   */
  static function get_directory_list($directory_path){
    $output = false;
    try{
      $output = scandir($directory_path);
      foreach ($output as $key=>$file){
        if ( !is_file($directory_path.'/'.$file) ){
          // just return files please.
          unset($output[$key]);
        }
      }
    }catch(Exception $e){
      echo ('Error reading directory: '.var_export($e, true).PHP_EOL);
      die();
    }
    return $output;
  }

  static function execute($cmd){
	  echo 'Executing '.$cmd.PHP_EOL;
	  echo(shell_exec($cmd));
  }

  /*
  * we're essentially aliasing php's getopt() functionality.
  *
  */
  static function get_arguments(){
    global $argv;
    $_ARG = array();
    
    foreach ($argv as $arg){
      $temp_arg = explode('=', $arg);
      $_ARG[$temp_arg[0]] = null;
      if (isset($temp_arg[1])){
        $_ARG[$temp_arg[0]] = $temp_arg[1]; 
      }
    }
    
    return $_ARG;
  }
}
?>
