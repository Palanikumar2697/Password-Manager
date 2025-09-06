<?php
session_start();
include ('./conn/conn.php');

$userRow = null;
$user_name = "My Account";

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
// ✅ Fetch the full user details
$stmt = $conn->prepare("SELECT * FROM `tbl_user` WHERE `tbl_user_id` = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
}
if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_name = $row['name'];
}


include ('./partials/header.php');
include ('./partials/modal.php');
?>

<?php if (!empty($row)) : ?>
  <!-- Hidden values used for the "View User" modal -->
  <span id="userName-<?php echo $row['tbl_user_id']; ?>" class="d-none"><?php echo htmlspecialchars($row['name']); ?></span>
  <span id="userPhone-<?php echo $row['tbl_user_id']; ?>" class="d-none"><?php echo htmlspecialchars($row['phone_number']); ?></span>
  <span id="userEmail-<?php echo $row['tbl_user_id']; ?>" class="d-none"><?php echo htmlspecialchars($row['email_address']); ?></span>
  <span id="userUsername-<?php echo $row['tbl_user_id']; ?>" class="d-none"><?php echo htmlspecialchars($row['username']); ?></span>
  <span id="userPassword-<?php echo $row['tbl_user_id']; ?>" class="d-none"><?php echo htmlspecialchars($row['password']); ?></span>
<?php endif; ?>


<nav class="navbar navbar-expand-lg navbar-dark bg-secondary px-3">
  <a class="navbar-brand" href="home.php">Password Manager App</a>

  <div class="ms-auto">
    <div class="dropdown">
      <a class="nav-link dropdown-toggle d-flex align-items-center" 
         href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
         style="text-decoration: none; color: #eee;">
         
        <i class="fa-solid fa-circle-user me-2" style="font-size: 1.4rem;"></i>

        <?php if (isset($user_name)) { ?>
          Welcome, <strong><?php echo $user_name; ?></strong>
        <?php } else { ?>
          My Account
        <?php } ?>
      </a>

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow">
        <li>
          <a class="dropdown-item" style="cursor: pointer;" onclick="view_user(<?php echo $user_id ?>)">
            <i class="fa-regular fa-user"></i> View Account
          </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <a class="dropdown-item" href="index.php">
            <i class="fa-solid fa-lock"></i> Log Out
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<div class="container-fluid py-4">
    <div class="accounts-container card shadow-lg p-4 rounded-3">
        <h4 class="text-center mb-4">
            <strong><?php echo $user_name; ?>'s Accounts</strong>
        </h4>

        <!-- Add Account Button -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                <i class="fa-solid fa-users me-2"></i> Add Account
            </button>
        </div>

        <!-- All Accounts Table -->
        <div class="table-responsive">
            <table id="accountsTable" class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Date</th>
                        <th scope="col">Account Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Password</th>
                        <th scope="col">URL</th>
                        <th scope="col">Description</th>
                        <th scope="col">Created By</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
