<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Practical 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 7: PHP Form Processing & File Storage (CSV / JSON)</p>
    </div>

    <div class="container">
        <div class="card">
            <h2>Student Registration Form</h2>
            <form action="process.php" method="POST">
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="student@charusat.edu.in" required>
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <input type="tel" id="mobile" name="mobile" placeholder="10 digit mobile number" maxlength="10" required>
                </div>

                <div class="form-group">
                    <label for="course">Course</label>
                    <select id="course" name="course" required>
                        <option value="">-- Select Course --</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Computer Engineering">Computer Engineering</option>
                        <option value="Computer Science & Engineering">Computer Science & Engineering</option>
                        <option value="Artificial Intelligence">Artificial Intelligence</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="year">Academic Year</label>
                    <select id="year" name="year" required>
                        <option value="">-- Select Year --</option>
                        <option value="1st Year">1st Year</option>
                        <option value="2nd Year">2nd Year</option>
                        <option value="3rd Year">3rd Year</option>
                        <option value="4th Year">4th Year</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <div class="radio-group">
                        <label><input type="radio" name="gender" value="Male" required> Male</label>
                        <label><input type="radio" name="gender" value="Female"> Female</label>
                        <label><input type="radio" name="gender" value="Other"> Other</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message">Remarks / Message</label>
                    <textarea id="message" name="message" rows="3" placeholder="Enter remarks (optional)"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit Registration</button>
            </form>
            <div class="footer-links">
                <a href="records.php" class="btn btn-secondary">View Stored Records</a>
            </div>
        </div>
    </div>
</body>
</html>