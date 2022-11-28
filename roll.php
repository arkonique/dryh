<?php
if( isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] != 'iframe' ) {
?>
<script type="text/javascript">
  window.location="./";
</script>
<?php
}
$config = parse_ini_file('../../dryh_config.ini');
$conn = new mysqli($config['server'],$config['uname'],$config['pw'],$config['db']);

session_start();

if (!isset($_SESSION['token'])){
  ?>
  <script type="text/javascript">
  window.location = "./";
</script>
  <?php
}

else {
  $token=$_SESSION['token'];
  $i=0;
  $stmt=$conn->prepare("SELECT * FROM rolls WHERE uid=?");
  $stmt->bind_param('s',$token);
  $stmt->execute();
  $result=$stmt->get_result();
  if ($result->num_rows!=0){
    while ($row = $result->fetch_assoc()){
        foreach ($row as $key => $value) {
          $r[$i]=intval($value)-1;
          $i++;
        }
    }
  }
}


$dicearr=["<i class='fa-solid fa-dice-one'></i>","<i class='fa-solid fa-dice-two'></i>","<i class='fa-solid fa-dice-three'></i>","<i class='fa-solid fa-dice-four'></i>","<i class='fa-solid fa-dice-five'></i>","<i class='fa-solid fa-dice-six'></i>"]

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title></title>
    <script src="https://kit.fontawesome.com/0bbd382f3c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/roller.css">
  </head>
  <body>
    <h1>Roll Conflict</h1>
    <h2>Last roll: </h2>
    <p id="results">
      <span id="ex"><?php
        for ($i=1; $i < 7; $i++) {
          if ($r[$i]>=0) {
            echo $dicearr[$r[$i]];
          }
        }
      ?></span>
      <span id="ma"><?php
        for ($i=7; $i < 13; $i++) {
          if ($r[$i]>=0) {
            echo $dicearr[$r[$i]];
          }
        }
      ?></span>
      <span id="di"><?php
        for ($i=13; $i < 16; $i++) {
          if ($r[$i]>=0) {
            echo $dicearr[$r[$i]];
          }
        }
      ?></span>
    </p>
    <h2>New roll: </h2>
    <form class="" action="" method="post">
        <p id="curr-ex">Current Exhaustion: </p>
        <input type="range" min="0" max="<?php echo $_GET['e']; ?>" name="exhaustion" id="exhaustion" value="0">
        <p id="exhaustion-p">Using: </p>
        <p id="curr-ma">Current Madness: </p>
        <input type="range" min="0" max="<?php echo $_GET['m']; ?>" name="madness" id="madness" value="0">
        <p id="madness-p">Using: </p>
        <p id="curr-di">Current Discipline: </p>
        <input type="range" min="0" max="<?php echo $_GET['d']; ?>" name="discipline" id="discipline" value="0">
        <p id="discipline-p">Using: </p>
        <button type="submit" name="submitform">Roll</button>
    </form>
  <script type="text/javascript">
  var slider1 = document.getElementById("exhaustion");
  var output1 = document.getElementById("exhaustion-p");
  slider1.oninput = function() {
    html=`Using: `;
    for (var i = 0; i < this.value; i++) {
      html=`${html}<i class="fa-solid fa-dice-d6"></i>`
    }
    output1.innerHTML = html;
  }
  var slider2 = document.getElementById("madness");
  var output2 = document.getElementById("madness-p");
  slider2.oninput = function() {
    html=`Using: `;
    for (var i = 0; i < this.value; i++) {
      html=`${html}<i class="fa-solid fa-dice-d6"></i>`
    }
    output2.innerHTML = html;
  }
  var slider3 = document.getElementById("discipline");
  var output3 = document.getElementById("discipline-p");
  slider3.oninput = function() {
    html=`Using: `;
    for (var i = 0; i < this.value; i++) {
      html=`${html}<i class="fa-solid fa-dice-d6"></i>`
    }
    output3.innerHTML = html;
  }

  var output4 = document.getElementById("curr-ex");
  html=`Current Exhaustion: `;
  for (var i = 0; i < <?php echo $_GET['e'];?>; i++) {
    html=`${html}<i class="fa-solid fa-dice-d6"></i>`
  }
  output4.innerHTML=html;

  var output4 = document.getElementById("curr-ma");
  html=`Current Madness: `;
  for (var i = 0; i < <?php echo $_GET['m'];?>; i++) {
    html=`${html}<i class="fa-solid fa-dice-d6"></i>`
  }
  output4.innerHTML=html;

  var output4 = document.getElementById("curr-di");
  html=`Current Discipline: `;
  for (var i = 0; i < <?php echo $_GET['d'];?>; i++) {
    html=`${html}<i class="fa-solid fa-dice-d6"></i>`
  }
  output4.innerHTML=html;

  </script>
</body>
</html>

<?php
  if (isset($_POST['submitform'])){
    $erolls = range(1, 6);
    shuffle($erolls );
    $erolls = array_slice($erolls ,0,$_POST['exhaustion']);

    $mrolls = range(1, 6);
    shuffle($mrolls );
    $mrolls = array_slice($mrolls ,0,$_POST['madness']);

    $drolls = range(1, 6);
    shuffle($drolls );
    $drolls = array_slice($drolls ,0,$_POST['discipline']);

    $erolls=array_pad($erolls,6,0);
    $mrolls=array_pad($mrolls,6,0);
    $drolls=array_pad($drolls,3,0);
    $rolls=array_merge($erolls,$mrolls,$drolls);
    $rolls[]=$token;
    $stmt=$conn->prepare(
      "UPDATE rolls SET d1=?, d2=?, d3=?, d4=?, d5=?, d6=?, d7=?, d8=?, d9=?,
      d10=?, d11=?, d12=?, d13=?, d14=?, d15=? WHERE uid=?"
    );
    $stmt->execute($rolls);
    ?>
    <script type="text/javascript">
      window.location=window.location.href
    </script>
    <?php
  }
?>