<?php 
$stmt = $conn->prepare("
    SELECT a.*, u.name AS created_by_name
    FROM tbl_accounts a
    LEFT JOIN tbl_user u ON a.tbl_user_id = u.tbl_user_id
    WHERE a.tbl_user_id = :user_id
");
$stmt->execute(['user_id' => $user_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($result) {
    foreach ($result as $row) {
        $accountID   = $row['tbl_account_id'];
        $created_at  = $row['created_at'];
        $accountName = $row['account_name'];
        $username    = $row['username'];
        $password    = $row['password'];
        $link        = $row['link'];
        $description = $row['description'];
        $created_by  = $row['created_by_name'];
?>
    <tr id="row-<?= $accountID ?>">
        <!-- ID -->
        <td id="accountID-<?= $accountID ?>"><?= $accountID ?></td>

        <!-- Created At -->
        <td id="created_at-<?= $accountID ?>"><?= htmlspecialchars($created_at) ?></td>

        <!-- Account Name -->
        <td id="accountName-<?= $accountID ?>"><?= htmlspecialchars($accountName) ?></td>

        <!-- Username -->
        <td id="username-<?= $accountID ?>"><?= htmlspecialchars($username) ?></td>

        <!-- Password column -->
        <td>
            <div class="position-relative input-group-sm">
                <input type="password" 
                       class="form-control text-center pe-5" 
                       value="<?= htmlspecialchars($password) ?>" 
                       readonly>

                <i class="fa-solid fa-eye-slash toggle-password"
                   style="position: absolute; top: 50%; right: 12px; 
                          transform: translateY(-50%); cursor: pointer; color: #666;"></i>
            </div>

            <!-- hidden span with actual password -->
            <span id="password-<?= $accountID ?>" 
                  class="d-none" 
                  data-password="<?= htmlspecialchars($password) ?>"></span>
        </td>

        <!-- URL -->
        <td id="link-<?= $accountID ?>">
            <a href="<?= htmlspecialchars($link) ?>" target="_blank" class="text-decoration-none">
                <?= htmlspecialchars($link) ?>
            </a>
        </td>

        <!-- Description -->
        <td id="description-<?= $accountID ?>"><?= htmlspecialchars($description) ?></td>

        <!-- Created By -->
        <td><?= htmlspecialchars($created_by) ?></td>

        <!-- Action Buttons -->
        <td>
            <div class="d-flex justify-content-center gap-2">
                <button class="btn btn-sm btn-warning"
                        onclick="update_account(<?= $accountID ?>)" 
                        title="Edit">
                     <i class="fa-solid fa-pen ms-1"></i>
                </button>

                <button class="btn btn-sm btn-outline-danger rounded-pill"
                        onclick="confirmDelete(<?= $accountID ?>, '<?= addslashes($accountName) ?>')" 
                        title="Delete">
                    <i class="fa-solid fa-trash me-1"></i>
                </button>
            </div>
        </td>
    </tr>
<?php 
    }
} else { 
?>
    <tr>
        <td colspan="9" class="text-center">No accounts found</td>
    </tr>
<?php } ?>

</tbody>

            </table>
        </div>
    </div>
</div>

<!-- ✅ Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Status</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="statusMessage">
        <!-- Success/Warning/Error message goes here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete <b id="deleteAccountName"></b>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
</div>

<script>
function confirmDelete(id, accountName) {
    Swal.fire({
        title: "Delete Account?",
        text: `Are you sure you want to delete "${accountName}"?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#d33"
    }).then((result) => {
        if (result.isConfirmed) {
            // ✅ Correct path to match your folder structure
            fetch("endpoint/delete-account.php?id=" + id)
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire({
                            title: "Deleted!",
                            text: data.message,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // ✅ Instantly remove deleted row from table
                        document.getElementById("row-" + data.id)?.remove();
                    } else {
                        Swal.fire("Oops!", data.message, data.status);
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire("Error", "Something went wrong!", "error");
                });
        }
    });
}
</script>


<script>
function update_account(id) {
    let modal = new bootstrap.Modal(document.getElementById("updateAccountModal"));
    modal.show();

    // ✅ Read values from table
    let updateAccountID   = $("#accountID-" + id).text().trim();
    let updateAccountName = $("#accountName-" + id).text().trim();
    let updateUsername    = $("#username-" + id).text().trim();
    let updatePassword    = $("#password-" + id).data("password"); // ✅ FIXED
    let updateLink        = $("#link-" + id).text().trim();
    let updateDescription = $("#description-" + id).text().trim();
    let updateCreatedAt   = $("#created_at-" + id).text().trim();

    console.log("Password from span:", updatePassword); // 👀 DEBUG

    // ✅ Fill modal fields
    $("#updateAccountID").val(updateAccountID);
    $("#updateAccountName").val(updateAccountName);
    $("#updateUsername").val(updateUsername);
    $("#updatePassword").val(updatePassword);   // 👈 should now populate
    $("#updateLink").val(updateLink);
    $("#updateDescription").val(updateDescription);

    // ✅ Format datetime
    if (updateCreatedAt) {
        let formatted = updateCreatedAt.replace(" ", "T").slice(0, 16);
        $("#updateCreatedAt").val(formatted);
    }
}
</script>

<?php if (!empty($_SESSION['flash_msg'])): ?>
    <div class="alert alert-<?= $_SESSION['flash_status'] ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['flash_msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash_msg'], $_SESSION['flash_status']); ?>
<?php endif; ?>


<?php if (isset($_SESSION['flash_status']) && isset($_SESSION['flash_msg'])): ?>
    <div class="alert alert-<?php 
        echo ($_SESSION['flash_status'] === 'success') ? 'success' : 
             (($_SESSION['flash_status'] === 'warning') ? 'warning' : 'danger'); 
    ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['flash_msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php 
        // ✅ Clear flash after showing once
        unset($_SESSION['flash_status']);
        unset($_SESSION['flash_msg']);
    ?>
<?php endif; ?>




<?php if (!empty($_SESSION['flash_status']) && !empty($_SESSION['flash_msg'])): ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
            icon: "<?= $_SESSION['flash_status'] ?>", // success | error | warning | info
            title: "<?= $_SESSION['flash_msg'] ?>",
            showConfirmButton: false,
            timer: 2000
        });
    });
</script>
<?php
    // ✅ Clear the flash so it shows only once
    unset($_SESSION['flash_status']);
    unset($_SESSION['flash_msg']);
?>
<?php endif; ?>




<?php include('./partials/footer.php') ?>



