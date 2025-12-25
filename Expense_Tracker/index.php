<?php
include('conn/conn.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Simple Expense Tracker App</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
.card-body { font-size: small; }
.main-panel, .card {
    margin: auto;
    height: 90vh;
    overflow-y: auto;
}
.action-btns button {
    margin-right: 4px;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark col-12">
    <a class="navbar-brand ml-5" href="#">Simple Expense Tracker App</a>
</nav>

<div class="main-panel mt-4 ml-5 col-11">
<div class="row">

<!-- ================= CATEGORY PANEL ================= -->
<div class="col-md-5">
<div class="card">
<div class="card-header">Expense Categories</div>
<div class="card-body">

<button class="btn btn-sm btn-outline-secondary float-right" data-toggle="modal" data-target="#addCategoryModal">
    + Expense Category
</button>

<!-- ADD CATEGORY MODAL -->
<div class="modal fade" id="addCategoryModal">
<div class="modal-dialog">
<div class="modal-content">
<form action="endpoint/add_category.php" method="POST">
<div class="modal-header">
<h5>Add Expense Category</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
<input type="hidden" name="tbl_expense_category_id">
<div class="form-group">
<label>Category Name</label>
<input type="text" class="form-control" name="category_name" required>
</div>
<div class="form-group">
<label>Budget</label>
<input type="number" class="form-control" name="category_budget" required>
</div>
</div>
<div class="modal-footer">
<button class="btn btn-secondary" data-dismiss="modal">Close</button>
<button class="btn btn-dark">Save</button>
</div>
</form>
</div>
</div>
</div>

<!-- UPDATE CATEGORY MODAL -->
<div class="modal fade" id="updateCategoryModal">
<div class="modal-dialog">
<div class="modal-content">
<form action="endpoint/update_category.php" method="POST">
<div class="modal-header">
<h5>Update Expense Category</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
<input type="hidden" id="updateCategoryID" name="tbl_expense_category_id">
<div class="form-group">
<label>Name</label>
<input type="text" id="updateCategoryName" class="form-control" name="category_name">
</div>
<div class="form-group">
<label>Budget</label>
<input type="number" id="updateCategoryBudget" class="form-control" name="category_budget">
</div>
</div>
<div class="modal-footer">
<button class="btn btn-secondary" data-dismiss="modal">Close</button>
<button class="btn btn-dark">Save</button>
</div>
</form>
</div>
</div>
</div>

<!-- CATEGORY TABLE -->
<div class="table-responsive mt-4">
<table class="table table-hover">
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Budget</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php
$stmt = $conn->prepare("SELECT * FROM tbl_expense_category");
$stmt->execute();
$categories = $stmt->fetchAll();

$totalBudget = 0;

foreach ($categories as $row):
$totalBudget += $row['category_budget'];
?>

<tr>
<td id="cat-id-<?= $row['tbl_expense_category_id'] ?>"><?= $row['tbl_expense_category_id'] ?></td>
<td id="cat-name-<?= $row['tbl_expense_category_id'] ?>"><?= htmlspecialchars($row['category_name']) ?></td>
<td id="cat-budget-<?= $row['tbl_expense_category_id'] ?>"><?= $row['category_budget'] ?></td>
<td class="action-btns">
<button class="btn btn-sm btn-outline-primary"
onclick="editCategory(<?= $row['tbl_expense_category_id'] ?>)">
<i class="fa fa-pencil"></i>
</button>
<button class="btn btn-sm btn-outline-danger"
onclick="deleteCategory(<?= $row['tbl_expense_category_id'] ?>)">
<i class="fa fa-trash"></i>
</button>
</td>
</tr>

<?php endforeach; ?>

<tr>
<td colspan="2"><strong>Total Budget</strong></td>
<td colspan="2"><strong id="totalBudget"><?= $totalBudget ?></strong></td>
</tr>

</tbody>
</table>
</div>

</div>
</div>
</div>

<!-- ================= EXPENSE PANEL ================= -->
<div class="col-md-7">
<div class="card">
<div class="card-header">Expenses</div>
<div class="card-body">

<button class="btn btn-sm btn-outline-secondary float-right" data-toggle="modal" data-target="#addExpenseModal">
+ Expense
</button>

<!-- ADD EXPENSE MODAL -->
<div class="modal fade" id="addExpenseModal">
<div class="modal-dialog">
<div class="modal-content">
<form action="endpoint/add_expense.php" method="POST">
<div class="modal-header">
<h5>Add Expense</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
<div class="form-group">
<label>Name</label>
<input type="text" class="form-control" name="expense_name" required>
</div>

<div class="form-group">
<label>Category</label>
<select class="form-control" name="tbl_expense_category_id" required>
<option value="">-- Select --</option>
<?php foreach ($categories as $c): ?>
<option value="<?= $c['tbl_expense_category_id'] ?>">
<?= htmlspecialchars($c['category_name']) ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div class="form-group">
<label>Date</label>
<input type="date" class="form-control" name="expense_date" required>
</div>

<div class="form-group">
<label>Amount</label>
<input type="number" class="form-control" name="expense_spent" required>
</div>

<div class="form-group">
<label>Description</label>
<textarea class="form-control" name="expense_description"></textarea>
</div>
</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-dismiss="modal">Close</button>
<button class="btn btn-dark">Save</button>
</div>
</form>
</div>
</div>
</div>

<!-- EXPENSE TABLE -->
<div class="table-responsive mt-4">
<table class="table table-hover">
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Category</th>
<th>Date</th>
<th>Spent</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php
$stmt = $conn->prepare("
SELECT e.*, c.category_name
FROM tbl_expense e
LEFT JOIN tbl_expense_category c
ON e.tbl_expense_category_id = c.tbl_expense_category_id
");
$stmt->execute();
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
<td class="action-btns">
<button class="btn btn-sm btn-outline-danger"
onclick="deleteExpense(<?= $e['tbl_expense_id'] ?>)">
<i class="fa fa-trash"></i>
</button>
</td>
</tr>

<?php endforeach; ?>

<tr>
<td colspan="4"><strong>Total Spent</strong></td>
<td colspan="2"><strong id="totalSpent"><?= $totalSpent ?></strong></td>
</tr>

</tbody>
</table>
</div>

</div>
</div>
</div>

</div>
</div>

<script>
function editCategory(id) {
    $('#updateCategoryModal').modal('show');
    $('#updateCategoryID').val(id);
    $('#updateCategoryName').val($('#cat-name-' + id).text());
    $('#updateCategoryBudget').val($('#cat-budget-' + id).text());
}

function deleteCategory(id) {
    if(confirm('Delete this category?')) {
        window.location = 'endpoint/delete_category.php?category=' + id;
    }
}

function deleteExpense(id) {
    if(confirm('Delete this expense?')) {
        window.location = 'endpoint/delete_expense.php?expense=' + id;
    }
}
</script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
