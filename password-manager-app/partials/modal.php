
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

          <div class="form-group mb-3">
            <label class="form-label">
              <i class="fa-solid fa-user me-2"></i> Name
            </label>
            <input type="text" id="name" name="name" class="form-control user-detail" disabled>
          </div>

          <div class="form-group mb-3">
            <label class="form-label">
              <i class="fa-solid fa-phone me-2"></i> Phone
            </label>
            <input type="text" id="phoneNumber" name="phoneNumber" class="form-control user-detail" disabled>
          </div>

          <div class="form-group mb-3">
            <label class="form-label">
              <i class="fa-solid fa-envelope me-2"></i> Email
            </label>
            <input type="email" id="emailAddress" name="emailAddress" class="form-control user-detail" disabled>
          </div>

          <div class="form-group mb-3">
            <label class="form-label">
              <i class="fa-solid fa-user-tag me-2"></i> Username
            </label>
            <input type="text" id="createUsername" name="username" class="form-control user-detail" disabled>
          </div>

          <div class="mb-3">
  <label for="updatePassword" class="form-label">
    <i class="fa-solid fa-lock"></i> Password
  </label>
  <div class="input-group">
    <input type="password" 
           class="form-control" 
           id="createPassword"
           name="password" 
           placeholder="Enter Password" 
           required>
    <button class="btn btn-outline-secondary toggle-password" type="button">
      <i class="fa-solid fa-eye-slash"></i>
    </button>
  </div>
</div>

        </div>

        <div class="modal-footer">
  <button type="button" id="editButton" class="btn btn-primary" onclick="editDetails()">
    <i class="fa-solid fa-pen"></i> Edit
  </button>
  <button type="submit" id="saveButton" class="btn btn-success d-none">
    <i class="fa-solid fa-floppy-disk"></i> Save
  </button>
  <button type="button" id="cancelButton" class="btn btn-secondary d-none" onclick="cancelEditDetails()">
    <i class="fa-solid fa-xmark"></i> Cancel
  </button>
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

 <div class="mb-3 position-relative">
  <label for="password" class="form-label">
    <i class="fa-solid fa-lock"></i> Password
  </label>
  
  <input type="password" 
         class="form-control pe-5 password-field" 
         id="password"
         name="password" 
         placeholder="Enter Password" 
         required>

  <!-- Eye icon inside input -->
  <i class="fa-solid fa-eye-slash toggle-password"
     style="position: absolute; top: 70%; right: 12px; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
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


  

<!-- Update Account Modal -->
<div class="modal fade" id="updateAccountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Update Account</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="./endpoint/update-account.php" method="POST" autocomplete="off">
  <div class="modal-body">
    <input type="hidden" id="updateAccountID" name="tbl_account_id">

    <div class="mb-3">
      <label for="updateAccountName" class="form-label">
        <i class="fa-solid fa-address-book me-1"></i> Account Name
      </label>
      <input type="text" id="updateAccountName" name="account_name" class="form-control" autocomplete="off">
    </div>

    <div class="mb-3">
      <label for="updateUsername" class="form-label">
        <i class="fa-solid fa-user me-1"></i> Username
      </label>
      <input type="text" id="updateUsername" name="username" class="form-control" autocomplete="off">
    </div>

   <div class="mb-3 position-relative">
  <label for="updatePassword" class="form-label">
    <i class="fa-solid fa-lock me-1"></i> Password
  </label>

  <input type="password" 
         class="form-control pe-5 password-field" 
         id="updatePassword"
         name="password" 
         placeholder="Enter Password" 
         required>

  <!-- Eye icon inside input -->
  <i class="fa-solid fa-eye-slash toggle-password"
     style="position: absolute; top: 70%; right: 12px; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
</div>


    <div class="mb-3">
      <label for="updateLink" class="form-label">
        <i class="fa-solid fa-link me-1"></i> URL
      </label>
      <input type="url" id="updateLink" name="link" class="form-control" autocomplete="off">
    </div>

    <div class="mb-3">
      <label for="updateDescription" class="form-label">
        <i class="fa-solid fa-file-lines me-1"></i> Description
      </label>
      <textarea id="updateDescription" name="description" class="form-control"></textarea>
    </div>

    <div class="mb-3">
      <label for="updateCreatedAt" class="form-label">
        <i class="fa-solid fa-calendar-days me-1"></i> Created At
      </label>
      <input type="datetime-local" id="updateCreatedAt" name="created_at" class="form-control">
    </div>
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn btn-success">
      <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
    </button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
      <i class="fa-solid fa-xmark me-1"></i> Cancel
    </button>
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

    // Get hidden values
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

    // Fill modal fields
    document.getElementById("userID").value = userId;
    document.getElementById("name").value = name;
    document.getElementById("phoneNumber").value = phone;
    document.getElementById("emailAddress").value = email;
    document.getElementById("createUsername").value = username;

    // ✅ FIX: use correct ID for password input
    let passInput = document.getElementById("createPassword");
    if (passInput) passInput.value = password;

    // Show modal
    $("#viewUserModal").modal("show");
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".toggle-password").forEach(icon => {
    icon.addEventListener("click", function () {
      const input = this.previousElementSibling; // always the input before icon

      if (input.type === "password") {
        input.type = "text";
        this.classList.replace("fa-eye-slash", "fa-eye");
      } else {
        input.type = "password";
        this.classList.replace("fa-eye", "fa-eye-slash");
      }
    });
  });
});
</script>















