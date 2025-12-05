<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables
$emailSent = false;
$errorMessage = '';
$successMessage = '';
$fileSaved = false;
$formSubmitted = false;

// Process form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formSubmitted = true;
    
    // Collect form data
    $name = htmlspecialchars($_POST['name'] ?? '');
    $admission = htmlspecialchars($_POST['admission'] ?? '');
    $level = htmlspecialchars($_POST['level'] ?? '');
    $season = htmlspecialchars($_POST['season'] ?? '');
    $examDate = htmlspecialchars($_POST['examDate'] ?? '');
    
    // Save to file first (always works)
    if (!empty($name) && !empty($admission)) {
        $filename = "student_data.txt";
        $data = "=== NEW SUBMISSION ===\n";
        $data .= "Date: " . date('Y-m-d H:i:s') . "\n";
        $data .= "Name: $name\n";
        $data .= "Admission: $admission\n";
        $data .= "Level: $level\n";
        $data .= "Season: $season\n";
        $data .= "Exam Date: $examDate\n";
        $data .= "=====================\n\n";
        
        // Append to file
        file_put_contents($filename, $data, FILE_APPEND);
        $fileSaved = true;
    }
    
    // Try to send email
    $to = "nuhualiyu222@gmail.com";
    $subject = "New Student Level Verification - " . $name;
    
    // Email body
    $message = "
    === STUDENT LEVEL VERIFICATION DETAILS ===

    Student Name: $name
    Admission Number: $admission
    Current Level: $level
    Season: $season
    Last Exam Date: $examDate

    Submission Time: " . date('Y-m-d H:i:s') . "
    IP Address: " . $_SERVER['REMOTE_ADDR'] . "
    User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "
    ";
    
    // Email headers
    $headers = "From: Student Verification System <verification@student-system.com>\r\n";
    $headers .= "Reply-To: no-reply@student-system.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Attempt to send email
    if (mail($to, $subject, $message, $headers)) {
        $emailSent = true;
        $successMessage = "Your information has been submitted successfully! An email has been sent to the administrator.";
    } else {
        $errorMessage = "Email sending failed, but your data was saved to our records.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Level Verification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 600px;
            overflow: hidden;
        }
        
        .header {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .form-section {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        input:focus, select:focus {
            border-color: #667eea;
            outline: none;
        }
        
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
        }
        
        .result-section {
            padding: 20px;
            background: #f8f9fa;
            border-top: 3px solid #ddd;
            margin-top: 20px;
        }
        
        .result-section h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        
        .success {
            color: #28a745;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .error {
            color: #dc3545;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .details-table th {
            background: #2c3e50;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        
        .details-table tr:last-child td {
            border-bottom: none;
        }
        
        .hidden {
            display: none;
        }
        
        .message {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .notification {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        
        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .notification.info {
            background-color: #e8f4fd;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>=== STUDENT LEVEL VERIFICATION SYSTEM ===</h1>
            <p>Please enter your details below:</p>
        </div>
        
        <div class="form-section">
            <?php if ($emailSent && $fileSaved): ?>
                <div class="notification success">
                    ✅ <?php echo $successMessage; ?>
                </div>
            <?php elseif (!$emailSent && $fileSaved): ?>
                <div class="notification error">
                    ❌ <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($fileSaved): ?>
                <div class="notification info">
                    📁 Data saved to student_data.txt
                </div>
            <?php endif; ?>
            
            <form id="studentForm" method="POST" action="">
                <div class="form-group">
                    <label for="name">Enter your name:</label>
                    <input type="text" id="name" name="name" placeholder="Your full name" 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="admission">Enter your admission number:</label>
                    <input type="number" id="admission" name="admission" placeholder="Admission number" 
                           value="<?php echo isset($_POST['admission']) ? htmlspecialchars($_POST['admission']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="level">Enter your current level:</label>
                    <input type="number" id="level" name="level" placeholder="e.g., 100, 200, 300" 
                           value="<?php echo isset($_POST['level']) ? htmlspecialchars($_POST['level']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="season">Enter your season:</label>
                    <select id="season" name="season" required>
                        <option value="">Select season</option>
                        <option value="2022/2023" <?php echo (isset($_POST['season']) && $_POST['season'] == '2022/2023') ? 'selected' : ''; ?>>2022/2023</option>
                        <option value="2023/2024" <?php echo (isset($_POST['season']) && $_POST['season'] == '2023/2024') ? 'selected' : ''; ?>>2023/2024</option>
                        <option value="2024/2025" <?php echo (isset($_POST['season']) && $_POST['season'] == '2024/2025') ? 'selected' : ''; ?>>2024/2025</option>
                        <option value="2025/2026" <?php echo (isset($_POST['season']) && $_POST['season'] == '2025/2026') ? 'selected' : ''; ?>>2025/2026</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="examDate">Enter your last exam date:</label>
                    <input type="date" id="examDate" name="examDate" 
                           value="<?php echo isset($_POST['examDate']) ? htmlspecialchars($_POST['examDate']) : date('Y-m-d'); ?>" required>
                </div>
                
                <button type="submit">VERIFY STUDENT LEVEL</button>
            </form>
            
            <?php if ($formSubmitted && !empty($name)): ?>
            <div class="result-section" id="resultSection">
                <h2>=== VERIFICATION RESULT ===</h2>
                <div id="messageContainer">
                    <?php if ($season === "2024/2025"): ?>
                        <div class="success">
                            <div class="message">🎉 CONGRATULATIONS! 🎉 <?php echo $name; ?></div>
                            <p>Your season starts from 2024 to 2025</p>
                            <p><strong>NOW YOU ARE A 200 LEVEL STUDENT!</strong></p>
                        </div>
                    <?php else: ?>
                        <div class="error">
                            <div class="message">❌ SORRY! ❌ <?php echo $name; ?></div>
                            <p>Your season is <?php echo $season; ?></p>
                            <p><strong>YOU ARE NOT A 200 LEVEL STUDENT</strong></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <h3>=== STUDENT DETAILS SUMMARY ===</h3>
                <table class="details-table">
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td id="resultName"><?php echo $name; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Admission Number:</strong></td>
                        <td id="resultAdmission"><?php echo $admission; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Current Level:</strong></td>
                        <td id="resultLevel"><?php echo $level; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Season:</strong></td>
                        <td id="resultSeason"><?php echo $season; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Last Exam Date:</strong></td>
                        <td id="resultExamDate">
                            <?php 
                            if (!empty($examDate)) {
                                $date = new DateTime($examDate);
                                echo $date->format('F j, Y');
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Data Status:</strong></td>
                        <td>
                            <?php if ($fileSaved): ?>
                                ✅ Saved to file
                            <?php endif; ?>
                            <?php if ($emailSent): ?>
                                <br>✅ Email sent
                            <?php else: ?>
                                <br>⚠️ Email not sent (check server config)
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                
                <div style="margin-top: 20px; background: #e8f4fd; padding: 15px; border-radius: 5px;">
                    <h4>📁 System Status:</h4>
                    <p><strong>File Storage:</strong> <?php echo $fileSaved ? '✅ Working' : '❌ Not working'; ?></p>
                    <p><strong>Email System:</strong> <?php echo $emailSent ? '✅ Working' : '❌ Not working (common on localhost)'; ?></p>
                    <p><small>Note: Email sending might not work on localhost. On a real server, it will work.</small></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Set today's date as default if not already set
        document.addEventListener('DOMContentLoaded', function() {
            const examDateInput = document.getElementById('examDate');
            if (!examDateInput.value) {
                examDateInput.valueAsDate = new Date();
            }
            
            // Scroll to results if form was submitted
            <?php if ($formSubmitted && !empty($name)): ?>
                document.getElementById('resultSection').scrollIntoView({ 
                    behavior: 'smooth' 
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>