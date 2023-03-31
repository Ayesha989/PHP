<?php
//include_once "dbconnect.php";
include_once "function.php";
//print_r($_POST);

// sanitize will check which inputs allowed and if anything other than that then it should replace with space.
//isValid function in is impelemented to validate the inputs like which pattern is allowed in inputs.

function sanitize_name($name){
$name_pattern = "/[^a-zA-Z \/+*]/";
$name = preg_replace($name_pattern, "", $name);
return $name;
}

function sanitize_onlyNumber($ph_num){
  $phone_pattern = "/[^0-9 ]/";
  $ph_num = preg_replace($phone_pattern, "", $ph_num);
  return $ph_num;
}

function isValidName($name){
  $pattern = "/[a-zA-Z \/+]{3,100}/";
  if (preg_match($pattern, $name)){
       return true;
    }
    else {
       return false;
    }   
 }

 function isValidPhoneNumber($ph_num){
  $pattern = "/[0-9]/";
  if (preg_match($pattern, $ph_num)){
       return true;
    }
    else {
       return false;
    }   
 }

 function isValidPin($pincode){
  $pattern = "/[0-9]{6}/";
  if (preg_match($pattern, $pincode)){
       return true;
    }
    else {
       return false;
    }   
 }

 function isValidEmail($email){
  $pattern = "/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/";
  if (preg_match($pattern, $email)){
       return true;
    }
    else {
       return false;
    }   
 }


