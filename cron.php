<?php
include "db.php";

/**
 * Curl get cryptocurrency list
 * USED coinmarketcap API
 **/

$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
$parameters = [
  'start' => '1',
  'limit' => '500',
  'convert' => 'USD'
];

$headers = [
  'Accepts: application/json',
  'X-CMC_PRO_API_KEY: 6b0df6e1-e3cc-405b-861c-a6320b854b95'
];
$qs = http_build_query($parameters); // query string encode the parameters
$request = "{$url}?{$qs}"; // create the request URL


$curl = curl_init(); // Get cURL resource
// Set cURL options
curl_setopt_array($curl, array(
  CURLOPT_URL => $request,            // set the request URL
  CURLOPT_HTTPHEADER => $headers,     // set the headers 
  CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
));

$response = curl_exec($curl); // Send the request, save the response

$response = json_decode($response); // json decoded response
curl_close($curl); // Close request

$data = $response->data;

//echo "<pre>";
//print_r($response); // print json decoded response



$tickers = $DB->query("SELECT ticker FROM ".TABLE_NAME)->fetchAll();
$tickers_in_DB = array();
foreach ($tickers as $val) {
	$tickers_in_DB[] = $val['ticker'];
}

//Проходим по полученому списку с API и обновляем добавляем данные
foreach ($data as $coin) {
	if (in_array($coin->symbol, $tickers_in_DB)) {
			
		 $stmt = $DB->prepare("UPDATE ".TABLE_NAME." SET price = :price, percent_change_24h = :percent_change_24h, market_cap = :market_cap, volume_24h = :volume_24h, updated = NOW()  WHERE `ticker` = :ticker");

	    //$stmt->bindParam(':name', $coin->name);
	    $stmt->bindParam(':ticker', $coin->symbol);
	    $stmt->bindParam(':price', $coin->quote->USD->price);
	    $stmt->bindParam(':percent_change_24h', $coin->quote->USD->percent_change_24h);
	    $stmt->bindParam(':market_cap', $coin->quote->USD->market_cap);
	    $stmt->bindParam(':volume_24h', $coin->quote->USD->volume_24h);

		try {
		    $execute = $stmt->execute();
		    echo $desc_link . " Изменены данные по валюте: ".$coin->symbol . "<br>";
		} catch (PDOException $exception) {
		    echo $desc_link . "Ошибка при изменении валюты: ".$coin->symbol . "<br>";
		    echo "Error: " . $exception->getMessage();
		}

	} else {

		 $stmt = $DB->prepare("INSERT INTO ".TABLE_NAME." (`name`, `ticker`, `price`, `percent_change_24h`, `market_cap`, `volume_24h`) VALUES (:name, :ticker, :price, :percent_change_24h, :market_cap, :volume_24h)");

	    $stmt->bindParam(':name', $coin->name);
	    $stmt->bindParam(':ticker', $coin->symbol);
	    $stmt->bindParam(':price', $coin->quote->USD->price);
	    $stmt->bindParam(':percent_change_24h', $coin->quote->USD->percent_change_24h);
	    $stmt->bindParam(':market_cap', $coin->quote->USD->market_cap);
	    $stmt->bindParam(':volume_24h', $coin->quote->USD->volume_24h);

		try {
		    $execute = $stmt->execute();
		    echo $desc_link . " Добавлено валюта: ".$coin->symbol . "<br>";
		} catch (PDOException $exception) {
		    echo $desc_link . "Ошибка при добавлении валюты: ".$coin->symbol . "<br>";
		    echo "Error: " . $exception->getMessage();
		}
	}
}

?>
