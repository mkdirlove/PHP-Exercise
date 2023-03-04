<h1 align="center">
  <br>
  <a href="https://github.com/mkdirlove/PHP-Exercise"><img src="https://github.com/mkdirlove/PHP-Exercise/blob/main/task.jpg" width="300" height="400" alt="PHP-Exercise"></a>
  <br>
  A simple PHP exercise that I solved.
  <br>
</h1>

### Problem and Solution

1.) Every applicant that uploads their file should have a applicant number provided by your system
```
Solution: I used uniqid(); to generate an applicant number.
```
2.) Applicants can only upload a PDF file with a 10mb max size
```
Solution: I used an if statement to check the file size if it's greater than 10MB.
```
3.) No applicant should upload their PDF more  than once
```
Solution: I used an if statement to check if the file is exisiting in the /resumes folder.
```
4.) Your system should accept the applicant's basic contact information(Name, address, contact number) and save it to a txt file
```
Solution: I used an html form that has an input field and uploader and use a fwrite() function to save the basic info in a txt file.
```
5.) The txt file name should be: surnameofapplicant_applicantnumber.txt
```
Solution: I used this "$filename = $surname . '_' . $applicantNumber . '.txt';" to generate a text file that has a filename surnameofapplicant_applicantnumber.txt
```
6.) Error trapping is a must
```
Solution: I used try and catch to handle exception.
```
Example:
```php
try {
    // Code that might throw an exception
    $result = 10 / 0; // This will throw a division by zero exception
} catch (Exception $e) {
    // Handle the exception
    echo "An error occurred: " . $e->getMessage();
}

```
7.) Lastly, in your admin page. Admin should just enter the file name (file extension not included) of the accepted applicants and when the admin click the 'Accepted' button there should be a 'Passed' word appended at the start of the applicants txt file. And there should be a 'Failed' button that also appends 'Failed' if the applicant didn't met the expectation of our admin.
```
Solution: I set the uniqid() as the session of the appicant and used it and use a glob() function to check if the uniqid() if the applicant matches in any txt file on the current directory.
```
Example:
```php
// Check if the $_SESSION['id'] is matched in filename and append status
      $txt_files = glob("*.txt");
      foreach ($txt_files as $txt_file) {
        if (strpos($txt_file, $_SESSION['id']) !== false) {
          $output_file = file($txt_file);
          $output_file[0] = "Status: " . ucfirst($status) . "\n";
          file_put_contents($txt_file, implode("", $output_file));
        }
      }
```

