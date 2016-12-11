<?php

ob_start();

$provider1Response = file_get_contents('http://api.nbp.pl/api/exchangerates/tables/A/');
$provider2Response = file_get_contents('http://api.exchangeratelab.com/api/current/PLN?apikey=F3501B8426AD98132626036600C8A1FC');

$kursy_nbp =  json_decode($provider1Response, true);
$kursy_exchangeratelab = json_decode($provider2Response, true);

$items = array_merge(convertNBP($kursy_nbp), convertExchangerateLab($kursy_exchangeratelab));

shuffle($items);

function convertNBP($kursy_nbp){
    $result = array();
    foreach ($kursy_nbp[0]['rates'] as $nbp){
         $item = array(
            'tytul' => 'Ile Polskich Z³otych za 1 '.$nbp['currency'],
			'kod' => $nbp['code'],
			'kurs' => $nbp['mid'],
			'zrodlo' => 'NBP'         
        );
        array_push($result, $item);
    }
    return $result;
}

function convertExchangerateLab($kursy_exchangeratelab){
    $result = array();
    foreach ($kursy_exchangeratelab['rates'] as $elab){
        $item = array(
            'tytul' => 'Ile '.$elab['to'].' za 1 PLN',
			'kod' => $elab['to'],
			'kurs' => $elab['rate'],
			'zrodlo' => 'ExchangerateLab' 
        );
        array_push($result, $item);
    }
    return $result;
}

foreach($items as $view) {
  $wynik .= '<tr>
    <td>'.$view['tytul'].'</td>
    <td>'.$view['kod'].'</td>
    <td>'.$view['kurs'].'</td>
    <td>'.$view['zrodlo'].'</td>
  </tr>';
}

echo '<h1>KURSY WALUT</h1>
<table width="800px" border="2" cellspacing="0" cellpadding="0">
  <tr>
    <td><b>Sposób przeliczania</b></td>
    <td><b>Kod waluty</b></td>
    <td><b>Kurs</b></td>
    <td><b>¬ród³o</b></td>
  </tr>'.$wynik.'
  </table>';
