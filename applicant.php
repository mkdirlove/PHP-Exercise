
<?php
session_start(); //declare you are starting a session
$_SESSION['id'] = uniqid(); //Assign a value to the id session

if(isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
    $applicantNumber = $_SESSION['id']; // generate unique applicant number
    $target_dir = "resumes/";
    $target_file = $target_dir . basename($_FILES["resume"]["name"]);
    $uploadOk = 1;
    $pdfFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $target_dir = "resumes/";

    // Check if "resumes" folder exists, and create it if not
    if (!file_exists($target_dir)) {
        mkdir($target_dir);
    }
      
    // Check if file already exists
    if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
    }
  
    // Check file size
    if ($_FILES["resume"]["size"] > 10000000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }
  
    // Allow only PDF files
    if($pdfFileType != "pdf") {
      echo "Sorry, only PDF files are allowed.";
      $uploadOk = 0;
    }
  
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars(basename( $_FILES["resume"]["name"])). " has been uploaded.";
        // save contact information to text file
        $surname = $_POST['last_name']; // get surname from last name field
        $contact = $_POST['contact_number']; // get contact number
        $address = $_POST['address']; // get address
        $filename = $surname . '_' . $applicantNumber . '.txt'; // create filename for contact information text file
        $file = fopen($filename, 'w');
        fwrite($file, "Status: Not Reviewed\n"); // add initial status
        fwrite($file, "Name: {$_POST['first_name']} {$_POST['last_name']}\n"); // concatenate first and last names and add to text file
        fwrite($file, "Contact: {$contact}\n");
        fwrite($file, "Address: {$address}\n");
        fclose($file);
        
        echo "\nResume uploaded successfully. Your applicant number is {$applicantNumber}.";
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>HRD Applicant Verification System - Applicant Page</title>
<style>
  body {
	background-color: #4158D0;
  }

  form {
    width: 400px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    font-family: Arial, sans-serif;
  }

  h1 {
      margin-top: 0;
      text-align: center;
      color: #000;
    }

  label {
    display: inline-block;
    width: 360px;
    font-weight: bold;
    margin-bottom: 5px;
  }
  
  input[type=text], input[type=file] {
    width: 100%;
    padding: 5px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 3px;
    font-size: 16px;
  }
  
  input[type="text"] {
      width: 100%;
      padding: 8px;
      border-radius: 5px;
      border: 1px solid #ccc;
      box-sizing: border-box;
      font-size: 16px;
      margin-bottom: 20px;
    }
    input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }
    input[type="submit"]:hover {
      background-color: #3e8e41;
    }
</style>
</head>
<body>
<h1>HRD Applicant Verification System - Applicant Page</h1>
<form method="POST" enctype="multipart/form-data">
  <label for="first_name">First Name:</label>
  <input type="text" name="first_name" id="first_name" required>
  <br>
  <label for="last_name">Last Name:</label>
  <input type="text" name="last_name" id="last_name" required>
  <br>
  <label for="address">Address:</label>
  <input type="text" name="address" id="address" required>
  <br>
  <label for="contact_number">Contact Number:</label>
  <input type="text" name="contact_number" id="contact_number" required>
  <br>
  <label for="resume">Upload your resume/CV (PDF only, max 10MB):</label>
  <input type="file" name="resume" id="resume" accept="application/pdf" max-size="10485760">
  <br>
  <input type="submit" name="submit" value="Submit">
</form>

</body>
</html>