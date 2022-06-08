<?php

$fileToTranslate = json_decode(file_get_contents(__DIR__.'/fileToTranslate.json'));
$langToTranslate = "EN";
$deepl_apiKey = 'a569ef4e-a232-4b23-d666-b4e0ef3b0021:fx&';

$curl = curl_init();
$translation = [];

foreach ($fileToTranslate as $key => $value) {

  curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api-free.deepl.com/v2/translate?auth_key='.$deepl_apiKey.'&text='.$value.'&target_lang=' . $langToTranslate,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_CUSTOMREQUEST => 'POST'
  ]);
  $response = curl_exec($curl);
  curl_close($curl);

  $response = curl_exec($curl);
  if ($response === false) {
    continue;
  }
  $array = json_decode($response, true);
  if (is_null($array) or $array === false) {
    continue;
  }

  $translation['key'] = $array['translations'][0]['text'];
}



$file = fopen(__DIR__.'/translatesFile.json', "w");
fwrite($file, json_encode($translation));
fclose($file);
