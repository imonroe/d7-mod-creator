<?php 
/**
*	Command-line script 
*
*/
error_reporting(E_ERROR | E_PARSE);
require "class_CommandLine.php";

function add_field(){
  $output = array();
  $output['machine_name'] = CommandLine::ask_user("What is the machine name for this field? e.g., my_field : ");  
  $output['#title'] = "t('" .CommandLine::ask_user("What is the label for this field? e.g., My Field : "). "')";
  $output['#description'] = "'".CommandLine::ask_user("Short description? : ")."'";
  
  $question = "What type of field will this be?".PHP_EOL;
  $question .= 'Allowed values - {textfield, textarea, select, hidden, submit, radios, date, markup} : ';
  switch(CommandLine::ask_user($question)){
    case 'textfield':
      $output['#type'] = "'textfield'";
      $output['#default_value'] = "'".CommandLine::ask_user("Default value? : ")."'";
      $output['#maxlength'] = '255';
      $output['#size'] = '60';
      break;
    case 'textarea':
      $output['#type'] = "'textarea'";
      $output['#default_value'] = "'".CommandLine::ask_user("Default value? : ")."'";
      $output['#cols'] = '60';
      $output['#rows'] = '5';
      break;
    case 'select':
      $output['#type'] = "'select'";
      $output['#options'] = array();
      while (CommandLine::ask_user("Add an option? {y,n} : ") == 'y'){
        $label = "t('".CommandLine::ask_user("Option label? : ")."')";
        $value = "'".CommandLine::ask_user("Option value? : ")."'";
        $output['#options'][$value]=$label;    
      }
      break;
    case 'radios':
      $output['#type'] = "'radios'";
      $output['#options'] = array();
      while (CommandLine::ask_user("Add an option? {y,n} : ") == 'y'){
        $label = "t('".CommandLine::ask_user("Option label? : ")."')";
        $value = "'".CommandLine::ask_user("Option value? : ")."'";
        $output['#options'][$value]=$label;    
      }
      break;
    case 'hidden':
      $output['#type'] = "'hidden'";
      $output['#value'] = "'".CommandLine::ask_user("Value? : ")."'";
      break;
    case 'submit':
      $output['#type'] = "'submit'";
      $output['#value'] = "t('".CommandLine::ask_user("Value? : ")."')";
      break;
    case 'date':
      $output['#type'] = "'date'";
      $output['#default_value'] = array("'year'" => 1975, "'month'" => 6, "'day'" => 2);
      break;
    case 'markup':
      $output['#type'] = "'item'";
      $output['#markup'] = "'".CommandLine::ask_user("Basic HTML markup : ")."'";
      break;
    default:
      break;
  }
  $req = CommandLine::ask_user("Required? {y/n} : ");
  $output['#required'] = ($req == 'y') ? 'TRUE' : 'FALSE'; 
  return $output;
}

echo("Quick Form Builder script for the Drupal 7 Forms API".PHP_EOL.PHP_EOL);

$module_name = CommandLine::ask_user("What's the machine name of the module you're working on?  e.g., vh_example_forms : ");
$form_name = CommandLine::ask_user("What's the machine name of the form you'd like to build?  e.g., my_form : ");

$fields_array = array();

while(CommandLine::ask_user("Add a field to the form? {y,n} : ") == 'y' ){
  $fields_array[] = add_field();
}

$src = "<?php".PHP_EOL.PHP_EOL;
$src .= "function _{$module_name}_{$form_name}_form(".'$form, &$form_state'.") {".PHP_EOL.PHP_EOL;  // this is going to hold our composed output.

foreach ($fields_array as $field){
  $src .= '  $form[\''.$field['machine_name'].'\'] = array('.PHP_EOL;
  foreach ($field as $key => $value ){
    if ($key != 'machine_name'){
      if (!is_array($value)){
        $src .= "    '{$key}' => {$value},".PHP_EOL;
      } else {
        $src .= "    '{$key}' => array(".PHP_EOL;
        foreach ($value as $i => $v){
          $src .= "      {$i} => {$v}, ".PHP_EOL;
        }
        $src .= "    ),".PHP_EOL;
      }
    }
  }
  $src .= '  );'.PHP_EOL.PHP_EOL;
  
}
$src .= PHP_EOL.'  return $form;'.PHP_EOL;
$src .= "}".PHP_EOL.PHP_EOL;
$src .= "function _{$module_name}_{$form_name}_form_validate(".'$form, &$form_state'.") { ".PHP_EOL."// validation handler ".PHP_EOL."}".PHP_EOL.PHP_EOL;
$src .= "function _{$module_name}_{$form_name}_form_submit(".'$form, &$form_state'.") { ".PHP_EOL."// submission handler".PHP_EOL.PHP_EOL;
$src .= '  $vals = $form_state[\'values\'];'.PHP_EOL.PHP_EOL;
$src .= '  /** '.PHP_EOL;
$src .= '  * Available Values: '.PHP_EOL;
foreach ($fields_array as $field){
  $src .= '  * $vals[\''.$field['machine_name'].'\'] '.PHP_EOL;
}
$src .= '  **/ '.PHP_EOL;
$src .= '  drupal_set_message("Submission function was triggered!"); '.PHP_EOL;
$src .= '}'.PHP_EOL;
$output_filename = CommandLine::ask_user("What shall I name the output file? e.g., output.php : ");
//echo $src;

CommandLine::create_file($output_filename, $src);

echo "Alright, buddy!  My work here is done.  Thanks for using me.".PHP_EOL.PHP_EOL;
echo (PHP_EOL.PHP_EOL."Finished Script.".PHP_EOL);
exit();
?>



