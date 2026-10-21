<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL Schema & ER Model - Practical 8</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 8: MySQL Schema Design, ER Model and Database Connectivity</p>
    </div>

    <div class="container container-wide">
        <div class="card">
            <div class="table-header">
                <h2>Database Design & Relational Schema</h2>
                <a href="test_connection.php" class="btn btn-primary">Test Live DB Connection</a>
            </div>

            <div class="schema-section">
                <h3>1. Table: students</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Type</th>
                                <th>Key</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>student_id</td>
                                <td>INT</td>
                                <td>PRIMARY KEY, AUTO_INCREMENT</td>
                                <td>Unique student identifier</td>
                            </tr>
                            <tr>
                                <td>student_code</td>
                                <td>VARCHAR(20)</td>
                                <td>UNIQUE</td>
                                <td>Student roll/ID number</td>
                            </tr>
                            <tr>
                                <td>name</td>
                                <td>VARCHAR(100)</td>
                                <td>-</td>
                                <td>Full student name</td>
                            </tr>
                            <tr>
                                <td>email</td>
                                <td>VARCHAR(100)</td>
                                <td>UNIQUE</td>
                                <td>Official email address</td>
                            </tr>
                            <tr>
                                <td>mobile</td>
                                <td>VARCHAR(20)</td>
                                <td>-</td>
                                <td>Contact phone number</td>
                            </tr>
                            <tr>
                                <td>course</td>
                                <td>VARCHAR(50)</td>
                                <td>-</td>
                                <td>Enrolled academic course</td>
                            </tr>
                            <tr>
                                <td>year</td>
                                <td>VARCHAR(20)</td>
                                <td>-</td>
                                <td>Current study year</td>
                            </tr>
                            <tr>
                                <td>created_at</td>
                                <td>TIMESTAMP</td>
                                <td>-</td>
                                <td>Record creation timestamp</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>2. Table: events</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Type</th>
                                <th>Key</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>event_id</td>
                                <td>INT</td>
                                <td>PRIMARY KEY, AUTO_INCREMENT</td>
                                <td>Unique event identifier</td>
                            </tr>
                            <tr>
                                <td>title</td>
                                <td>VARCHAR(150)</td>
                                <td>-</td>
                                <td>Event name / title</td>
                            </tr>
                            <tr>
                                <td>description</td>
                                <td>TEXT</td>
                                <td>-</td>
                                <td>Detailed event overview</td>
                            </tr>
                            <tr>
                                <td>event_date</td>
                                <td>DATE</td>
                                <td>-</td>
                                <td>Scheduled event date</td>
                            </tr>
                            <tr>
                                <td>venue</td>
                                <td>VARCHAR(100)</td>
                                <td>-</td>
                                <td>Campus location / hall</td>
                            </tr>
                            <tr>
                                <td>category</td>
                                <td>VARCHAR(50)</td>
                                <td>-</td>
                                <td>Technical, Workshop, etc.</td>
                            </tr>
                            <tr>
                                <td>status</td>
                                <td>ENUM</td>
                                <td>-</td>
                                <td>upcoming / completed / cancelled</td>
                            </tr>
                            <tr>
                                <td>created_at</td>
                                <td>TIMESTAMP</td>
                                <td>-</td>
                                <td>Record creation timestamp</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>3. Table: registrations (Relational Junction)</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Type</th>
                                <th>Key</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>registration_id</td>
                                <td>INT</td>
                                <td>PRIMARY KEY, AUTO_INCREMENT</td>
                                <td>Unique registration identifier</td>
                            </tr>
                            <tr>
                                <td>student_id</td>
                                <td>INT</td>
                                <td>FOREIGN KEY (students.student_id)</td>
                                <td>References student registered</td>
                            </tr>
                            <tr>
                                <td>event_id</td>
                                <td>INT</td>
                                <td>FOREIGN KEY (events.event_id)</td>
                                <td>References event chosen</td>
                            </tr>
                            <tr>
                                <td>registration_date</td>
                                <td>TIMESTAMP</td>
                                <td>-</td>
                                <td>Registration date & time</td>
                            </tr>
                            <tr>
                                <td>status</td>
                                <td>VARCHAR(20)</td>
                                <td>-</td>
                                <td>Status (confirmed/pending)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="action-buttons">
                <a href="test_connection.php" class="btn btn-primary">Run Connection & Count Test</a>
            </div>
        </div>
    </div>
</body>
</html>