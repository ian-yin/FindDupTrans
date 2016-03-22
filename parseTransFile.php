<?php

require_once('vendor/autoload.php');

$climate = new League\CLImate\CLImate;

$option = getopt('f:');
if (!file_exists($option['f'])) {
  $climate->lightRed('invalid file path');
  $climate->lightRed('Usage:');
  $climate->lightRed('php parseTransFile.php -f <file-path>');
  exit(0);
}

$handle = fopen($option['f'], "r");

$msgid_array = array();
$line_number = 0;
$dup_counter = 0;

while (!feof($handle)) {
  $line = fgets($handle);
  $line_number++;

  $parts = explode(' "', $line);

  if ($parts[0] == 'msgid') {
    $msgid = str_replace('"', '', trim($parts[1]));
    if (in_array($msgid, $msgid_array)) {
      $climate->lightYellow("Line: " . $line_number . " msgid " . $msgid);
      $dup_counter++;
    }
    else {
      $msgid_array[$line_number] = $msgid;
    }
  }
}

$climate->lightRed('Total amount of duplicated msgid: ' . $dup_counter);

fclose($handle);
