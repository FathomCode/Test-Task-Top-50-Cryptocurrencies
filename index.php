<?php
include "db.php";

/**
 * View TOP 10 List from DB
 **/

$topcoins = $DB->query("SELECT * FROM ".TABLE_NAME." ORDER BY market_cap DESC LIMIT 50")->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>    
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TOP 50</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<body>
  <table class="table table-striped">
    <thead>
      <tr class="table-primary">
        <th>#</th>
        <th>Name</th>
        <th>Ticker</th>
        <th>Price</th>
        <th>Change in 24h(%)</th>
        <th>Market Cap</th>
        <th>Volume(24h)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($topcoins as $key => $topcoin) : ?>
        <tr>
          <td><?=$key+1?></td>
          <td><?=$topcoin['name']?></td>
          <td><?=$topcoin['ticker']?></td>
          <td><?=$topcoin['price']+0?></td>
          <td><?=number_format($topcoin['percent_change_24h'], 2,'.',' ')?></td>
          <td><?=number_format($topcoin['market_cap']+0,2,'.',' ')?></td>
          <td><?=number_format($topcoin['volume_24h']+0,4,'.',' ')?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>

  </table>
 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>