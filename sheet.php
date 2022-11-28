<?php

#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);

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
  $token=$_SESSION['token']; // column 1
  $i=0;
  $stmt=$conn->prepare("SELECT * FROM chars WHERE uid=?");
  $stmt->bind_param('s',$token);
  $stmt->execute();
  $result=$stmt->get_result();
  if ($result->num_rows!=0){
    while ($row = $result->fetch_assoc()){
        foreach ($row as $key => $value) {
          $c[$i]=(!empty($value))?htmlentities($value):"";
          $i++;
        }
    }
  }
  $i=1;

  $c[9]=($c[9]=="")?"img/default.png":$c[9];

}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Don't Rest Your Head Character Sheet</title>
    <script src="https://kit.fontawesome.com/0bbd382f3c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/csshake.min.css">
    <link rel="stylesheet" href="css/sheet.css">
    <link rel="icon" href="https://dnd5echarsheet.com/dryh/favicon.ico" type="image/x-icon" />
  </head>


  <body>
    <div>
    <h1 id="title">Don't Rest Your Head  <span class='shake shake-constant' style="
    margin: 0 20px;
"><?php echo $c[1];?></span>:)</h1>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
      <fieldset class="top">
        <legend>Stats</legend>
        <label for="portrait"><img src="<?php echo $c[9]; ?>" id="portrait-img" title="Upload character image"></label><input type="file" name="portrait" value="" id="portrait" onchange="readURL(this)" style="display: none;">
        <br>
        <label for="pname">Player: </label> <input type="text" name="pname" value="<?php echo $c[$i];$i++;?>" style="font-family: 'nightmare'"><br>
        <label for="name">Character name: </label><input type="text" name="name" value="<?php echo $c[$i];$i++;?>" style="font-family: 'nightmare'">
        <br>
        <fieldset class="exf">
          <legend style="color: white">Exhaustion</legend>
          <?php
          // parse edm
          $c[3]=($c[3]=="")?'6,3,3':$c[3];
          $emd=explode(",",$c[3]);
          for ($loop=0; $loop < intval($emd[0]); $loop++) {
            echo "<label class='container'>
            <input type='checkbox' name='exhaustion[]' value='1' checked>
            <span class='checkmark ex'><i class='fa-solid fa-dice-six'></i></span>
            </label>";
          }
          for ($loop=0; $loop < 6-intval($emd[0]); $loop++) {
            echo "<label class='container'>
            <input type='checkbox' name='exhaustion[]' value='1'>
            <span class='checkmark ex'><i class='fa-solid fa-dice-six'></i></span>
            </label>";
          }
          ?>
          <input type="hidden" name="ne" value="<?php echo $emd[0];?>">
          <input type="hidden" name="nm" value="<?php echo $emd[1];?>">
          <input type="hidden" name="nd" value="<?php echo $emd[2];?>">
        </fieldset>
        <?php
          $mm=3-intval($emd[2]);
          $ww=$mm+3-intval($emd[1]);
          if (intval($emd[1])<=3) {
            echo "<fieldset class='exf'><legend style='color: red'>Madness</legend>";
            for ($loop=0; $loop < intval($emd[1]); $loop++) {
              echo "<label class='container'>
              <input type='checkbox' name='madness[]' value='1' checked>
              <span class='checkmark ma'><i class='fa-solid fa-dice-six'></i></span>
              </label>";
            }
            for ($loop=0; $loop < 3-intval($emd[1]); $loop++) {
              echo "<label class='container'>
              <input type='checkbox' name='madness[]' value='1'>
              <span class='checkmark ma'><i class='fa-solid fa-dice-six'></i></span>
              </label>";
            }
            echo "</fieldset>";
            echo "<fieldset class='exf'><legend style='color: #4f85ff'>Discipline/Madness</legend>";
            for ($loop=0; $loop < $mm; $loop++) {
              echo"<label class='container'>
                <input type='checkbox' name='discipline[0][]' value='1'>
                <input type='hidden' name='discipline[1][]' value='0'>
                <span class='checkmark di'><i class='fa-solid fa-dice-six'></i></span>
              </label>";
            }
            for ($loop=0; $loop < $emd[2]; $loop++) {
              echo"<label class='container'>
                <input type='checkbox' name='discipline[0][]' value='1' checked>
                <input type='hidden' name='discipline[1][]' value='d'>
                <span class='checkmark di'><i class='fa-solid fa-dice-six'></i></span>
              </label>";
            }
            echo "</fieldset>";
          }
          else {
            echo "<fieldset class='exf'><legend style='color: red'>Madness</legend>";
            for ($loop=0; $loop < 3; $loop++) {
              echo "<label class='container'>
              <input type='checkbox' name='madness[]' value='1' checked>
              <span class='checkmark ma'><i class='fa-solid fa-dice-six'></i></span>
              </label>";
            }
            echo "</fieldset>";
            echo "<fieldset class='exf'><legend style='color: #4f85ff'>Discipline/Madness</legend>";
            for ($loop=0; $loop < $emd[1]-3; $loop++) {
              echo"<label class='container'>
                <input type='checkbox' name='discipline[0][]' value='1'>
                <input type='hidden' name='discipline[1][]' value='m'>
                <span class='checkmark di'><i class='fa-solid fa-dice-six'></i></span>
              </label>";
            }
            for ($loop=0; $loop < $ww; $loop++) {
              echo"<label class='container'>
                <input type='checkbox' name='discipline[0][]' value='1'>
                <input type='hidden' name='discipline[1][]' value='0'>
                <span class='checkmark di'><i class='fa-solid fa-dice-six'></i></span>
              </label>";
            }
            for ($loop=0; $loop < $emd[2]; $loop++) {
              echo"<label class='container'>
                <input type='checkbox' name='discipline[0][]' value='1' checked>
                <input type='hidden' name='discipline[1][]' value='d'>
                <span class='checkmark di'><i class='fa-solid fa-dice-six'></i></span>
              </label>";
            }
            echo "</fieldset>";
          }
          $i++;
        ?>
        <br>
        <fieldset class="fof">
        <span id="fof">Fight or Flight: </span>
        <?php
        // parse fof
          $c[4]=($c[4]=="")?0:intval($c[4]);
          $f1=intval($c[4]/100);
          $f2=intval($c[4]/10)-10*$f1;
          $f3=$c[4]%10;
          $i++;
        ?>
        <label class="container">
          <input type="checkbox" name="fof[0][]" value="">
          <input type="hidden" name="fof[1][]" value="<?php echo $f1; ?>">
          <span class="fcheckmark"><i class="fa-solid fa-shoe-prints"></i><i class="fa-solid fa-hand-fist"></i><i class="fa-regular fa-circle"></i></span>
        </label>
        <label class="container">
          <input type="checkbox" name="fof[0][]" value="">
          <input type="hidden" name="fof[1][]" value="<?php echo $f2; ?>">
          <span class="fcheckmark"><i class="fa-solid fa-shoe-prints"></i><i class="fa-solid fa-hand-fist"></i><i class="fa-regular fa-circle"></i></span>
        </label>
        <label class="container">
          <input type="checkbox" name="fof[0][]" value="">
          <input type="hidden" name="fof[1][]" value="<?php echo $f3; ?>">
          <span class="fcheckmark"><i class="fa-solid fa-shoe-prints"></i><i class="fa-solid fa-hand-fist"></i><i class="fa-regular fa-circle"></i></span>
        </label>
      </fieldset>
        <br>
        <fieldset class="talents">
          <label for="etalent">Exhaustion talent: </label><input type="text" name="etalent" value="<?php echo $c[$i];$i++;?>" placeholder=" "><br>
          <textarea name="etalent-desc" placeholder="Description"><?php echo $c[$i];$i++;?></textarea>
        </fieldset>
        <fieldset class="talents" style="margin-bottom: 0">
          <label for="mtalent">Madness Talent: </label><input type="text" name="mtalent" value="<?php echo $c[$i];$i++;?>" placeholder=" "><br>
          <textarea name="mtalent-desc" placeholder="Description"><?php echo $c[$i];$i++;$i++?></textarea>
        </fieldset>
        <br>
      </fieldset>
      <fieldset class="top">
        <legend>Personality</legend>
        <textarea name="w-happened" rows="8" cols="80" placeholder="What just happened to you?"><?php echo $c[$i];$i++;?></textarea>
        <br>
        <textarea name="w-awake" rows="8" cols="80" placeholder="What is keeping you awake?"><?php echo $c[$i];$i++;?></textarea>
        <br>
        <textarea name="w-surface" rows="8" cols="80" placeholder="What is on the surface?"><?php echo $c[$i];$i++;?></textarea>
        <br>
        <textarea name="w-beneath" rows="8" cols="80" placeholder="What lies beneath?"><?php echo $c[$i];$i++;?></textarea>
        <br>
        <textarea name="w-path" rows="8" cols="80" placeholder="What is your path?"><?php echo $c[$i];$i++;?></textarea>
        <br>
      </fieldset>
      <fieldset class="top">
        <h3 class="heading">Inventory</h3>
        <div class="inv-wrapper">
        <?php
          $c[$i]=($c[$i]=="")?"|||||||||||||||||||||||||||||||||||||||||||||||||":$c[$i];
          $inv=explode("|",$c[$i]);
          foreach ($inv as $key => $value) {
            echo "<input type='text' name='inventory[]' value='${value}'  placeholder=' ' class='inv'>";
          }
          $i++;
        ?>
        </div>
      </fieldset>
      <fieldset class="top">
        <h3 class="heading">Scars</h3>
        <div class="scars-wrapper">
            <textarea name="scars[]" rows="8" cols="80" placeholder=" "><?php echo $c[$i];$i++;?></textarea>
            <textarea name="scars[]" rows="8" cols="80" placeholder=" "><?php echo $c[$i];$i++;?></textarea>
            <textarea name="scars[]" rows="8" cols="80" placeholder=" "><?php echo $c[$i];$i++;?></textarea>
            <textarea name="scars[]" rows="8" cols="80" placeholder=" "><?php echo $c[$i];$i++;?></textarea>
            <textarea name="scars[]" rows="8" cols="80" placeholder=" "><?php echo $c[$i];$i++;?></textarea>
            <textarea name="scars[]" rows="8" cols="80" placeholder=" "><?php echo $c[$i];$i++;?></textarea>
        </div>
      </fieldset>
      <fieldset class="top">
        <h3 class="heading">Notes</h3>
        <textarea name="notes" rows="8" cols="80" placeholder=" "><?php echo $c[$i];$i++;?></textarea>
        <a href="#" id="refopener">Rules reference</a>
        <embed src="dryh-reference.pdf" id="references" />
        <span class="closer"><i class="fa-solid fa-circle-xmark"></i></span>
      </fieldset>
      <div class="timer">
        00:00:00
      </div>
      </div>
      <nav>
        <div class="navtop">
          <button type="button" name="roller" onclick="reloadIFrame()"><i class="fa-solid fa-dice"></i></button>
          <button type="button" name="hope" onclick="reloadIFrame2()"><i class="fa-solid fa-sun"></i></button>
        </div>
        <div class="navbot">
          <button type="submit" name="submitform"><i class="fa-solid fa-floppy-disk"></i></button>
          <a href="logout" id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
          <div class="navspacer"></div>
        </div>
      </nav>
    </form>
    <div id="roller-div"><iframe id="roller" src=""></iframe> <span class="closer"><i class="fa-solid fa-circle-xmark"></i></span> </div>
    <div id="hoper-div"><iframe id="hoper" src=""></iframe> <span class="closer"><i class="fa-solid fa-circle-xmark"></i></span> </div>
    <audio src="" id="musicplayer" controls></audio>
    <div class="showimg">
      <img src="img/default.png" alt="" id="shower">
       <span class="closer"><i class="fa-solid fa-circle-xmark"></i></span>
    </div>
  </body>
  <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
  <script type="text/javascript" async>

    $(document).ready(function(){
      [...document.querySelectorAll("input[name^='discipline'][value='m']")].map((x) =>  x.previousElementSibling.indeterminate=true );
      [...document.querySelectorAll("input[name^='fof'][value='2']")].map((x) =>  x.previousElementSibling.indeterminate=true );
      [...document.querySelectorAll("input[name^='fof'][value='1']")].map((x) =>  x.previousElementSibling.checked=true );
    });

    $('#refopener').click(function(){
      $(this).next().css('transform','scale(1)')
      $(this).next().next().css('display','block')
    })

    $(window).bind('beforeunload', function(){
      return 'Are you sure you want to leave?';
    });


    function reloadIFrame() {
      ne=$('input[name="ne"]').val();
      nm=$('input[name="nm"]').val();
      nd=$('input[name="nd"]').val();
      document.getElementById("roller").src=`roll.php?e=${ne}&m=${nm}&d=${nd}`;
      $("#roller").css('transform','scale(1)');
      $("#roller").next().css('display','block');
    }

    function reloadIFrame2() {
      document.getElementById("hoper").src=`hope.php`;
      $("#hoper").css('transform','scale(1)');
      $("#hoper").next().css('display','block');
    }

    $("input[name='exhaustion[]']").change(function(){
      let ne=document.querySelectorAll('input[name="exhaustion[]"]:checked').length
      $('input[name="ne"]').val(ne);
    });

    $("input[name='madness[]']").change(function(){
      let nm1=document.querySelectorAll('input[name="madness[]"]:checked').length
      let nm2=document.querySelectorAll('input[name^="discipline"]:indeterminate').length
      $('input[name="nm"]').val(nm1+nm2);
    });

    $("input[name^='discipline']").change(function(){
        if($(this).next().val()=='d'){
          this.indeterminate=true;
          $(this).next().val('m')
          $('input[name="nd"]').val(parseInt($('input[name="nd"]').val())-1)
          $('input[name="nm"]').val(parseInt($('input[name="nm"]').val())+1)
        }
        else if ($(this).next().val()=='m') {
          this.checked=false;
          $(this).next().val('0')
          $('input[name="nm"]').val(parseInt($('input[name="nm"]').val())-1)
        }
        else if ($(this).next().val()=='0') {
          this.checked=true;
          $(this).next().val('d')
          $('input[name="nd"]').val(parseInt($('input[name="nd"]').val())+1)
        }
    })

    $("input[name^='fof']").change(function(){
        if($(this).next().val()=='1'){
          this.indeterminate=true;
          $(this).next().val('2')
        }
        else if ($(this).next().val()=='2') {
          this.checked=false;
          $(this).next().val('0')
        }
        else if ($(this).next().val()=='0') {
          this.checked=true;
          $(this).next().val('1')
        }
    })

    function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $('#portrait-img').attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }


  $('.closer').click(function(){
    $(this).prev().css('transform','scale(0)');
    $(this).css('display','none');
  });

  $('.showimg > .closer').click(function(){
    $(this).prev().css('transform','scale(1)');
    $('.showimg').css('transform','scale(0)');
  })

  var interval=setInterval(function () {
    let post;
    let data;
    fetch('./gm/player.JSON', {
      headers : {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    }).then(data => {
      return data.json();
    })
    .then(post => {
      if (post.state==1){
        if (document.getElementById('musicplayer').src=="" || document.querySelector('#musicplayer').attributes.src.nodeValue!=`music/${post.song}`){
          document.getElementById('musicplayer').src=`music/${post.song}`;
        }
        if (document.getElementById('musicplayer').paused) {
          //document.getElementById('musicplayer').currentTime = 0
          document.getElementById('musicplayer').play();
        }
      }
      else if (post.state==0) {
        document.getElementById('musicplayer').pause();
      }
    });

    let post2;
    let data2;
    fetch('./gm/shower.JSON').then(data2 => {
      return data2.json();
    })
    .then(post2 => {
      if (post2.state==1){
        console.log(post2);
        if (document.getElementById('shower').src=="" || document.querySelector('#shower').attributes.src.nodeValue!=`img/${post2.img}`){
          document.getElementById('shower').src=`img/${post2.img}`;
          $('.showimg').css('transform','scale(1)');
          $('.showimg > .closer').css('display','block');
        }
      }
      else if (post2.state==0) {
      }
    });


    let post3;
    let data3;
    var date = new Date(new Date().toLocaleString("en-US",{timezone: "US/Eastern"}));
    var h = date.getHours(); // 0 - 23
    var m = date.getMinutes(); // 0 - 59
    var s = date.getSeconds(); // 0 - 59
    if(h == 0){
      h = 12;
    }

    if(h > 12){
      h = h - 12;
    }
    fetch('./gm/timer.JSON').then(data2 => {
      return data2.json();
    })
    .then(post3 => {
      if (post3.state==1){
        h=h-post3.h;
        m=m-post3.m;
        s=s-post3.s;
        if (s<0) {
          m=m-1
          s=60+s;
        }
        if (h<10) {
          h="0"+h
        }
        if (m<10) {
          m="0"+m
        }
        if (s<10) {
          s="0"+s
        }
        $('.timer').html(`${h}:${m}:${s}`);
      }
      else {
        $('.timer').html("00:00:00")
      }

    }
  )

}, 100);

  </script>
</html>


<?php
if (isset($_POST['submitform'])){
  $data=array();
  // Column 1 is the primary key
  $data[0]=$_POST['pname']; // column 2
  $data[1]=$_POST['name']; // column 3

  $ne=count($_POST['exhaustion']);
  $nm=count($_POST['madness']);
  $m2=array_count_values($_POST['discipline'][1]);
  $m3=(isset($m2['m']))?$m2['m']:0;
  $nm=$nm+$m3;
  $nd=(isset($m2['d']))?$m2['d']:0;
  $data[2]="${ne},${nm},${nd}"; // column 4

  $data[3]=join("",$_POST['fof'][1]); // column 5
  $data[4]=$_POST['etalent']; // column 6
  $data[5]=$_POST['etalent-desc']; // column 7

  $data[6]=$_POST['mtalent']; // column 8
  $data[7]=$_POST['mtalent-desc']; // column 9


  if($_FILES["portrait"]["error"] == 4) {
      $data[8]=$c[9]; // column 10, no upload
  }else{
    $namer=preg_replace('/\s*/', '',$data[0]);
    $namer=strtolower($namer);
    $target_dir = "uploads/{$namer}_";
    $target_file = $target_dir . basename($_FILES["portrait"]["name"]);
    $uploadOk = 0;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["portrait"]["tmp_name"]);
    if($check !== false) {
      $uploadOk = 1;
    } else {
      echo "<script>alert('File is not an image')</script>";
      $uploadOk = 0;
    }
    if (file_exists($target_file)) {
      echo "<script>alert('File already exists. Please use a different filename')</script>";
      $uploadOk = 0;
    }
    if ($uploadOk == 0) {
    } else {
      if (move_uploaded_file($_FILES["portrait"]["tmp_name"], $target_file)) {
        if ($c[9]!="img/default.png"){
          unlink($c[9]);
        }
      } else {
      }
    }

    $data[8]=$target_file; // column 10
  }

  $data[9]=$_POST['w-happened']; // column 11
  $data[10]=$_POST['w-awake']; // coolumn 12
  $data[11]=$_POST['w-surface']; // column 13
  $data[12]=$_POST['w-beneath']; // column 14
  $data[13]=$_POST['w-path']; // column 15

  $data[14]=join("|",$_POST['inventory']); // column 16


  // columns 17 - 22
  foreach ($_POST['scars'] as $key => $value) {
    $data[15+$key]=$value;
  }

  $data[21]=$_POST['notes']; // column 23
  $data[22]=$token;

  $stmt=$conn->prepare(
    "UPDATE chars SET player=?, charname=?, edm=?, fof=?,
    exhaustion=?, exhaustion_description=?, madness=?, madness_description=?,
    portrait=?, happened=?, awake=?, surface=?, beneath=?, goal=?, inventory=?,
    scars1=?, scars2=?, scars3=?, scars4=?, scars5=?, scars6=?, notes=? WHERE
    uid=?
  ");
  $stmt->execute($data);
  ?>
  <script type="text/javascript">
  window.location = "sheet";
  </script>
  <?php
}
?>
