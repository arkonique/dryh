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
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <script src="https://kit.fontawesome.com/0bbd382f3c.js" crossorigin="anonymous"></script>
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/screen.css">
    <title>Don't Rest Your Head GM screen</title>
  </head>
  <body>
    <h1>Game Master's Screen</h1>
    <?php
      $stmt=$conn->prepare("SELECT * FROM chars");
      $stmt->execute();
      $result=$stmt->get_result();
      if ($result->num_rows!=0){
        ?>
        <table id="players">
          <thead>
            <th>Player</th>
            <th>Character</th>
            <th>E,M,D</th>
            <th>Fight or Flight</th>
            <th>Exhaustion Talent</th>
            <th>Madness Talent</th>
            <th>What happened</th>
            <th>Why awake</th>
            <th>Surface</th>
            <th>Beneath</th>
            <th>Path</th>
            <th>Inventory</th>
          </thead>
        <?php
        while ($row = $result->fetch_assoc()){
          $players[$row['uid']]=$row['player'];
          echo "<tr>
          <td>{$row['player']}</td>
          <td>{$row['charname']}</td>
          <td>{$row['edm']}</td>
          <td>{$row['fof']}</td>
          <td>{$row['exhaustion']}</td>
          <td>{$row['madness']}</td>
          <td><span class='q-h'><p>{$row['happened']}</p><input class='expand' type='checkbox'></span></td>
          <td><span class='q-h'><p>{$row['awake']}</p><input class='expand' type='checkbox'></span></td>
          <td><span class='q-h'><p>{$row['surface']}</p><input class='expand' type='checkbox'></span></td>
          <td><span class='q-h'><p>{$row['beneath']}</p><input class='expand' type='checkbox'></span></td>
          <td><span class='q-h'><p>{$row['goal']}</p><input class='expand' type='checkbox'></span></td>
          <td>{$row['inventory']}</td>
          </tr>";
        }
      }

     ?>
   </table>

    <iframe src="despair" width="700" height="500" id="despair"></iframe>
    <div class="pains">
      <h2>Roll pain: </h2>
      <input type="range" min=0 max=12 name="pain" id="pain" value=0>
      <p id="pain-p">0</p>
      <p id="painroll"><i class="fa-solid fa-dice-six"></i></p>
      <button type="button" name="roller" onclick="painroller()">Roll</button>
    </div>

    <iframe src="rolls" onload='javascript:(function(o){o.style.height=o.contentWindow.document.body.scrollHeight+30+"px";}(this));' style="height:200px;width:100%;border:none;overflow:hidden; margin: 20px 0" id='rolls'></iframe>


    <h2>Enemies</h2>
    <table id=enemies"">
    <thead>
      <tr>
        <th>Enemy</th>
        <th>Pain Rating</th>
        <th>Notes</th>
        <th>Bonuses</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Officer Tock</td>
        <td>6</td>
        <td>Stopwatch for a face, clock had as a weapon</td>
        <td>+2 - district 13, 13th hour, warrant</td>
      </tr>
      <tr>
        <td>Clockwork lieutenant</td>
        <td>1</td>
        <td>Can be in groups</td>
        <td>0</td>
      </tr>
      <tr>
        <td>Tacks man</td>
        <td>8</td>
        <td>Nightmare</td>
        <td>0, doesn't leave district 13</td>
      </tr>
      <tr>
        <td>Tacks man's pins</td>
        <td>5</td>
        <td>Can steal something if it pierces you</td>
        <td>0</td>
      </tr>
      <tr>
        <td>PIn heads</td>
        <td>3</td>
        <td>24 hour news organization, works for tacks man</td>
        <td>0</td>
      </tr>
      <tr>
        <td>Needle nose bloodhounds</td>
        <td>3</td>
        <td>Can use thread to trap victims</td>
        <td>+2 search, +1 trap</td>
      </tr>
      <tr>
        <td>Mother When</td>
        <td>12</td>
        <td>Headmaster of high school, only lets girls graduate, virtues of jealousy, malice, spite</td>
        <td>0</td>
      </tr>
      <tr>
        <td>Ladies in hating</td>
        <td>1 or 2</td>
        <td>Graduates of high school</td>
        <td>+3 each additional two ladies</td>
      </tr>
      <tr>
        <td>Paper boys</td>
        <td>2</td>
        <td>Whatever they publish is true</td>
        <td>+8 for their stories</td>
      </tr>
      <tr>
        <td>Wax king</td>
        <td>10</td>
        <td>Lives in the wax kingdom in the warrens underground, uses wax to cover people to make slaves, nightmares stay away, not violent</td>
        <td>0</td>
      </tr>
      <tr>
        <td>Blind Knights</td>
        <td>4</td>
        <td>Maximum group of 2</td>
        <td>+2 for every additional knight</td>
      </tr>
      <tr>
        <td>Smothered folk</td>
        <td>1</td>
        <td>Were normal people that got covered by wax, no identity, horde tactics</td>
        <td>0</td>
      </tr>
    </tbody>
    </table>


    <div class="hide-wrapper" id="mu">
      <iframe src="musicupload" class="hideframe"></iframe>
      <span class="closer"><i class="fa-solid fa-circle-xmark"></i></span>
    </div>
    <div class="hide-wrapper" id="iu">
      <iframe src="imageupload" class="hideframe"></iframe>
      <span class="closer"><i class="fa-solid fa-circle-xmark"></i></span>
    </div>
    <div class="hide-wrapper" id="pl">
      <iframe src="player" id="player" class="hideframe"></iframe>
      <span class="closer"><i class="fa-solid fa-circle-xmark"></i></span>
    </div>
    <div class="hide-wrapper" id="ga">
      <iframe src="gallery" id="gallery" class="hideframe"></iframe>
      <span class="closer"><i class="fa-solid fa-circle-xmark"></i></span>
    </div>

    <nav>
      <button type="button" name="refresher" onclick="refreshPage()"><i class="fa-solid fa-rotate-right"></i></button>
      <button type="button" name="umusic" onclick="reloadIFrame3()"><i class="fa-solid fa-music"></i></button>
      <button type="button" name="uimg" onclick="reloadIFrame4()"><i class="fa-solid fa-image"></i></button>
      <button type="button" name="play" onclick="reloadIFrame()"><i class="fa-solid fa-headphones"></i></button>
      <button type="button" name="show" onclick="reloadIFrame2()"><i class="fa-solid fa-eye"></i></button>
      <button type="button" name="timer" onclick="starttimer()"><i class="fa-solid fa-clock"></i></button>
      <a href="logout" id="logout"><i class="fa-solid fa-right-from-bracket"></i></a>
    </nav>


    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
      <script type="text/javascript">

      $('.closer').click(function () {
        $(this).parent().css('transform','scale(0)')
      })

      clicker=0;
      function starttimer(){
        var date = new Date(new Date().toLocaleString("en-US",{timezone: "US/Eastern"}));;
        var h = date.getHours(); // 0 - 23
        var m = date.getMinutes(); // 0 - 59
        var s = date.getSeconds(); // 0 - 59
        if(h == 0){
            h = 12;
        }

        if(h > 12){
            h = h - 12;
        }
        if (clicker==0) {
          clicker=1;
          $('button[name="timer"]').addClass('rotate')
        }
        else {
          clicker=0;
          $('button[name="timer"]').removeClass('rotate')
        }
        fetch(`timer.php?h=${h}&m=${m}&s=${s}&state=${clicker}`);
      }

      function reloadIFrame() {
        $("#pl").css('transform','scale(1)')
        document.getElementById("player").src=`player`;
      }

      function reloadIFrame2() {
        $("#ga").css('transform','scale(1)')
        document.getElementById("gallery").src=`gallery`;
      }

      function reloadIFrame3() {
        $("#mu").css('transform','scale(1)')
        document.getElementById("musicupload").src=`gallery`;
      }

      function reloadIFrame4() {
        $("#iu").css('transform','scale(1)')
        document.getElementById("imageupload").src=`gallery`;
      }

      var slider1 = document.getElementById("pain");
      var output1 = document.getElementById("pain-p");
      slider1.oninput = function() {
        output1.innerHTML = this.value;
      }
        function refreshPage(){
          window.location.reload();
        }
        function painroller() {
          diceobj={
            1: '<i class="fa-solid fa-dice-one"></i>',
            2: '<i class="fa-solid fa-dice-two"></i>',
            3: '<i class="fa-solid fa-dice-three"></i>',
            4: '<i class="fa-solid fa-dice-four"></i>',
            5: '<i class="fa-solid fa-dice-five"></i>',
            6: '<i class="fa-solid fa-dice-six"></i>'
          }
          n=document.querySelector('input[name="pain"]').value;
          roll=Array.from({length: n}, () => Math.floor(Math.random() * 6)+1);
          htmlstr="";
          for (var v of roll) {
            htmlstr=htmlstr+diceobj[v];
          }
          document.getElementById('painroll').innerHTML=htmlstr;
        }

        setInterval(function(){
          document.getElementById("rolls").src=`rolls`;
        },10)
      </script>
  </body>
</html>
