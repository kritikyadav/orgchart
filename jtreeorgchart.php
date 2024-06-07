<!DOCTYPE html>
<html>

<head>
  <title>Create Treeview with jsTree plugin and PHP</title>

  <link rel="stylesheet" href="jstreestylesheetlink.css" />
  <script><?php include "jquerylink.php" ?></script>
  <script><?php include "jstreescriptlink.php" ?></script>
</head>
<style>
  body {
    color: blue;
  }

  div#nav {
    border: none;
    display: flex;
    float: right;
  }

  #Input {
    width: 9.5rem;
  }

  @media print {
    
    body * {
      visibility: hidden;
    }

    div#nav {
      visibility: hidden;
    }

    #folder_jstree, #folder_jstree * {
      visibility: visible;
    }
    ul {
      margin-left: 20px;
    }

    li {
      list-style-type: none;
      margin: 10px;
      position: relative;
    }

    li::before {
      content: "";
      position: absolute;
      top: -14px;
      left: 0px;
      border-left: 1px solid #ccc;
      border-bottom: 1px solid #ccc;
      border-radius: 0 0 0 0px;
      width: 24px;
      height: 24px;
    }

    li::after {
      position: absolute;
      content: "";
      top: 10px;
      left: 0px;
      border-left: 1px solid #ccc;
      border-top: 1px solid #ccc;
      border-radius: 0px 0 0 0;
      width: 24px;
      height: 99.9%;
    }

    li:last-child::after {
      display: none;
    }

    li:last-child:before {
      border-radius: 0 0 0 2px;
    }
    ul>li:first-child::after {
      border-radius: 0px 0 0 0 ; 
    }
  }
</style>
<body>
  <script>
    function ClearFields() {
      document.getElementById("Input").value = "";
    }
  </script>
  <div id='nav' id="form">
    <form method="post">
      <input type="text" id="Input" name="title" placeholder='Enter Name' value='<?php if (isset($_POST['title'])) {echo trim($_POST['title']);} ?>'><br>
      <input type="submit" id="search" name="submit" class="button" value="Search" />
      <input type="submit" id="reset" class="button" onclick="ClearFields();" value="Reset" />
      <input type="button" value="Print" id="Print" onclick="window.print()" />
    </form>
  </div>

  <?php
  $dsn_common_db = array(
    'username'   =>  "devuser",
    'password'   =>  "d3v7u5s4e2R1",
    'hostspec'   =>  "QCINDSRV",
    'database'   =>  "QCDEV",
  );

  $serverName = $dsn_common_db['hostspec'];
  $uid = $dsn_common_db['username'];
  $pwd = $dsn_common_db['password'];
  $db = $dsn_common_db['database'];

  $connectionInfo = array("UID" => $uid, "PWD" => $pwd, "Database" => $db, "CharacterSet" => "UTF-8");
  $conn = sqlsrv_connect($serverName, $connectionInfo);
  if ($conn == false) {
    echo 'unable to connect...';
  }
  $sql = "SELECT * FROM orgchartusingjstree";
  $folderData = sqlsrv_query($conn, $sql);
  if ($folderData === false) {
    die(print_r(sqlsrv_errors(), true));
  }
  $folders_arr = array();
  while ($row = sqlsrv_fetch_array($folderData, SQLSRV_FETCH_ASSOC)) {
    $id = $row['id'];
    $parentid = $row['parentid'];
    $sql = "SELECT COUNT(0) as count FROM orgchartusingjstree WITH (NOLOCK) Where [parentid] = " . $id . "";
    $count = sqlsrv_query($conn, $sql);
    if ($count === false) {
      die(print_r(sqlsrv_errors(), true));
    }
    if (sqlsrv_fetch($count) === false) {
      die(print_r(sqlsrv_errors(), true));
    }
    $child = sqlsrv_get_field($count, 0);
    if ($id == '0') {
      $parentid = '#';
      $child = $child - 1;
    }
    if (isset($_POST['submit'])) {
      if (isset($_POST['title'])) {
        $sql = "SELECT TOP 1 [name] FROM orgchartusingjstree WITH (NOLOCK) Where [name] like '%" . $_POST['title'] . "%'";
        $valuegot = sqlsrv_query($conn, $sql);
        if ($valuegot === false) {
          die(print_r(sqlsrv_errors(), true));
        }
        if (sqlsrv_fetch($valuegot) === false) {
          die(print_r(sqlsrv_errors(), true));
        }
        $searchname = sqlsrv_get_field($valuegot, 0);
        $selected = false;
        $opened = false;
        if ($searchname == 'IPA IPA') {
          $selected = false;
          $opened = true;
        } elseif ($row['name'] == $searchname) {
          $selected = true;
          $opened = true;
        }
      }
    } else {
      $selected = false;
      $opened = true;
    }

    if ($row['gender'] == 'M') {
    } elseif ($row['gender'] == 'F') {
    } elseif ($row['gender'] == 'O') {
    }
    if ($child != '0') {
      $folders_arr[] = array(
        "id" => $row['id'],
        "parent" => $parentid,
        "text" => $row['name'] . ' (' . $child . ')',
        "state" => array("selected" => $selected, "opened" => $opened)
      );
    } else {
      $folders_arr[] = array(
        "id" => $row['id'],
        "parent" => $parentid,
        "text" => $row['name'],
        "state" => array("selected" => $selected, "opened" => $opened)
      );
    }
  }

  ?>
  <!-- Initialize jsTree -->
  <div id="folder_jstree"></div>

  <!-- Store folder list in JSON format -->
  <textarea style='display:none' id='txt_folderjsondata'><?= json_encode($folders_arr) ?></textarea>
  <script>
    $(document).ready(function() {
      var folder_jsondata = JSON.parse($('#txt_folderjsondata').val());
      $('#folder_jstree').jstree({
        'core': {
          'data': folder_jsondata,
          'multiple': false,
        },
        "types": {
          "default": {
            "icon": false
          },
          "file": {
            "icon": false
          }
        },
        plugins: ["types"]
      });
    });
  </script>
</body>
</html>