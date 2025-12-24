<?php
// home.php - cleaned & ready
// --------------------------------------------------
// Session, flash, and modal handling
// --------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


date_default_timezone_set('Asia/Kolkata');

$timeout = 600; // 10 minutes

// Set login time once
if (!isset($_SESSION['login_time'])) {
    $_SESSION['login_time'] = time();
}

// Track last activity
if (!isset($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
}

// Auto logout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {

    session_unset();
    session_destroy();
    header("Location: /PM/password-manager-app/index.php?timeout=1");
    exit;
}




// Read & clear flash message (login success, etc.)
$flashStatus = $_SESSION['flash_status'] ?? null;
$flashMsg    = $_SESSION['flash_msg'] ?? null;
unset($_SESSION['flash_status'], $_SESSION['flash_msg']);

// Read & clear modal (showModal1 helper)
$modal = $_SESSION['modal'] ?? null;
unset($_SESSION['modal']);

// DB connection
include('./conn/conn.php');
require_once __DIR__ . '/config/crypto.php';


// Fetch user details
$row = null;
$user_name = "My Account";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM `tbl_user` WHERE `tbl_user_id` = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_name = $row['name'] ?? $user_name;
    }
} else {
    // Not logged in → redirect
    header("Location: ../index.php");
    exit;
}

// Include header / partials (assumed to include Bootstrap CSS & JS, FontAwesome, etc.)
include('./partials/header.php');
include('./partials/modal.php');
?>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm px-3">
  <div class="container-fluid">

    <!-- Brand -->
    <a class="navbar-brand fw-bold" href="home.php">
      <i class="fa-solid fa-shield-halved me-2"></i>
      Password Manager
    </a>

    <!-- Mobile Menu Button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#mainNavbar" aria-controls="mainNavbar"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu Items -->
    <div class="collapse navbar-collapse" id="mainNavbar">

      <ul class="navbar-nav ms-auto align-items-lg-center">

        <!-- Welcome Text -->
        <li class="nav-item me-2 d-none d-lg-inline">
          <span class="nav-link text-light">
            Welcome, <strong><?= htmlspecialchars($user_name) ?></strong>
          </span>
        </li>

        <!-- Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center text-light"
             href="#" id="userMenu" role="button"
             data-bs-toggle="dropdown" aria-expanded="false">

            <i class="fa-solid fa-circle-user me-2" style="font-size: 1.4rem;"></i>
          </a>

          <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">

            <li>
              <button class="dropdown-item" onclick="view_user(<?= (int)$user_id ?>)">
                <i class="fa-regular fa-user me-2"></i> View Account
              </button>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
  <form action="endpoint/logout.php" method="POST" class="m-0 p-0">
    <button type="submit" class="dropdown-item">
      <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
    </button>
  </form>
</li>


          </ul>
        </li>

      </ul>

    </div>
  </div>
</nav>



<!-- SweetAlert2 (needed for flash & modal alerts) -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Flash Message (placed right after navbar - Option A) -->
<?php if (!empty($flashStatus) && !empty($flashMsg)): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: "<?= addslashes($flashStatus) ?>",  // expected values: success | error | warning | info
        title: "<?= ($flashStatus === 'success') ? 'Success' : 'Notice' ?>",
        html: "<?= addslashes($flashMsg) ?>",
        timer: 2200,
        showConfirmButton: false
    });
});
</script>
<?php endif; ?>

<!-- If modal message exists: show via SweetAlert2 -->
<?php if ($modal): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: "<?= addslashes($modal['type']) ?>",
        title: "<?= addslashes($modal['title']) ?>",
        html: "<?= addslashes($modal['message']) ?>",
        showConfirmButton: false,
        timer: 2500
    });
});
</script>
<?php endif; ?>

