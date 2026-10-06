<?php

require_once "db.php";

$student_id = 1;

$sql = "
    SELECT
        students.name AS student_name,
        events.title AS event_name,
        events.event_date,
        events.venue
    FROM registrations
    INNER JOIN students
        ON registrations.student_id = students.student_id
    INNER JOIN events
        ON registrations.event_id = events.event_id
    WHERE students.student_id = :student_id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":student_id" => $student_id
]);

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Events</title>
</head>

<body>

<h1>Registered Events</h1>

<?php if (count($events) > 0): ?>

<table border="1" cellpadding="10">

<tr>
    <th>Student</th>
    <th>Event</th>
    <th>Date</th>
    <th>Venue</th>
</tr>

<?php foreach ($events as $event): ?>

<tr>
    <td>
        <?php echo htmlspecialchars($event["student_name"]); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($event["event_name"]); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($event["event_date"]); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($event["venue"]); ?>
    </td>
</tr>

<?php endforeach; ?>

</table>

<?php else: ?>

<p>No events registered.</p>

<?php endif; ?>

</body>
</html>

