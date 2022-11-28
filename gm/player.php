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

$stmt=$conn->prepare('SELECT * FROM music');
$stmt->execute();

$htmlstr="";
$result=$stmt->get_result();
if ($result->num_rows!=0){
  while ($row = $result->fetch_assoc()){
      $vname=htmlentities($row['name']);
      $htmlstr=$htmlstr."<label class='song'><input type='radio' name='music' value='{$vname}' checked><span class='radiomark'><i class='fa-solid fa-check'></i></span><span class='mname'>{$row['name']}</span><span class='mtags'>{$row['tag']}</span><span class='mcats'>{$row['categories']}</span></label>";
}}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <script src="https://kit.fontawesome.com/0bbd382f3c.js" crossorigin="anonymous"></script>
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/screen.css">
    <link rel="stylesheet" href="../css/music.css">
    <title>A</title>
  </head>
  <body>
    <h1>Select music</h1>
    <article class="options">
      <?php
      echo $htmlstr;
      ?>
    </article>
    <label id="playbtn">
      <input type="checkbox" name="play" value="1">
      <i class="fa-solid fa-play"></i>
      <i class="fa-solid fa-pause"></i>
    </label>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script type="text/javascript" async>
    let colordict={
      'atmospheric' : '#03a9f4',
      'chase' : '#c500a4',
      'combat' : '#f30000',
      'exploration' : '#ffa622',
      'empty' : '#58e747'
    }

    $(document).ready(function(){
      let tags=[...document.getElementsByClassName('mtags')].map(function(x){x.innerHTML=(x.innerHTML!="")?x.innerHTML.split(',').map(e =>'#'+e.trim()).join(' '):""})
      let cats=[...document.getElementsByClassName('mcats')].map(function(x){x.innerHTML=(x.innerHTML!="")?x.innerHTML.split(',').map(e => ' '+e.trim().replace(/\s/g,'')).join(','):""})
      cats=[...document.getElementsByClassName('mcats')].map(function(x) {
        let m=((x.innerHTML!="")?x.innerHTML.split(',').map(e => e.trim().replace(/\s/g,'')):["empty"]);
        m=(m.length>1)? m : [...m,'empty']
        let arr='linear-gradient(240deg,'+m.map(e => colordict[e]).join(',')+')'
        $(x).parent().css('background',arr)
        $(x).parent().css('background-size','400% 400%')
      })
    })

      let play=setInterval(()=>{
        state=(document.querySelector('input[name=play]').checked)?1:0;
        name=document.querySelector('input[name=music]:checked').value;
        fetch( `savemusic.php/?state=${state}&song=${name}` )
    },1000)

    </script>
  </body>
</html>
