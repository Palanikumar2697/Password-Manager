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
                    <tr>
                        <td><?= $accountID ?></td>
                        <td><?= $created_at ?></td>
                        <td><?= $accountName ?></td>
                        <td><?= $username ?></td>
                       <td>
    
  <div class="input-group input-group-sm">
        <input type="password" 
               class="form-control text-center password-field" 
               value="<?= htmlspecialchars($password) ?>" 
               readonly>
        <button class="btn btn-outline-secondary toggle-password" type="button">
            <i class="fa-solid fa-eye-slash"></i>
        </button>
  </div>
</td>

                        <td><a href="<?= $link ?>" target="_blank" class="text-decoration-none"><?= $link ?></a></td>
                        <td><?= $description ?></td>
                        <td><?= $created_by ?></td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-warning" onclick="update_account(<?= $accountID ?>)" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="delete_account(<?= $accountID ?>, '<?= addslashes($accountName) ?>')" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ✅ Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="deleteMessage">
        Are you sure you want to delete this account?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
      </div>
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




<script>
function delete_account(accountId, accountName) {
    // Update modal content with account name
    document.getElementById("deleteMessage").innerText =
        "Are you sure you want to delete account: \"" + accountName + "\"?";

    // Set delete link dynamically
    document.getElementById("confirmDeleteBtn").href =
        "./endpoint/delete-account.php?id=" + accountId;

    // Show modal
    $('#deleteConfirmModal').modal('show');
}
</script>

<script>
function update_account(id) {
    $("#updateAccountModal").modal("show");

    let updateAccountID   = $("#accountID-" + id).text().trim();
    let updateAccountName = $("#accountName-" + id).text().trim();
    let updateUsername    = $("#username-" + id).text().trim();
    let updatePassword    = $("#password-" + id).text().trim();
    let updateLink        = $("#link-" + id).text().trim();
    let updateDescription = $("#description-" + id).text().trim();
    let updateCreatedAt   = $("#created_at-" + id).text().trim(); // ✅ get created_at

    $("#updateAccountID").val(updateAccountID);
    $("#updateAccountName").val(updateAccountName);
    $("#updateUsername").val(updateUsername);
    $("#updatePassword").val(updatePassword);
    $("#updateLink").val(updateLink);
    $("#updateDescription").val(updateDescription);

    // ✅ format datetime for input[type="datetime-local"]
    if (updateCreatedAt) {
        let formatted = updateCreatedAt.replace(" ", "T").slice(0, 16);
        $("#updateCreatedAt").val(formatted);
    }
}
</script>





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



