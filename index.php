<?php
include "database.php";
error_reporting(E_ERROR | E_PARSE);

$obj = new Database();
$obj->_construct();

session_start();
?>

<!doctype html>
<html>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800|Nova+Round|Old+Standard+TT:400,700" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="template/css/animate.css">
  <link rel="stylesheet" href="template/css/main.css">

  <title>Aquera Frequency Detector</title>

  <!-- <link href='http://fonts.googleapis.com/css?family=Alike' rel='stylesheet' type='text/css'> -->
  <style>
    body {
      font: 14pt 'Alike', sans-serif;
    }

    #note {
      font-size: 164px;
    }

    .droptarget {
      background-color: #348781
    }

    div.confident {
      color: black;
    }

    div.vague {
      color: lightgrey;
    }

    #note {
      display: inline-block;
      height: 180px;
      text-align: left;
    }

    #detector {
      width: 300px;
      height: 300px;
      border: 4px solid gray;
      border-radius: 8px;
      text-align: center;
      padding-top: 10px;
    }

    #output {
      width: 300px;
      height: 42px;
    }

    #flat {
      display: none;
    }

    #sharp {
      display: none;
    }

    .flat #flat {
      display: inline;
    }

    .sharp #sharp {
      display: inline;
    }
  </style>
</head>

<body>
  <?php include "template/header.php"; ?>

  <script src="pitchdetect.js"></script>

  <?php if (isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["id"]) : ?>

    <section id="blog-single" class="container">
      <div class="row">
        <div class="col">
          <p><button class="btn btn-default" onclick="startPitchDetect();" id="buttonStart">Start accept the Microphone</button>
          <button class="btn btn-default" id="buttonStop">Stop accept the Microphone</button></p>
          <p><button class="btn btn-default" onclick="this.innerText = togglePlayback()">use demo audio</button>
            <button class="btn btn-default" onclick="toggleLiveInput()">use live input</button>
            <button class="btn btn-default" onclick="toggleOscillator()">use oscillator</button>
            <!--<button onclick="updatePitch(0);">sample</button>-->
          </p>

          <div id="detector" class="vague" style="HEIGHT: 318px; WIDTH: 361px">
            <div class="pitch"><span id="pitch">--</span> - Hz</div>
            <div class="note"><span id="note">--</span></div>

            <canvas id="output" width="300" height="42"></canvas>
            <div id="detune"><span id="detune_amt">--</span><span id="flat">cents ♭</span><span id="sharp">cents ♯</span></div>
          </div>
          <canvas id="waveform" width="512" height="256"></canvas>
          <!-- just used for debugging
          <canvas id="waveform" width="512" height="256"></canvas>
          -->
        </div>
        <div class="col">
          <label>Frequency List</label>
          <div id="createElement"></div>
          <select id="frq_list" name="frq_list" class="form-control" multiple="false" style="HEIGHT: 176px; WIDTH: 369px">
            <?php
            foreach ($files as $key => $file) {
              echo "<option value='" . $file . "'>" . $file . "</option>";
            }
            ?>
          </select>
            <br>

          <form action="download.php" method="post">
            <?php
              $sql = "SELECT * FROM frequencies where user_id=".$_SESSION['id'];
              $query = $obj->getConn()->prepare($sql);
              $query -> execute();
              $records = $query -> fetchAll(PDO::FETCH_OBJ);
            ?>
            <label>Each 1444 Record List</label>
            <select id="file_list" name="file_list" class="form-control" multiple="false" style="HEIGHT: 176px; WIDTH: 369px">
              <?php
              foreach ($records as $key => $record) {
                echo "<option value='" . $record->id . "'>" . $record->created_at . ".txt</option>";
              }
              ?>
            </select>

            <button type="submit" name="submit" class="btn btn-info" id="saveFile"> Download File </button>
          </form>
        </div>
      </div>
    </section>

  <?php else : ?>
    <?php
    class users extends Database
    {
      protected $conn;
      function _construct()
      {
        parent::_construct();
        $this->conn = parent::getConn();
      }
      function getConn()
      {
        return $this->conn;
      }

      function login()
      {
        try {
          $activation_code = $_POST["activation_code"];

          $sql = 'SELECT * FROM users WHERE activation_code="' . $activation_code . '" AND is_active = 1';
          $sorgu = $this->conn->prepare($sql);
          $sorgu->execute();
          $save = $sorgu->fetchAll(PDO::FETCH_ASSOC);
          if ($sorgu->rowCount() > 0) {
            foreach ($save as $k) {
              $_SESSION['id'] = $k['id'];
              $_SESSION['username'] = $k['username'];
              $_SESSION['password'] = $k['password'];
              $_SESSION['activation_code'] = $k['activation_code'];
            }
          } else {
            echo '<p class="hata">Failed! Please check the activation_code!</p>';
          }
          if (isset($_SESSION["id"])) {
            header("Location: index.php");
          }
        } catch (Exception $e) {
          echo 'This user doesn\'t exist: ' . $e;
        }
      }
    }
    $obj = new users();
    $obj->_construct();
    if (isset($_POST["login"])) {
      $obj->login();
    }

    ?>
    <div class="form" style="max-width: 50%; margin:auto; margin-top:8%;">
      <form action="" class="contact-form frm1" method="post">
        <ul class="list-unstyled">
          <li class="row">
            <div class="col-sm-12 col-md-6">
              <input required type="password" name="activation_code" class="form-control" placeholder="Activation Code">
            </div>
            <p class="hata"></p>
          </li>
          <br>
          <li class="row form-group">
            <div class="col-sm-3 col-md-4">
              <button type="submit" name="login" class="btn btn-default">Login</button>
            </div>
          </li>
        </ul>
      </form>

      <br>
    </div>
  <?php endif ?>

</body>

</html>