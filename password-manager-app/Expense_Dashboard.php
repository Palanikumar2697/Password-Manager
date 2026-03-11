<?php
include('conn/conn1.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Expense Tracker Dashboard</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- Your custom CSS -->

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">


</head>


<body>
<style>
    /* ===============================
   ROOT VARIABLES
================================ */
:root {
    --primary: #343a40;
    --primary-light: #495057;
    --accent: #007bff;
    --success: #28a745;
    --danger: #dc3545;
    --warning: #ffc107;
    --bg-light: #f4f6f9;
    --card-bg: #ffffff;
    --border: #dee2e6;
    --shadow: 0 6px 18px rgba(0,0,0,0.08);
}

/* ===============================
   GLOBAL
================================ */
html, body {
    height: 100%;
    margin: 0;
    overflow-x: hidden;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI",
                 Roboto, "Helvetica Neue", Arial, sans-serif;

    color: #343a40;

  

    background-size: cover;
    background-attachment: scroll; /* 👈 FIX */
}
@media (min-width: 1200px) {
    body {
        background-attachment: fixed;
    }
}


a {
    text-decoration: none;
}

.container-fluid {
    padding: 20px;
}

/* ===============================
   NAVBAR
================================ */
.navbar {
    box-shadow: var(--shadow);
}

.navbar-brand {
    font-weight: 600;
    font-size: 1.1rem;
}

/* ===============================
   CARDS
================================ */
.card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(6px);
    border-radius: 16px;
}