<!-- Hidden user data (for client-side view_user etc.) -->
<?php if (!empty($row)): ?>
    <span id="userName-<?= (int)$user_id ?>" class="d-none"><?= htmlspecialchars($row['name']) ?></span>
    <span id="userPhone-<?= (int)$user_id ?>" class="d-none"><?= htmlspecialchars($row['phone_number'] ?? '') ?></span>
    <span id="userEmail-<?= (int)$user_id ?>" class="d-none"><?= htmlspecialchars($row['email_address'] ?? '') ?></span>
    <span id="userUsername-<?= (int)$user_id ?>" class="d-none"><?= htmlspecialchars($row['username']) ?></span>
    <span id="userPassword-<?= (int)$user_id ?>" class="d-none"><?= htmlspecialchars($row['password']) ?></span>
<?php endif; ?>

<!-- Accounts Section -->
<div class="container-fluid py-4">
  <div class="accounts-container card shadow-lg p-4 rounded-3">
    <h4 class="text-center mb-4">
      <strong><?= htmlspecialchars($user_name) ?>'s Accounts</strong>
    </h4>
    <?php
date_default_timezone_set('Asia/Kolkata');
?>

<?php if (!empty($_SESSION['login_time'])): ?>

<div class="session-pill active p-3 mb-3">

    <!-- User Status -->
    <div class="d-flex justify-content-end align-items-center small mb-2 user-status">
        <span class="me-2 fw-semibold text-muted">User Status:</span>
        <i id="statusIcon" class="fa-solid fa-circle text-success me-1"></i>
        <strong id="userStatus">Online</strong>
    </div>

    <!-- Login Info -->
    <div class="row text-muted small mb-2">
        <div class="col-md-6 text-start text-center text-md-start">
            <i class="fa-regular fa-clock me-1"></i>
            Logged in at:
            <strong><?= date('d M Y, h:i A', $_SESSION['login_time']) ?></strong>
        </div>

        <?php if (!empty($row['last_login'])): ?>
        <div class="col-md-6 text-end text-center text-md-end">
            <i class="fa-solid fa-rotate-left me-1"></i>
            Last login:
            <strong><?= date('d M Y, h:i A', strtotime($row['last_login'])) ?></strong>
        </div>
        <?php endif; ?>
    </div>

    <!-- Session Timers -->
    <div class="text-center text-muted small mb-1">
        <i class="fa-solid fa-hourglass-half me-1"></i>
        Session duration:
        <strong><span id="sessionTimer">00:00:00</span></strong>
    </div>

    <div class="text-center text-muted small mb-2">
        <i class="fa-solid fa-clock-rotate-left me-1"></i>
        Session expires in:
        <strong><span id="sessionExpire">10:00</span></strong>
    </div>

  <div class="session-progress-wrapper mt-3">
    <div class="session-progress">
        <div id="sessionProgress">100%</div>
    </div>
</div>


</div>

<?php endif; ?>




    <!-- Accounts Table -->
    <div class="table-responsive">
      
    <!-- Buttons Container -->
<div class="d-flex justify-content-end gap-2 mb-3">

    <!-- Filters Button -->
    <button class="btn btn-outline-secondary" id="filterToggleBtn">
        <i class="fa-solid fa-filter me-2"></i> Filters
    </button>

    <!-- Add Account Button -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
        <i class="fa-solid fa-users me-2"></i> Add Account
    </button>

</div>

<!-- FILTER PANEL (Moved OUTSIDE the button row) -->
<div id="filterPanel"
     class="border rounded p-3 mb-3"
     style="display:none; width:100%;">

    <h6 class="fw-bold mb-3">Filter Options</h6>

    <!-- DATE RANGE -->
    <div class="mb-3">
        <label class="form-label fw-semibold">Date Range</label>
        <div class="d-flex gap-2">
            <input type="date" id="fDateFrom" class="form-control">
            <input type="date" id="fDateTo" class="form-control">
        </div>
    </div>

    <!-- ACCOUNT NAME -->
    <div class="mb-3">
        <label class="form-label fw-semibold">Account Name</label>
        <input type="text" id="fAccountName" class="form-control" placeholder="Type...">
    </div>

    <!-- USERNAME -->
    <div class="mb-3">
        <label class="form-label fw-semibold">Username</label>
        <select id="fUserName" class="form-select">
            <option value="">All</option>
        </select>
    </div>

    <!-- CREATED BY -->
    <div class="mb-3">
        <label class="form-label fw-semibold">Created By</label>
        <select id="fCreateBy" class="form-select">
            <option value="">All</option>
        </select>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-light btn-sm px-3" id="resetFilters">Reset</button>
        <button class="btn btn-primary btn-sm px-3" id="applyFilters">Apply</button>
    </div>
