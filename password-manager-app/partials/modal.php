
<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="userModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="userModal">User Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>

      <!-- ✅ Form starts here -->
      <form action="./endpoint/update-user.php" method="POST">
        <div class="modal-body">

          <input type="hidden" id="userID" name="tbl_user_id">

          <div class="form-group">
            <label>Name</label>
            <input type="text" id="name" name="name" class="form-control user-detail" disabled>
          </div>

          <div class="form-group">
            <label>Phone</label>
            <input type="text" id="phoneNumber" name="phoneNumber" class="form-control user-detail" disabled>
          </div>

          <div class="form-group">
            <label>Email</label>
            <input type="email" id="emailAddress" name="emailAddress" class="form-control user-detail" disabled>
          </div>

          <div class="form-group">
            <label>Username</label>
            <input type="text" id="createUsername" name="username" class="form-control user-detail" disabled>
          </div>

       <div class="form-group">
  <label>Password</label>
  <div class="input-group">
    <input type="password" id="createPassword" name="password" class="form-control user-detail" disabled>
    <span class="input-group-text toggle-password" style="cursor: pointer;">
  <i class="fa-solid fa-eye-slash"></i>  <!-- 👈 start with eye-slash -->
</span>
  </div>
</div>


        </div>

        <div class="modal-footer">
          <button type="button" id="editButton" class="btn btn-primary" onclick="editDetails()">Edit</button>
          <button type="submit" id="saveButton" class="btn btn-success d-none">Save</button>
          <button type="button" id="cancelButton" class="btn btn-secondary d-none" onclick="cancelEditDetails()">Cancel</button>
        </div>
      </form>
      <!-- ✅ Form ends here -->

    </div>
  </div>
</div>






<!-- Add Account Modal -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="addAccountLabel">Add Account</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Form -->
      <form action="./endpoint/add-account.php" method="POST">
        <div class="modal-body">

          <div class="mb-3">
            <label for="accountName" class="form-label"><i class="fa-solid fa-user"></i> Account Name</label>
            <input type="text" class="form-control" id="accountName" name="account_name" placeholder="Enter Account Name" required>
          </div>

          <div class="mb-3">
            <label for="username" class="form-label"><i class="fa-solid fa-users"></i> Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
          </div>

     <div class="mb-3">
  <label for="password" class="form-label"><i class="fa-solid fa-lock"></i> Password</label>
  <div class="input-group">
    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
     <span class="input-group-text toggle-password" style="cursor: pointer;">
  <i class="fa-solid fa-eye-slash"></i>  <!-- 👈 start with eye-slash -->
</span>
  </div>
</div>



          <div class="mb-3">
            <label for="link" class="form-label"><i class="fa-solid fa-link"></i> Link</label>
            <input type="text" class="form-control" id="link" name="link" placeholder="Enter Link">
          </div>

          <div class="mb-3">
            <label for="description" class="form-label"><i class="fa-solid fa-file-prescription"></i> Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description"></textarea>
          </div>

          <div class="mb-3">
            <label for="created_at" class="form-label">Date & Time</label>
            <input type="datetime-local" class="form-control" id="created_at" name="created_at" required>
          </div>

        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-dark">Save Account</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>

    </div>
  </div>
</div>


        




<!-- Update Account Modal (Bootstrap 5) -->
<div class="modal fade" id="updateAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="./endpoint/update-account.php">

        <!-- Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Update Account</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <input type="hidden" name="tbl_account_id" id="updateAccountID">

          <div class="mb-3">
            <label for="updateAccountName" class="form-label">
              <i class="fa-solid fa-user"></i> Account Name
            </label>
            <input type="text" name="account_name" id="updateAccountName" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="updateUsername" class="form-label">
              <i class="fa-solid fa-users"></i> Username
            </label>
            <input type="text" name="username" id="updateUsername" class="form-control" required>
          </div>
<div class="mb-3">
  <label for="updatePassword" class="form-label"><i class="fa-solid fa-lock"></i> Password</label>
  <div class="input-group">
    <input type="password" class="form-control" id="updatePassword" name="password" placeholder="Enter Password" required>
     <span class="input-group-text toggle-password" style="cursor: pointer;">
  <i class="fa-solid fa-eye-slash"></i>  <!-- 👈 start with eye-slash -->
</span>
  </div>