.card-header {
    background-color: #f8f9fa;
    font-weight: 600;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-body {
    padding: 15px;
}

/* ===============================
   TABLES
================================ */
.table {
    margin-bottom: 0;
}

.table thead th {
    background-color: #f1f3f5;
    font-weight: 600;
    border-bottom: 2px solid var(--border);
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}

.table td,
.table th {
    vertical-align: middle;
}

/* ===============================
   BUTTONS
================================ */
.btn {
    border-radius: 10px;
    font-size: 0.85rem;
}

.btn-outline-secondary:hover {
    background-color: var(--primary);
    color: #fff;
}

.action-btns button {
    margin-right: 5px;
}

/* ===============================
   FILTER BAR
================================ */
.form-inline input[type="date"] {
    min-width: 160px;
}

/* ===============================
   TOTALS
================================ */
.total-remaining {
    font-weight: 600;
}

.total-remaining.warning {
    color: var(--danger);
}

/* ===============================
   MODALS
================================ */
.modal-content {
    border-radius: 14px;
    box-shadow: var(--shadow);
}

.modal-header {
    border-bottom: 1px solid var(--border);
}

.modal-footer {
    border-top: 1px solid var(--border);
}

.modal-body input,
.modal-body select {
    border-radius: 8px;
}

/* ===============================
   CHARTS
================================ */
canvas {
    max-width: 100%;
}

/* ===============================
   EXPORT BUTTONS
================================ */
.btn-success.btn-sm {
    padding: 6px 12px;
    font-weight: 500;
}

/* ===============================
   RESPONSIVE
================================ */
@media (max-width: 992px) {
    .form-inline {
        flex-direction: column;
        align-items: flex-start;
    }

    .form-inline input,
    .form-inline button,
    .form-inline a {
        margin-bottom: 8px;
        width: 100%;
    }
}

@media (max-width: 576px) {
    .card-header {
        font-size: 0.9rem;
    }

    .btn {
        font-size: 0.8rem;
    }
}
.card-header .btn {
    transition: all 0.2s ease;
}

.card-header .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.action-btns {
    white-space: nowrap;
}

.action-btns .btn {
    padding: 4px 10px;
    font-size: 0.75rem;
    border-radius: 50px;
}

.action-btns .btn i {
    font-size: 0.75rem;
}
.table thead th {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.table tbody td {
    font-size: 0.85rem;
}
.table tbody tr:last-child {
    background: #fff3cd;
    font-weight: 600;
}
.card-header span {
    font-size: 0.95rem;
    letter-spacing: 0.3px;
}
.form-inline .btn {
    min-width: 90px;
}
.table-hover tbody tr {
    transition: background-color 0.15s ease-in-out;
}


</style>
<nav class="navbar navbar-dark bg-dark px-3 d-flex justify-content-between">
    <span class="navbar-brand">Expense Tracker Dashboard</span>

   <button class="btn btn-outline-light btn-sm"
        onclick="window.location.href='/PM/password-manager-app/home.php'">
    <i class="fa fa-arrow-left"></i> Back
</button>

</nav>


<div class="container-fluid mt-4">
<div class="row">

<!-- ================= CATEGORY PANEL ================= -->
<div class="col-lg-5">
<div class="card">
<div class="card-header d-flex justify-content-between align-items-center">
   <span class="font-weight-bold">Expense Categories</span>



    <button class="btn btn-sm btn-primary rounded-pill px-3"
            data-toggle="modal"
            data-target="#addCategoryModal">
        <i class="fa-solid fa-plus me-1"></i> Category
    </button>
</div>

<div class="card-body">
<table class="table table-hover">
<thead>
<tr><th>ID</th><th>Name</th><th>Budget</th><th>Action</th></tr>
</thead>
<tbody>

<?php
$categories = $conn->query("SELECT * FROM tbl_expense_category")->fetchAll();
$totalBudget = 0;

foreach ($categories as $row):
$totalBudget += $row['category_budget'];
?>

<tr>
<td><?= $row['tbl_expense_category_id'] ?></td>
<td id="cat-name-<?= $row['tbl_expense_category_id'] ?>"><?= htmlspecialchars($row['category_name']) ?></td>
<td id="cat-budget-<?= $row['tbl_expense_category_id'] ?>"><?= $row['category_budget'] ?></td>
<td class="action-btns">
  <button class="btn btn-sm btn-outline-primary" onclick="editCategory(<?= $row['tbl_expense_category_id'] ?>)">
    <i class="fa fa-pencil me-1"></i> Edit
  </button>

  <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(<?= $row['tbl_expense_category_id'] ?>)">
    <i class="fa fa-trash me-1"></i> Delete
  </button>
</td>

</tr>

<?php endforeach; ?>

<tr>
<td colspan="2"><strong>Total Budget</strong></td>
<td colspan="2"><strong><?= $totalBudget ?></strong></td>
</tr>

</tbody>
</table>
</div>
</div>
</div>

<!-- ================= EXPENSE PANEL ================= -->
<div class="col-lg-7">
<div class="card">
<div class="card-header d-flex justify-content-between align-items-center">
    <span class="font-weight-bold">Expenses</span>


    <button class="btn btn-sm btn-success rounded-pill px-3"
            data-toggle="modal"
            data-target="#addExpenseModal">
        <i class="fa-solid fa-plus me-1"></i> Expense
    </button>
</div>

<div class="card-body">
    <form method="GET" class="form-inline mb-3">
    <input type="date" name="from_date" class="form-control mr-2"
           value="<?= $_GET['from_date'] ?? '' ?>" required>

    <input type="date" name="to_date" class="form-control mr-2"
           value="<?= $_GET['to_date'] ?? '' ?>" required>

    <button class="btn btn-primary mr-2" type="submit">
        <i class="fa fa-filter"></i> Filter
    </button>

   <button type="button" id="resetFilters" class="btn btn-secondary">
    Reset
</button>


</form>
<?php if (!empty($_GET['from_date']) && !empty($_GET['to_date'])): ?>
<div class="alert alert-info py-2">
    Showing expenses from
    <strong><?= htmlspecialchars($_GET['from_date']) ?></strong>
    to
    <strong><?= htmlspecialchars($_GET['to_date']) ?></strong>
</div>
<?php endif; ?>




<table class="table table-hover">
<thead>
<tr><th>ID</th><th>Name</th><th>Category</th><th>Date</th><th>Spent</th><th>Action</th></tr>
</thead>
<tbody>

<?php
$where = "";
$params = [];

if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
    $where = "WHERE expense_date BETWEEN :from AND :to";
    $params = [
        ':from' => $_GET['from_date'],
        ':to'   => $_GET['to_date']
    ];
}

$stmt = $conn->prepare("
    SELECT e.*, c.category_name
    FROM tbl_expense e
    LEFT JOIN tbl_expense_category c
    ON e.tbl_expense_category_id = c.tbl_expense_category_id
    $where
    ORDER BY expense_date DESC
");
$stmt->execute($params);
$expenses = $stmt->fetchAll();


$totalSpent = 0;
foreach ($expenses as $e):
$totalSpent += $e['expense_spent'];
?>

<tr>
<td><?= $e['tbl_expense_id'] ?></td>
<td><?= htmlspecialchars($e['expense_name']) ?></td>
<td><?= htmlspecialchars($e['category_name']) ?></td>
<td><?= $e['expense_date'] ?></td>
<td><?= $e['expense_spent'] ?></td>
<td>
  <button class="btn btn-sm btn-outline-danger" onclick="deleteExpense(<?= $e['tbl_expense_id'] ?>)">
    <i class="fa fa-trash me-1"></i> Delete
  </button>
</td>

</tr>

<?php endforeach; ?>

<tr>
<td colspan="4"><strong>Total Spent</strong></td>
<td colspan="2"><strong><?= $totalSpent ?></strong></td>
</tr>

<tr>
<td colspan="4"><strong>Remaining</strong></td>
<td colspan="2">
<strong class="total-remaining <?= ($totalBudget - $totalSpent < 0) ? 'warning' : '' ?>">
<?= $totalBudget - $totalSpent ?>
</strong>
</td>
</tr>

</tbody>
</table>
</div>
</div>
</div>

</div>
</div>

<!-- ================= ADD CATEGORY MODAL ================= -->
<div class="modal fade" id="addCategoryModal">
<div class="modal-dialog">
<form class="modal-content" method="POST" action="endpoint/add_category.php">
<div class="modal-header">
<h5>Add Category</h5>
<button class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
<input class="form-control mb-2" name="category_name" placeholder="Category Name" required>
<input class="form-control" name="category_budget" type="number" placeholder="Budget" required>
</div>
<div class="modal-footer">
<button class="btn btn-primary">Save</button>
</div>
</form>
</div>
</div>

<!-- ================= UPDATE CATEGORY MODAL ================= -->
<div class="modal fade" id="updateCategoryModal">
<div class="modal-dialog">
<form class="modal-content" method="POST" action="endpoint/update_category.php">
<div class="modal-header">
<h5>Update Category</h5>
<button class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
<input type="hidden" id="updateCategoryID" name="category_id">
<input class="form-control mb-2" id="updateCategoryName" name="category_name" required>
<input class="form-control" id="updateCategoryBudget" name="category_budget" type="number" required>
</div>
<div class="modal-footer">
<button class="btn btn-primary">Update</button>
</div>
</form>
</div>
</div>

<!-- ================= ADD EXPENSE MODAL ================= -->
<div class="modal fade" id="addExpenseModal">
<div class="modal-dialog">
<form class="modal-content" method="POST" action="endpoint/add_expense.php">
<div class="modal-header">
<h5>Add Expense</h5>
<button class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
<input class="form-control mb-2" name="expense_name" placeholder="Expense Name" required>
<select class="form-control mb-2" name="tbl_expense_category_id" required>
<option value="">Select Category</option>
<?php foreach ($categories as $c): ?>
<option value="<?= $c['tbl_expense_category_id'] ?>"><?= $c['category_name'] ?></option>
<?php endforeach; ?>
</select>
<input class="form-control mb-2" type="date" name="expense_date" required>
<input class="form-control" type="number" name="expense_spent" placeholder="Amount" required>
</div>
<div class="modal-footer">
<button class="btn btn-primary">Save</button>
</div>
</form>
</div>
</div>

<!-- ================= CHARTS ================= -->
<div class="row mt-4">

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Budget vs Spent (Category)</div>
            <div class="card-body">
                <canvas id="budgetSpentChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Category-wise Expense</div>
            <div class="card-body">
                <canvas id="categoryPieChart" height="150"></canvas>
            </div>
        </div>
    </div>

   

</div>
<?php
$categoryNames = [];
$categoryBudgets = [];
$categorySpentData = [];

foreach ($categories as $cat) {
    $categoryNames[] = $cat['category_name'];
    $categoryBudgets[] = (float)$cat['category_budget'];

    $spent = 0;
    foreach ($expenses as $e) {
        if ($e['tbl_expense_category_id'] == $cat['tbl_expense_category_id']) {
            $spent += (float)$e['expense_spent'];
        }
    }
    $categorySpentData[] = $spent;
}

// Daily expense calculation
$dailyExpenses = [];
foreach ($expenses as $e) {
    $date = $e['expense_date'];
    $dailyExpenses[$date] = ($dailyExpenses[$date] ?? 0) + $e['expense_spent'];
}
ksort($dailyExpenses);
?>


<!-- ================= JS ================= -->

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function editCategory(id) {
    $('#updateCategoryModal').modal('show');
    $('#updateCategoryID').val(id);
    $('#updateCategoryName').val($('#cat-name-'+id).text());
    $('#updateCategoryBudget').val($('#cat-budget-'+id).text());
}

function deleteCategory(id) {
    if(confirm('Delete this category?'))
        window.location = 'endpoint/delete_category.php?category=' + id;
}

function deleteExpense(id) {
    if(confirm('Delete this expense?'))
        window.location = 'endpoint/delete_expense.php?expense=' + id;
}
</script>

<script>
const categoryNames = <?= json_encode($categoryNames) ?>;
const categoryBudgets = <?= json_encode($categoryBudgets) ?>;
const categorySpentData = <?= json_encode($categorySpentData) ?>;
const dailyDates = <?= json_encode(array_keys($dailyExpenses)) ?>;
const dailySpent = <?= json_encode(array_values($dailyExpenses)) ?>;

/* Budget vs Spent */
new Chart(document.getElementById('budgetSpentChart'), {
    type: 'bar',
    data: {
        labels: categoryNames,
        datasets: [
            {
                label: 'Budget',
                data: categoryBudgets,
                backgroundColor: 'rgba(54,162,235,0.6)'
            },
            {
                label: 'Spent',
                data: categorySpentData,
                backgroundColor: 'rgba(255,99,132,0.6)'
            }
        ]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

/* Category Pie */
new Chart(document.getElementById('categoryPieChart'), {
    type: 'pie',
    data: {
        labels: categoryNames,
        datasets: [{
            data: categorySpentData,
            backgroundColor: [
                '#007bff','#28a745','#ffc107',
                '#dc3545','#17a2b8','#6f42c1'
            ]
        }]
    },
    options: { responsive: true }
});


</script>
<script>
document.getElementById('resetFilters').addEventListener('click', function () {
    // Reload same PHP file without GET parameters
    window.location.href = 'Expense_Dashboard.php';
});
</script>



</body>
</html>
