<!DOCTYPE html>
<html>
<head>
  <title>HRD Applicant Verification System - Admin Page</title>
  <style>
    body {
      background-color: #4158D0;
      font-family: Arial, sans-serif;
      font-size: 16px;
      line-height: 1.5;
      color: #333;
    }
    h1 {
      margin-top: 0;
      text-align: center;
      color: #000;
    }
    form {
      max-width: 500px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    label {
      display: inline-block;
      width: 120px;
      margin-bottom: 10px;
      font-weight: bold;
      color: #000;
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
    .message {
      max-width: 500px;
      margin: 0 auto;
      padding: 10px;
      border-radius: 5px;
      margin-top: 20px;
    }
    .success {
      background-color: #c9f9c9;
      color: #4CAF50;
      border: 1px solid #4CAF50;
    }
    .error {
      background-color: #ffe0e0;
      color: #f44336;
      border: 1px solid #f44336;
    }
  </style>
</head>
<body>
  <h1>HRD Applicant Verification System - Admin Page</h1>
  <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="filename">File name:</label>
    <input type="text" id="filename" name="filename" required>
    <input type="submit" name="accept" value="Accept">
    <input type="submit" name="reject" value="Decline">
  </form>

<?php
session_start();
//echo $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['accept'])) {
    $filename = $_GET['filename'] . '.pdf';
    $status = "Passed";
    if (file_exists('resumes/' . $filename)) {
      $file = fopen('resumes/' . $filename, 'a');
      fwrite($file, $status . "\n");
      fclose($file);
      
      // Check if the $_SESSION['id'] is matched in filename and append status
      $txt_files = glob("*.txt");
      foreach ($txt_files as $txt_file) {
        if (strpos($txt_file, $_SESSION['id']) !== false) {
          $output_file = file($txt_file);
          $output_file[0] = "Status: " . ucfirst($status) . "\n";
          file_put_contents($txt_file, implode("", $output_file));
        }
      }
      echo '<div class="message success">Applicant accepted.</div>';
    } else {
      echo '<div class="message error">File does not exist.</div>';
    }
  } elseif (isset($_GET['reject'])) {
    $filename = $_GET['filename'] . '.pdf';
    $status = "Failed";
    if (file_exists('resumes/' . $filename)) {
      $file = fopen('resumes/' . $filename, 'a');
      fwrite($file, $status . "\n");
      fclose($file);
      
      // Check if the $_SESSION['id'] is matched in filename and append status
      $txt_files = glob("*.txt");
      foreach ($txt_files as $txt_file) {
        if (strpos($txt_file, $_SESSION['id']) !== false) {
          $output_file = file($txt_file);
          $output_file[0] = "Status: " . ucfirst($status) . "\n";
          file_put_contents($txt_file, implode("", $output_file));
        }
      }
      echo '<div class="message error">Applicant rejected.</div>';
    } else {
      echo '<div class="message error">File does not exist.</div>';
    }
  }
}
?>


</body>
</html>