</div>


          <div class="mb-3">
            <label for="updateLink" class="form-label">
              <i class="fa-solid fa-link"></i> Link
            </label>
            <input type="text" name="link" id="updateLink" class="form-control">
          </div>

          <div class="mb-3">
            <label for="updateDescription" class="form-label">
              <i class="fa-solid fa-file-alt"></i> Description
            </label>
            <textarea name="description" id="updateDescription" class="form-control" rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label for="updateCreatedAt" class="form-label">
              <i class="fa-solid fa-calendar"></i> Date & Time
            </label>
            <input type="datetime-local" name="created_at" id="updateCreatedAt" class="form-control" required>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save Changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>

      </form>
    </div>
  </div>
</div>









<script>
document.addEventListener("DOMContentLoaded", function () {
    const phoneInput = document.getElementById("phoneNumber");

    // Optional: Enable the field for testing purposes
    // phoneInput.disabled = false;

    // Allow only numbers and limit to 10 digits
    phoneInput.addEventListener("input", function () {
        // Remove non-digit characters
        this.value = this.value.replace(/\D/g, '');

        // Limit to 10 digits
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });

    // Validate on form submission (adjust form ID if needed)
    const form = phoneInput.closest("form");
    if (form) {
        form.addEventListener("submit", function (e) {
            if (!/^\d{10}$/.test(phoneInput.value)) {
                e.preventDefault(); // Stop form submission
                alert("Please enter a valid 10-digit phone number.");
                phoneInput.focus();
            }
        });
    }
});
</script>

<script>
function view_user(userId) {
    console.log("Opening View User Modal for ID:", userId);

    // Get hidden values (from home.php spans)
    let name      = document.getElementById("userName-" + userId)?.innerText.trim();
    let phone     = document.getElementById("userPhone-" + userId)?.innerText.trim();
    let email     = document.getElementById("userEmail-" + userId)?.innerText.trim();
    let username  = document.getElementById("userUsername-" + userId)?.innerText.trim();
    let password  = document.getElementById("userPassword-" + userId)?.innerText.trim();

    if (!name || !email) {
        console.warn("⚠️ Could not fetch user data for ID:", userId);
        alert("User details not found. Please check hidden spans in PHP.");
        return;
    }

    // Fill form fields inside modal
    document.getElementById("userID").value = userId;
    document.getElementById("name").value = name;
    document.getElementById("phoneNumber").value = phone;
    document.getElementById("emailAddress").value = email;
    document.getElementById("createUsername").value = username;
    document.getElementById("createPassword").value = password;

    // ✅ Show correct modal
    $("#viewUserModal").modal("show");
}
</script>


<script>
  document.getElementById("togglePassword").addEventListener("click", function () {
    const passwordInput = document.getElementById("password");
    const icon = this.querySelector("i");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    } else {
      passwordInput.type = "password";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    }
  });
</script>

<script>
document.querySelectorAll('.toggle-password').forEach(toggle => {
  toggle.addEventListener('click', function () {
    const input = this.previousElementSibling; // the <input> before the eye icon
    const icon = this.querySelector("i");

    if (input.type === "password") {
      input.type = "text";
      icon.classList.replace("fa-eye-slash", "fa-eye");
    } else {
      input.type = "password";
      icon.classList.replace("fa-eye", "fa-eye-slash");
    }
  });
});

</script>
<script>
document.querySelectorAll('.toggle-password').forEach(toggle => {
  toggle.addEventListener('click', function () {
    const input = this.closest('.input-group').querySelector('input');
    const icon = this.querySelector("i");

    if (input.type === "password") {
      input.type = "text";
      icon.classList.replace("fa-eye-slash", "fa-eye");
    } else {
      input.type = "password";
      icon.classList.replace("fa-eye", "fa-eye-slash");
    }
  });
});
</script>

<script>
function editDetails() {
  // Enable all fields
  document.querySelectorAll(".user-detail").forEach(input => {
    input.removeAttribute("disabled");
  });

  // Toggle buttons
  document.getElementById("editButton").classList.add("d-none");
  document.getElementById("saveButton").classList.remove("d-none");
  document.getElementById("cancelButton").classList.remove("d-none");
}
</script>
<script>
function cancelEditDetails() {
  // Disable all fields again
  document.querySelectorAll(".user-detail").forEach(input => {
    input.setAttribute("disabled", true);
  });

  // Reset buttons
  document.getElementById("editButton").classList.remove("d-none");
  document.getElementById("saveButton").classList.add("d-none");
  document.getElementById("cancelButton").classList.add("d-none");
}
</script>




