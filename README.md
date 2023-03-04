<h1 align="center">
  <br>
  <a href="https://github.com/mkdirlove/PHP-Exercise"><img src="https://github.com/mkdirlove/PHP-Exercise/blob/main/task.jpg" width="300" height="400" alt="PHP-Exercise"></a>
  <br>
  A simple PHP exercise that I solved.
  <br>
</h1>

### Solution

1.) Every applicant that uploads their file should have a applicant number provided by your system
```php
$_SESSION['id'] = uniqid();
$applicantNumber = $_SESSION['id'];
```
2.) Applicants can only upload a PDF file with a 10mb max size
```php
    // Check file size
    if ($_FILES["resume"]["size"] > 10000000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }
```
3.) No applicant should upload their PDF more  than once
```php
    // Check if file already exists
    if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
    }
```
4.) Your system should accept the applicant's basic contact information(Name, address, contact number) and save it to a txt file
```php
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
```
5.) The txt file name should be: surnameofapplicant_applicantnumber.txt
```php
 $filename = $surname . '_' . $applicantNumber . '.txt'; // create filename for contact information text file
```
6.) Error trapping is a must
```php
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
    }
```

7.) Lastly, in your admin page. Admin should just enter the file name 
(file extension not included) of the accepted applicants and when the 
admin click the 'Accepted' button there should be a 'Passed' word appended 
at the start of the applicants txt file. And there should be a 'Failed' button 
that also appends 'Failed' if the applicant didn't met the expectation of our admin.
```php
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
```