if (isset($_POST['submit'])){
  //print_r($_POST);
  $input = array();
  $validated_input = array();
  $arrTitle = array("Miss.","Mrs.");
  $errMsg = "";
  $PageisValid = true;
  $title = trim($_POST['title']);
  $name = trim($_POST['name']);
  $phone_num = trim($_POST['phone_number']);
  $phone_num = filter_var($phone_num, FILTER_VALIDATE_INT);
  $email = trim($_POST['email']);
  $confirm_email = trim($_POST['confirm_email']);
  //$email = filter_var($phone_num, FILTER_VALIDATE_EMAIL);
  $pincode = trim($_POST['pincode']);

  // echo "before sanitize => ".$name."<br>";
  // echo "before sanitize => ".$phone_num."<br>";
  $sanitize_name = sanitize_name($name);
  $sanitize_ph_num = sanitize_onlyNumber($phone_num);
  $sanitize_email = $email;
  $sanitize_conf_email = $confirm_email;
  $sanitize_pincode = sanitize_onlyNumber($pincode);
  
  //$name_pattern = "/[^a-zA-Z0-9 \/+]/";
  //$sanitize_name = preg_replace($name_pattern, "", $name);
  array_push($input,$title,$sanitize_name,$sanitize_ph_num,$sanitize_email,$sanitize_conf_email,$sanitize_pincode);
  print_r($input);

  if (strcmp($title, "0") == 0 || !in_array($title, $arrTitle)) {
    $PageisValid = false;
    $errMsg = "- Select the Title!" . "<BR/>";
  } else
    $title = trim($title);
  
  if (!isValidName($sanitize_name) || $sanitize_name == "") {
    $PageisValid = false;
    $errMsg .= "First Name Should be valid Alphabet value." . "<BR/>";
    //echo $errMsg;
  } else {
    $sanitize_name = trim($sanitize_name);
    echo "name=> ".$sanitize_name;
  }
  // phone number validation
  if (!isValidPhoneNumber($sanitize_ph_num) || $sanitize_ph_num == "") {
    $PageisValid = false;
    $errMsg .= "Phone number Should be valid number." . "<BR/>";
   //echo $errMsg;
  } else {
    $sanitize_ph_num = trim($sanitize_ph_num);
    echo " Phone_num=> ".$sanitize_ph_num;
  }

  // Email validation
  if (!isValidEmail($sanitize_email) || $sanitize_email == "") {
    $PageisValid = false;
    $errMsg .= "Enter valid e-mail value." . "<BR/>";
    //echo $errMsg;
  } else {
    $sanitize_email = trim($sanitize_email);
    echo " Email => ".$sanitize_email;
  }
  // Confirm Email validation
  if (!isValidEmail($sanitize_conf_email) || $sanitize_conf_email == "") {
    $PageisValid = false;
    $errMsg .= "Enter valid confirm  e-mail value." . "<BR/>";
    //echo $errMsg;
  } else {
    $sanitize_conf_email = trim($sanitize_conf_email);
    echo " Confirm Email => ".$sanitize_conf_email;
  }
  if ($sanitize_email != $sanitize_conf_email) {
      $PageisValid = false;
      $errMsg .= "Confirmation e-mail and E-mail is not same.<BR/>";
      //echo $errMsg;
  } else {
      $sanitize_conf_email = trim($sanitize_conf_email);
      echo " Confirm Email => ".$sanitize_conf_email;
  } 

    // phone number validation
    if (!isValidPin($sanitize_pincode) || $sanitize_pincode == "") {
      $PageisValid = false;
      $errMsg .= "Pincode Should be valid number." . "<BR/>";
      //echo $errMsg;
    } else {
      $sanitize_pincode = trim($sanitize_pincode);
      echo " Pin => ".$sanitize_pincode;
      echo "valid= >".$PageisValid ;
    }





  echo "<br> validated inputs <br>";
  array_push($validated_input,$title,$sanitize_name,$sanitize_ph_num,$sanitize_email,$sanitize_conf_email,$sanitize_pincode);
  print_r($validated_input);
  echo "PageisValid".$PageisValid;
  if ($PageisValid) {
    //echo "All validation sanitization done love";

    //To avoid duplication of data

    $dplct_sql = "SELECT user_phone_num from user_detail where user_phone_num = '".$sanitize_ph_num."' ";
    //$dplct_q = $mysqli -> query($dplct_sql);
    //echo "Affected rows: " . $mysqli -> affected_rows;
    // Return the number of rows in result set
    $rowcount = run_mysql_query($dplct_sql);
    echo "query row count => ".$rowcount."<br>";

    if($rowcount > 0){

      $error_m = "DUPLICATE DATA NOT ALLOWED";
      errorlog($error_m);

    } else {
    //$insert_query = "insert into user_info (user_name,user_phone_num,user_email,user_pincode) values ('".$sanitize_name."',$sanitize_ph_num,'".$sanitize_email."',$sanitize_pincode)";
    
    $insert_query = "insert into user_detail (user_name,user_phone_num,user_email,user_pincode) values ('".$sanitize_name."',$sanitize_ph_num,'".$sanitize_email."',$sanitize_pincode)";
    $insert_q = $mysqli -> query($insert_query);
    if ($insert_q){
      echo "Error: " . $insert_query . "<br>" . $mysqli -> error;
      echo "it works";
      echo "<script type='text/javascript'>alert('submitted successfully!')</script>";
    }else{
      echo "Error: " . $insert_query . "<br>" . $mysqli -> error; // changed);
    }
    // Print auto-generated id
  echo "New record has id: " . $mysqli -> insert_id;

    }
  } else {
    echo "If error";
  }
  //echo "sanitize name=>".$sanitize_name;
}else{
  echo "error";
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    
  <div class="container">
        <div class=" text-center mt-5 ">

            <h1 >Bootstrap Contact Form</h1>
 
        </div>
        <?php 
              echo "ERROR =>".$PageisValid;
              if($PageisValid == false){
              echo $errMsg;
              }
              ?>
    
    <div class="row ">
      <div class="col-lg-7 mx-auto">
        <div class="card mt-2 mx-auto p-4 bg-light">
            <div class="card-body bg-light">
       
            <div class = "container">
             
            <form actiob="event.php" method="post" id="contact-form" role="form">

            <div class="controls">

              <div class="row mb-2">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="form_name">Title *</label>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                            <select name="title" id="title">
                            <option value="select">Select </option>
                              <option value="Miss.">Miss. </option>
                              <option value="Mrs.">Mrs. </option>
                            </select>
                          </div>
                      </div>
                </div>

              <div class="row mb-2">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="form_name">Name *</label>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <input id="form_name" type="text" name="name" class="form-control" placeholder="Please enter your lastname *" >
                              <label for="form_name">(Min 3 and Max 100 Characters)</label>
                          </div>
                      </div>
              </div>
              <div class="row mb-2">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="form_number">Phone number *</label>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <input id="form_phone_number" type="text" name="phone_number" class="form-control" placeholder="Please enter your phone number *" required="required" data-error="Lastname is required.">
                              <label for="form_name">(Enter your 10 digit Mobile Number)</label>
                          </div>
                      </div>
              </div>
              <div class="row mb-2">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="form_email">Enter Your e-mail *</label>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <input id="form_email" type="text" name="email" class="form-control" placeholder="Please enter your Email *">
                              <!--<input id="form_email" type="text" name="email" class="form-control" placeholder="Please enter your Email *" required="required" data-error="Lastname is required.">-->
                          </div>
                      </div>
              </div>
              <div class="row mb-2">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="form_name">Confirm Your e-mail *</label>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <input id="form_email" type="text" name="confirm_email" class="form-control" placeholder="Please confirm your email *">
                          </div>
                      </div>
              </div>
              <div class="row mb-2">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="form_pincode">Pincode *</label>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <input id="form_pincode" type="text" name="pincode" class="form-control" placeholder="Please enter your pincode *" >
                          </div>
                      </div>
              </div>
              <div class="row">
                <div class="col-md-12"> 
                        <input type="submit" name="submit" class="btn btn-success btn-send  pt-2 btn-block" value="Submit" >
                </div>
          
              </div>


            </div>
         </form>
        </div>
            </div>


    </div>
        <!-- /.8 -->

    </div>
    <!-- /.row-->

</div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>