<?php
$config = parse_ini_file('../../../dryh_config.ini');
$conn = new mysqli($config['server'],$config['uname'],$config['pw'],$config['db']);

session_start();
if (!isset($_SESSION['uname'])){
  ?>
  <script type="text/javascript">
  window.location = "./";
</script>
  <?php
}

$stmt=$conn->prepare("SELECT * FROM chars");
$stmt->execute();
$result=$stmt->get_result();
if ($result->num_rows!=0){
  while ($row = $result->fetch_assoc()){
    $players[$row['uid']]=$row['charname'];
  }
}



$stmt=$conn->prepare("SELECT * FROM rolls");
$stmt->execute();
$result=$stmt->get_result();
if ($result->num_rows!=0){
  ?>
  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="../css/liverolls.css">
      <title></title>
    </head>
    <body>

  <table id="rolls">
    <thead>
      <th>Player</th>
      <th>E1</th>
      <th>E2</th>
      <th>E3</th>
      <th>E4</th>
      <th>E5</th>
      <th>E6</th>
      <th>M1</th>
      <th>M2</th>
      <th>M3</th>
      <th>M4</th>
      <th>M5</th>
      <th>M6</th>
      <th>D1</th>
      <th>D2</th>
      <th>D3</th>
    </thead>
      <?php
      while ($row = $result->fetch_assoc()){
        echo "<tr>
        <td>{$players[$row['uid']]}</td>
        <td>{$row['d1']}</td>
        <td>{$row['d2']}</td>
        <td>{$row['d3']}</td>
        <td>{$row['d4']}</td>
        <td>{$row['d5']}</td>
        <td>{$row['d6']}</td>
        <td>{$row['d7']}</td>
        <td>{$row['d8']}</td>
        <td>{$row['d9']}</td>
        <td>{$row['d10']}</td>
        <td>{$row['d11']}</td>
        <td>{$row['d12']}</td>
        <td>{$row['d13']}</td>
        <td>{$row['d14']}</td>
        <td>{$row['d15']}</td>
        </tr>
        ";
      }
    }

  ?>
</table>

</body>
</html>
