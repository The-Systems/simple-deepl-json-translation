<?php

$fileToTranslate = json_decode(file_get_contents(__DIR__.'/fileToTranslate.json'), true);
$langToTranslate = "RU";
$deepl_apiKey = '4e1e7179-4363-60ab-6c0e-343c617374ae:fx';

$curl = curl_init();
$translation = [];
$translationCount = count($fileToTranslate);

echo PHP_EOL.'START SIMPLE DEEPL TRANSLATION BY THESYSTEMS'.PHP_EOL;
$i = 0;
foreach ($fileToTranslate as $key => $value) {
  $i++;
  curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api-free.deepl.com/v2/translate?auth_key='.$deepl_apiKey.'&text='.urlencode($value).'&target_lang=' . $langToTranslate,
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
    $translation[$key] = $value;
    echo $i."/".$translationCount. " (Error Request)".PHP_EOL;
    continue;
  }
  $array = json_decode($response, true);
  if (is_null($array) or $array === false) {
    $translation[$key] = $value;
    echo $i."/".$translationCount. " (Error JSON)".PHP_EOL;
    continue;
  }

  $translation[$key] = $array['translations'][0]['text'];
  echo $i."/".$translationCount. " - ".round(($i/$translationCount*100), 1)."% (Done)".PHP_EOL;
}
echo PHP_EOL."DONE".PHP_EOL;
unlink(__DIR__.'/translatesFile.json');
$file = fopen(__DIR__.'/translatesFile.json', "w");
fwrite($file, json_encode($translation, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
fclose($file);
