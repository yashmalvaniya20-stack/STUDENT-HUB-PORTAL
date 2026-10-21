<?php
$csvFile = "registrations.csv";
$rows = [];

if (file_exists($csvFile)) {
    $fp = fopen($csvFile, "r");
    if ($fp) {
        $header = fgetcsv($fp);
        while (($data = fgetcsv($fp)) !== false) {
            $rows[] = $data;
        }
        fclose($fp);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stored Registrations - Practical 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>StudentHub Portal</h1>
        <p>Practical 7: Display Stored CSV / JSON Records</p>
    </div>

    <div class="container container-wide">
        <div class="card">
            <div class="table-header">
                <h2>Saved Registrations (CSV Storage)</h2>
                <a href="index.php" class="btn btn-primary">+ New Registration</a>
            </div>

            <?php if (empty($rows)): ?>
                <div class="alert alert-info">
                    <p>No registration records found yet. Submit the form to generate records.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Course</th>
                                <th>Year</th>
                                <th>Gender</th>
                                <th>Message</th>
                                <th>Submitted At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $index => $row): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($row[0] ?? ""); ?></td>
                                    <td><?php echo htmlspecialchars($row[1] ?? ""); ?></td>
                                    <td><?php echo htmlspecialchars($row[2] ?? ""); ?></td>
                                    <td><?php echo htmlspecialchars($row[3] ?? ""); ?></td>
                                    <td><?php echo htmlspecialchars($row[4] ?? ""); ?></td>
                                    <td><?php echo htmlspecialchars($row[5] ?? ""); ?></td>
                                    <td><?php echo htmlspecialchars($row[6] ?? "-"); ?></td>
                                    <td><?php echo htmlspecialchars($row[7] ?? ""); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="footer-links">
                <a href="index.php" class="btn btn-secondary">Back to Registration Form</a>
            </div>
        </div>
    </div>
</body>
</html>