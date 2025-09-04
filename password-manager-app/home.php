<?php
    session_start();
include ('./conn/conn.php');

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch the user's name from the database
    $stmt = $conn->prepare("SELECT `name` FROM `tbl_user` WHERE `tbl_user_id` = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $user_name = $row['name'];
    }
}

include ('./partials/header.php');
include ('./partials/modal.php');
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-secondary px-3">
  <a class="navbar-brand" href="home.php">Password Manager App</a>

  <div class="ms-auto">
    <div class="dropdown">
      <a class="nav-link dropdown-toggle d-flex align-items-center" 
         href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
         style="text-decoration: none; color: #eee;">
         
        <!-- User Icon -->
        <i class="fa-solid fa-circle-user me-2" style="font-size: 1.4rem;"></i>

        <!-- Dynamic Username -->
        <?php if (isset($user_name)) { ?>
          Welcome, <strong><?php echo $user_name; ?></strong>
        <?php } else { ?>
          My Account
        <?php } ?>
      </a>

      <!-- Dark Dropdown -->
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow">
        <!-- View Account -->
        <li>
          <a class="dropdown-item" style="cursor: pointer;" onclick="view_user(<?php echo $user_id ?>)">
            <i class="fa-regular fa-user"></i> View Account
          </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        <!-- Log Out -->
        <li>
          <a class="dropdown-item" href="index.php">
            <i class="fa-solid fa-lock"></i> Log Out
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>




<div class="main">
    <div class="accounts-container">
        <h4 class="text-center"><strong><?php echo $user_name; ?>'s Accounts</strong></h4>
        
        <div class="add_accountbtn">
     <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
        <i class="fa-solid fa-users"></i> 
  Add Account
</button>    
        </div>
       
        <!-- All Accounts Table -->
        <div class="table-responsive">
            <table id="accountsTable" class="table table-hover task-list">
                <thead class="text-center">
                    <tr class="center1">
                        <th scope="col">ID</th>
                        <th scope="col">Date</th>
                        <th scope="col">Account Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Password</th>
                        <th style="display:none;"></th>
                        <th scope="col">URL</th>
                        <th scope="col">Description</th>
                        <th scope="col">Created By</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php 
                    // ✅ Only fetch logged-in user's accounts
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
                    <tr class="text-center1">
                        <td id="accountID-<?= $accountID ?>"><?php echo $accountID ?></td>
                        <td id="created_at-<?= $accountID ?>"><?php echo $created_at ?></td>
                        <td id="accountName-<?= $accountID ?>"><?php echo $accountName ?></td>
                        <td id="username-<?= $accountID ?>"><?php echo $username ?></td>
                        <td id="password-<?= $accountID ?>" style="display:none;"><?php echo $password ?></td>
                        <td>
                            <input class="password-input" type="password" value="<?php echo $password ?>" onclick="togglePasswordVisibility(<?php echo $accountID ?>)" id="password-input-<?= $accountID ?>" readonly>
                        </td>
                        <td id="link-<?= $accountID ?>"><a href="<?php echo $link ?>" target="_blank"><?php echo $link ?></a></td>
                        <td id="description-<?= $accountID ?>"><?php echo $description ?></td>
                        <td id="created_by-<?= $accountID ?>"><?php echo $created_by ?></td>
                        <td class="text-center">
                            <div class="action-buttons">
                                <!-- Edit Button -->
                                <button class="btn btn-edit" onclick="update_account(<?php echo $accountID ?>)" title="Edit">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                               <button class="btn btn-delete" 
        onclick="delete_account(<?php echo $accountID ?>, '<?php echo addslashes($accountName) ?>')">
    <i class="fa-solid fa-trash"></i> Delete
</button>

                            </div>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
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
<script>
function view_user(userId) {
    let myModal = new bootstrap.Modal(document.getElementById('viewUserModal'));
    myModal.show();

    resetUserModal();

    // Clear old data
    document.getElementById("userID").value = "";
    document.getElementById("name").value = "";
    document.getElementById("phoneNumber").value = "";
    document.getElementById("emailAddress").value = "";
    document.getElementById("createUsername").value = "";
    document.getElementById("createPassword").value = "";

    // Fetch fresh data
    fetch("./endpoint/get-user.php?id=" + userId)
        .then(res => res.json())
        .then(data => {
            if (data) {
                document.getElementById("userID").value          = data.tbl_user_id;
                document.getElementById("name").value            = data.name;
                document.getElementById("phoneNumber").value     = data.phone_number;   // ✅ match DB
                document.getElementById("emailAddress").value    = data.email_address;  // ✅ match DB
                document.getElementById("createUsername").value  = data.username;
                document.getElementById("createPassword").value  = data.password;
            } else {
                console.error("No user found!");
            }
        })
        .catch(err => console.error("Error loading user:", err));
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



