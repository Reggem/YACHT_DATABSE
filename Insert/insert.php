<?php
  session_start();
  require('../config.php');

  //Collect the data of the added company
  $addcompName=$addindus=$addnbrEmp=$addcity="";
  //Collect the data of the added trainee
  $addtrFname=$addtrLname=$addtrEmail=$addtrKantoor=$addtrTel=$addtrDep="";
  //Collect the data of the contact
  $addcontName=$addcontFN=$addFun=$addEmail=$addTel=$addLinkedin=$addDepartment=$addTrainee=$addLocatie=$addNote=$addStatus=$addDate="";
  $bedrijf="";


  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["traineesubmit"])){
      $addtrFname = test_input($_POST["traineeFname"]);
      $addtrLname = test_input($_POST["traineeLname"]);
      $addtrKantoor = test_input($_POST["traineeKantoor"]);
      $addtrEmail = test_input($_POST["traineeEmail"]);
      $addtrTel=test_input($_POST["traineeTel"]);
      $addtrDep=test_input($_POST["traineeDep"]);
    }

    if(isset($_POST["companysubmit"])){
      $addcompName = test_input($_POST["compname"]);
      $addindus = test_input($_POST["industry"]);
      $addnbrEmp = test_input($_POST["nbrEmp"]);
    }

    if(isset($_POST["contactsubmit"])){
      $addcontName = test_input($_POST["naam"]);
      $addcontFN = test_input($_POST["voornaam"]);
      $addFun = test_input($_POST["functie"]);
      $addEmail = test_input($_POST["email"]);
      $addTel = test_input($_POST["Telefoon"]);
      $addLinkedin = test_input($_POST["linkedin"]);
      $addDepartment=test_input($_POST["department"]);
      $bedrijf=test_input($_POST['bedrijf']);
      $addTrainee=test_input($_POST["trainee"]);
      $addLocatie=test_input($_POST["locatie"]);
      $addNote=test_input($_POST["note"]);
      $addStatus=test_input($_POST["status"]);
      $addDate=date("Y-m-d");

    }

    if(isset($_POST["inspectadd"])){
      $addcontName = test_input($_POST["naam"]);
      $addcontFN = test_input($_POST["voornaam"]);
      $addFun = test_input($_POST["functie"]);
      $addEmail = test_input($_POST["email"]);
      $addTel = test_input($_POST["Telefoon"]);
      $addLinkedin = test_input($_POST["linkedin"]);
      $addDepartment=test_input($_POST["department"]);
      $bedrijf=test_input($_POST['bedrijf']);
      $addTrainee=test_input($_POST["trainee"]);
      $addLocatie=test_input($_POST["locatie"]);
      $addNote=test_input($_POST["note"]);
      $addStatus=test_input($_POST["status"]);
    }

  }




  function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    // <!-- Connection to the database -->


    try {
      $connection=new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,$_SESSION["code"]);
      $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

      // echo "succesful connection";

    } catch (PDOException $e) {
        header("Location: ../index.php");
    }


    // Get available Kantoren
    $queryoffices="SELECT DISTINCT  Stad
    FROM kantoren ;";

    $sqloffices=$connection->prepare($queryoffices);
    $sqloffices->execute();

    $avOffices=$sqloffices->fetchall();

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Font awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/Style.css">
    <title>Insert data</title>


  </head>
  <body>
    <?php
      // echo '<pre>'.print_r($_SESSION)."</pre>";
      include("navbarinsert.php");
    ?>
    <!-- Insert the company in the database  -->


    <div class="container-fluid content">


        <div class="row mt-5">


              <?php
              include('company.php');

              //add the company

              try {

                // Format for the query
                $tab=array($addcompName, $addnbrEmp, $addindus);
                $new_tab="'".implode("', '", $tab)."'";

                // Insert the company
                $sql="INSERT INTO bedrijven(Bedrijf, AantalWerknemers, Industrie)
                 VALUES($new_tab);";



                if(isset($_POST["companysubmit"])){
                  $insert=$connection->prepare($sql);
                  $insert->execute();
                }

              } catch (PDOException $e) {
                  // echo "<div class='text-danger h6'>Echec connection</div>";
              }

              //add the trainee

              try {
                //get the id of the office
                $queryidoffices="SELECT DISTINCT  idKantoor
                FROM kantoren
                WHERE Stad='$addtrKantoor' ;";

                // echo $queryidoffices;

                $sqlidoffices=$connection->prepare($queryidoffices);
                $sqlidoffices->execute();

                $avidOffices=$sqlidoffices->fetchall();


                // Format for the query
                $tab=array($addtrFname, $addtrLname, $avidOffices[0]["idKantoor"], $addtrEmail, $addtrTel, $addtrDep);
                $new_tab="'".implode("', '", $tab)."'";

                // Insert the company
                $sql="INSERT INTO trainees(Voornaam, Naam, idKantoor, Email, Telefoon, Afdeling)
                 VALUES($new_tab);";

                // echo $sql;

                if(isset($_POST["traineesubmit"])){
                  $insert=$connection->prepare($sql);
                  $insert->execute();

                }

              } catch (PDOException $e) {
                  // echo "<div class='text-danger h6'>Echec connection</div>";
              }

              ?>

              <!-- Add the contact to the contact table -->
              <?php

                // Get available Companies
                $querycompanies="SELECT DISTINCT  b.Bedrijf
                FROM bedrijven b;";

                $sqlCompanies=$connection->prepare($querycompanies);
                $sqlCompanies->execute();

                $avCompanies=$sqlCompanies->fetchall();




                include('contact.php');


                // Format for the query
                $tab=array($addcontName, $addcontFN, $addEmail,$addTel,$addFun,$addDepartment, $addTrainee, $addLocatie, $addNote, $addStatus, $addDate);
                $new_tab="'".implode("', '", $tab)."'";


                // if(isset())
                $sqlcont="INSERT INTO contacts(Naam, Voornaam, Email,Telefoon,Functie, Afdeling,ToegevoegdDoor, Locatie, Note,Status, LastModified, Bedrijf_idBedrijf)
                 SELECT $new_tab, idCompany FROM bedrijven WHERE Bedrijf='$bedrijf';";


                //execute the query only if we click submit
                if(isset($_POST["contactsubmit"])){
                  $insert=$connection->prepare($sqlcont);
                  $insert->execute();
                }



              ?>

      </div>






      <?php
        include("../jssheets.php");
      ?>
      <script type="text/javascript" src="js.js"></script>
  </body>

</html>
