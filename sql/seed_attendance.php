<?php
// seed_attendance.php
// Run this script to insert 1000 dummy attendance records into the Flow HR database.
// WARNING: This will truncate the attendance table in the connected database.
// Usage (dev): open in browser http://localhost/miniHRIS/sql/seed_attendance.php
// Or run from CLI: php C:\xampp\htdocs\miniHRIS\sql\seed_attendance.php

require_once __DIR__ . '/../inc/config.php';

try {
    // Ensure attendance table exists (schema from app)
    $pdo->exec("CREATE TABLE IF NOT EXISTS attendance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employee_id INT NOT NULL,
        `date` DATE NOT NULL,
        status VARCHAR(50) NOT NULL,
        note TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Get employee IDs
    $stmt = $pdo->query('SELECT id FROM employees');
    $employees = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (empty($employees)) {
        echo "No employees found. Please seed employees first (or add some employees).";
        exit;
    }

    // Truncate attendance
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
    $pdo->exec('TRUNCATE TABLE attendance;');
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');

    $statuses = ['Present', 'Absent', 'Sick', 'Leave', 'WFH', 'Late'];
    $notes_options = [
        null,
        'Arrived late by 10 minutes',
        'Medical note provided',
        'Approved leave',
        'Work from home',
        'Shift changed'
    ];

    $insert = $pdo->prepare('INSERT INTO attendance (employee_id, `date`, status, note) VALUES (?, ?, ?, ?)');
    $pdo->beginTransaction();

    $start = new DateTime('2023-01-01');
    $end = new DateTime(); // today
    $daysRange = (int)$start->diff($end)->format('%a');

    $total = 1000;
    for ($i = 0; $i < $total; $i++) {
        // pick employee
        $employee_id = (int)$employees[array_rand($employees)];
        // random date in range
        $randDay = rand(0, max(0, $daysRange));
        $dt = (clone $start)->modify("+{$randDay} days")->format('Y-m-d');
        // status distribution biased to Present
        $r = rand(1, 100);
        if ($r <= 70) $status = 'Present';
        elseif ($r <= 80) $status = 'Late';
        elseif ($r <= 88) $status = 'WFH';
        elseif ($r <= 94) $status = 'Sick';
        elseif ($r <= 97) $status = 'Leave';
        else $status = 'Absent';
        // note sometimes
        $note = null;
        if ($status === 'Late') $note = 'Arrived late by ' . rand(5, 60) . ' minutes';
        elseif ($status === 'Sick' && rand(1,3) === 1) $note = 'Medical certificate provided';
        elseif ($status === 'Leave') $note = 'Annual leave';
        elseif ($status === 'WFH' && rand(1,4) === 1) $note = 'WFH due to internet issues';

        $insert->execute([$employee_id, $dt, $status, $note]);
    }

    $pdo->commit();

    echo "Inserted {$total} attendance records successfully.\n";
    echo "You can now visit the Attendance page in the app to view data.";

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit(1);
}
