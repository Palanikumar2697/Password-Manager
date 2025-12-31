<?php
include('../conn/conn.php');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=monthly_expense_report.xls");

$stmt = $conn->query("
    SELECT DATE_FORMAT(expense_date,'%Y-%m') AS month,
           SUM(expense_spent) AS total
    FROM tbl_expense
    GROUP BY month
    ORDER BY month
");

echo "Month\tTotal Amount\n";

while ($row = $stmt->fetch()) {
    echo $row['month'] . "\t" . $row['total'] . "\n";
}
