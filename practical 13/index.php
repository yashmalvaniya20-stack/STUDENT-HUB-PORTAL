<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX / Fetch API CRUD - Practical 13</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 13: PHP JSON API & AJAX / Fetch-Based CRUD</p>
    </div>

    <div class="container container-wide">
        <div id="alertBox" class="alert" style="display: none;"></div>

        <div class="crud-layout">
            <div class="card form-card">
                <h2 id="formTitle">Add Student (No Page Reload)</h2>
                <form id="studentForm">
                    <input type="hidden" id="student_id" name="student_id" value="">

                    <div class="form-group">
                        <label for="student_code">Student Code</label>
                        <input type="text" id="student_code" name="student_code" placeholder="e.g. 23IT050" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Student name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="student@charusat.edu.in" required>
                    </div>

                    <div class="form-group">
                        <label for="mobile">Mobile Number</label>
                        <input type="tel" id="mobile" name="mobile" placeholder="10-digit number" maxlength="10" required>
                    </div>

                    <div class="form-group">
                        <label for="course">Course</label>
                        <select id="course" name="course" required>
                            <option value="">-- Select Course --</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Computer Engineering">Computer Engineering</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Artificial Intelligence">Artificial Intelligence</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="year">Year</label>
                        <select id="year" name="year" required>
                            <option value="">-- Select Year --</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" id="saveBtn" class="btn btn-primary">Save Student</button>
                        <button type="button" id="cancelBtn" class="btn btn-secondary" style="display: none;">Cancel</button>
                    </div>
                </form>
            </div>

            <div class="card table-card">
                <div class="table-header">
                    <h2>Live Students Directory</h2>
                    <input type="text" id="searchInput" placeholder="Search table..." class="search-input">
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Year</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                            <tr>
                                <td colspan="6" style="text-align:center;">Loading data via Fetch API...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="app.js"></script>
</body>
</html>