</div>

</div>


    
      <table id="accountsTable" class="table table-bordered table-hover align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Account Name</th>
            <th>Username</th>
            <th>Password</th>
            <th>URL</th>
            <th>Description</th>
            <th>Created By</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $user_role = $row['role'] ?? 'Admin'; // Make sure you store role in session or DB

if ($user_role === 'Admin') {
    // Admin sees all
    $stmt = $conn->prepare("
        SELECT a.*, u.name AS created_by_name
        FROM tbl_accounts a
        LEFT JOIN tbl_user u ON a.tbl_user_id = u.tbl_user_id
        ORDER BY a.created_at DESC
    ");
    $stmt->execute();
} else {
    // User sees only own accounts
    $stmt = $conn->prepare("
        SELECT a.*, u.name AS created_by_name
        FROM tbl_accounts a
        LEFT JOIN tbl_user u ON a.tbl_user_id = u.tbl_user_id
        WHERE a.tbl_user_id = :user_id
        ORDER BY a.created_at DESC
    ");
    $stmt->execute(['user_id' => $user_id]);
}
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($accounts) {
          foreach ($accounts as $acct):
            $accountID   = (int)$acct['tbl_account_id'];
            $created_at  = $acct['created_at'];
            $accountName = $acct['account_name'];
            $uname       = $acct['username'];
            $pwd = decryptPassword($acct['password']);
            $link        = $acct['link'];
            $description = $acct['description'];
            $created_by  = $acct['created_by_name'];
        ?>
        <tr id="row-<?= $accountID ?>">
          <td><?= $accountID ?></td>
       <td id="created_at-<?= $accountID ?>">
    <?= htmlspecialchars($created_at) ?>
</td>

          <td id="accountName-<?= $accountID ?>"><?= htmlspecialchars($accountName) ?></td>
          <td id="username-<?= $accountID ?>"><?= htmlspecialchars($uname) ?></td>
          <td class="password-cell">
            <div class="position-relative input-group-sm">
              <input type="password" class="form-control text-center password-field"
                     value="••••••" readonly id="input-<?= $accountID ?>"
                     data-target="<?= $accountID ?>" style="cursor: pointer;">
            </div>
            <span class="d-none real-password" data-password="<?= htmlspecialchars($pwd) ?>"></span>
          </td>
          <td id="link-<?= $accountID ?>">
            <a href="<?= htmlspecialchars($link) ?>" target="_blank" class="text-decoration-none">
              <?= htmlspecialchars($link) ?>
            </a>
          </td>
          <td id="description-<?= $accountID ?>"><?= htmlspecialchars($description) ?></td>
          <td><?= htmlspecialchars($created_by) ?></td>
          <td>
            <div class="d-flex justify-content-center gap-2 table-actions">
              <button class="btn btn-sm btn-warning" onclick="update_account(<?= $accountID ?>)" title="Edit">
                <i class="fa-solid fa-pen me-1"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(<?= $accountID ?>, '<?= addslashes($accountName) ?>')" title="Delete">
                <i class="fa-solid fa-trash me-1"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php
          endforeach;
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

<!-- Modals: statusModal, deleteConfirmModal, etc. -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="statusMessage"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

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

<!-- JS scripts -->
<script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 5 bundle (includes Popper) - ensure your partial/header.php doesn't duplicate -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

const loginTime = <?= $_SESSION['login_time'] * 1000 ?>;

function updateSessionTimer() {
    const now = new Date().getTime();
    let diff = Math.floor((now - loginTime) / 1000);

    const hrs = String(Math.floor(diff / 3600)).padStart(2, '0');
    diff %= 3600;
    const mins = String(Math.floor(diff / 60)).padStart(2, '0');
    const secs = String(diff % 60).padStart(2, '0');

    document.getElementById("sessionTimer").textContent =
        `${hrs}:${mins}:${secs}`;
}

setInterval(updateSessionTimer, 1000);
updateSessionTimer();

const SESSION_TIMEOUT = 600; // seconds
let lastActivityTS = <?= $_SESSION['last_activity'] * 1000 ?>;


function updateExpiryTimer() {
    const now = Date.now();
    let remaining = SESSION_TIMEOUT - Math.floor((now - lastActivityTS) / 1000);

    if (remaining <= 0) {
        window.location.href = "/PM/password-manager-app/index.php?timeout=1";
        return;
    }

    const mins = String(Math.floor(remaining / 60)).padStart(2, '0');
    const secs = String(remaining % 60).padStart(2, '0');

    document.getElementById("sessionExpire").textContent = `${mins}:${secs}`;
}

setInterval(updateExpiryTimer, 1000);
updateExpiryTimer();

const progressBar = document.getElementById("sessionProgress");

function updateSessionProgress() {
    const now = Date.now();
    const elapsed = Math.floor((now - lastActivityTS) / 1000);
    const percent = Math.max(0, Math.min(100, 100 - (elapsed / SESSION_TIMEOUT) * 100));

    const rounded = Math.ceil(percent);

    progressBar.style.width = rounded + "%";
    progressBar.textContent = rounded + "%";

    /* Change color when almost expired */
    if (rounded <= 20) {
        progressBar.style.background = "linear-gradient(90deg, #dc3545, #b02a37)";
        progressBar.style.boxShadow = "0 0 8px rgba(220,53,69,0.6)";
    }
}



setInterval(updateSessionProgress, 1000);
updateSessionProgress();



let lastUserAction = Date.now();

const sessionPill = document.querySelector(".session-pill");

function setStatus(active) {
    const status = document.getElementById("userStatus");
    const icon = document.getElementById("statusIcon");

    if (active) {
        status.textContent = "Online";
        icon.className = "fa-solid fa-circle text-success me-1";

        sessionPill.classList.add("active");
        sessionPill.classList.remove("idle");
    } else {
        status.textContent = "Idle";
        icon.className = "fa-solid fa-circle text-warning me-1";

        sessionPill.classList.add("idle");
        sessionPill.classList.remove("active");
    }

    if (active) {
    progressBar.style.background = "linear-gradient(90deg, #2ecc71, #27ae60)";
} else {
    progressBar.style.background = "linear-gradient(90deg, #f1c40f, #f39c12)";
}

}


// Track activity
['mousemove','keydown','click','scroll'].forEach(evt => {
    document.addEventListener(evt, () => {
        lastUserAction = Date.now();
        setStatus(true);
    });
});

// Idle checker (1 min)
setInterval(() => {
    const idleTime = Date.now() - lastUserAction;

    if (idleTime > 60000) {
        setStatus(false);
    } else {
        setStatus(true);
    }
}, 5000);




const WARNING_TIME = 60; // 1 minute before logout
let warningShown = false;

function checkSessionWarning() {
    const now = Date.now();
    const elapsed = Math.floor((Date.now() - lastActivityTS) / 1000);
  
    const remaining = SESSION_TIMEOUT - elapsed;

    // Show warning at 1 minute remaining
    if (remaining <= WARNING_TIME && remaining > 0 && !warningShown) {
        warningShown = true;

        Swal.fire({
            icon: 'warning',
            title: 'Session Expiring Soon!',
            html: `You will be logged out in <b>${remaining}</b> seconds.<br><br>Do you want to stay logged in?`,
            showCancelButton: true,
            confirmButtonText: 'Stay Logged In',
            cancelButtonText: 'Logout Now',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#dc3545',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Refresh session
               fetch("endpoint/keep-alive.php")
.then(() => {
    lastActivityTS = Date.now(); // ✅ correct
    lastUserAction = Date.now();
    warningShown = false;
    setStatus(true);
});
            } else {
                window.location.href = "endpoint/logout.php";
            }
        });
    }
}

setInterval(checkSessionWarning, 5000);

let keepAliveTimer = null;

function pingServer() {
    fetch("endpoint/keep-alive.php", { method: "GET" })
        .then(() => {
            lastActivityTS = Date.now(); // sync JS with PHP
        });
}

// Call keep-alive every 30 seconds ONLY if user is active
['mousemove','keydown','click','scroll'].forEach(evt => {
    document.addEventListener(evt, () => {
        lastUserAction = Date.now();
        setStatus(true);

        // throttle server pings
        if (!keepAliveTimer) {
            keepAliveTimer = setTimeout(() => {
                pingServer();
                keepAliveTimer = null;
            }, 30000); // 30 sec
        }
    });
});


// Toggle SHOW / HIDE filter panel
document.getElementById("filterToggleBtn").addEventListener("click", function () {
    const panel = document.getElementById("filterPanel");

    // Toggle display
    if (panel.style.display === "none" || panel.style.display === "") {
        panel.style.display = "block";
    } else {
        panel.style.display = "none";
    }
});






// Close filter panel after clicking APPLY
document.getElementById("applyFilters").addEventListener("click", function () {
    document.getElementById("filterPanel").style.display = "none";
});

// Reset filter values (optional)
document.getElementById("resetFilters").addEventListener("click", function () {
    document.getElementById("fDateFrom").value = "";
    document.getElementById("fDateTo").value = "";
    document.getElementById("fAccountName").value = "";
    document.getElementById("fUserName").value = "";
    document.getElementById("fCreateBy").value = "";
});


// Confirm delete with SweetAlert and fetch delete endpoint
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
            fetch("endpoint/delete-account.php?id=" + encodeURIComponent(id))
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
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

// Populate update modal fields and show modal
function update_account(id) {
    // Ensure update modal element exists
    const updateModalEl = document.getElementById("updateAccountModal");
    if (!updateModalEl) {
        console.warn("updateAccountModal not found in DOM.");
        return;
    }
    const modal = new bootstrap.Modal(updateModalEl);
    // Populate values safely
    const accountName = document.getElementById("accountName-" + id)?.textContent.trim() || "";
    const username = document.getElementById("username-" + id)?.textContent.trim() || "";
    const link = document.getElementById("link-" + id)?.textContent.trim() || "";
    const description = document.getElementById("description-" + id)?.textContent.trim() || "";

    // Set fields if they exist
    const setIf = (selector, value) => {
        const el = document.querySelector(selector);
        if (el) el.value = value;
    };

    setIf("#updateAccountID", id);
    setIf("#updateAccountName", accountName);
    setIf("#updateUsername", username);
    // Password field might be hidden; we attempt to fetch data-password from .real-password span
    const pwdSpan = document.querySelector("#row-" + id + " .real-password");
    if (pwdSpan) setIf("#updatePassword", pwdSpan.getAttribute("data-password") || "");

    setIf("#updateLink", link);
    setIf("#updateDescription", description);

    // created_at handling (if present)
    const createdAt = document.getElementById("created_at-" + id)?.textContent.trim();
    if (createdAt) {
        const formatted = createdAt.replace(" ", "T").slice(0, 16);
        setIf("#updateCreatedAt", formatted);
    }

    modal.show();
}
</script>


<script>
document.querySelectorAll('.password-field').forEach(function(input){
  input.addEventListener('click', function(){
    const cell = this.closest('.password-cell');
    const realPasswordSpan = cell.querySelector('.real-password');
    const realPassword = realPasswordSpan.getAttribute('data-password');

    if(this.type === 'password'){
      // Show real password
      this.type = 'text';
      this.value = realPassword;
    } else {
      // Hide password (bullets)
      this.type = 'password';
      this.value = '••••••';
    }
  });
});
</script>


<?php include('./partials/footer.php'); ?>
