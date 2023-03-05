<!DOCTYPE html>
<html>
<head>
  <title>Admin</title>
  <style>
		body {
			background-color: #f0f0f0;
			color: #333;
			font-family: Arial, sans-serif;
			transition: background-color 0.5s ease;
		}

		.dark-mode {
			background-color: #333;
			color: #f0f0f0;
		}

		nav {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 20px;
			background-color: #4CAF50;
			color: white;
      height: 10px;
		}

		button {
			background-color: #4CAF50;
			color: white;
			border: none;
			padding: 10px;
			border-radius: 5px;
			cursor: pointer;
			font-size: 16px;
      font-weight: bold;
			transition: background-color 0.5s ease;
		}

		button:hover {
			background-color: #3e8e41;
		}

		button:focus {
			outline: none;
		}

		.dark-mode button {
			background-color: #f0f0f0;
			color: #333;
		}

		.dark-mode button:hover {
			color: #ccc;
		}

		.dark-mode h1 {
			color: #fff;
		}

    .dark-mode label {
			color: #fff;
		}

    h1 {
      margin-top: 5;
      text-align: center;
      color: #000;
    }
    form {
      max-width: 500px;
      margin: 0 auto;
      padding: 20px;
      border: 3px solid #ccc;
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
<nav>
		<h1>HRD Applicant Verification System - Admin Page</h1>
		<button id="mode-toggle" onclick="toggleMode()">Dark Mode</button>
	</nav>
  <br>
	<main>
  <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="filename">File name:</label>
    <input type="text" id="filename" name="filename" required>
    <input type="submit" name="accept" value="Accept">
    <input type="submit" name="reject" value="Decline">
  </form>
  </main>
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

<script>
		var modeToggle = document.getElementById("mode-toggle");

		// Check if there is a saved mode in local storage
		var savedMode = localStorage.getItem("mode");
		if (savedMode === "dark") {
			// Set the dark mode class on the body
			document.body.classList.add("dark-mode");
			// Update the button text
			modeToggle.textContent = "Light Mode";
		}

		function toggleMode() {
			var body = document.body;
			var mode = body.classList.contains("dark-mode") ? "light" : "dark";
			body.classList.toggle("dark-mode");
			modeToggle.textContent = mode === "dark" ? "Light Mode" : "Dark Mode";
			// Save the mode in local storage
			localStorage.setItem("mode", mode);
		}
	</script>
</body>
</html>