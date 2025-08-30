<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="userModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModal">User Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="./endpoint/update-user.php" method="POST">
                    <?php 
                        $stmt = $conn->prepare("SELECT * FROM `tbl_user` WHERE `tbl_user_id` = $user_id");
                        $stmt->execute();
                        $result = $stmt->fetchAll();

                        foreach ($result as $row) {
                            $userID = $row['tbl_user_id'];
                            $name = $row['name'];
                            $phoneNumber = $row['phone_number'];
                            $emailAddress = $row['email_address'];
                            $username = $row['username'];
                            $password = $row['password'];
                        ?>
                        <input type="text" class="form-control" id="userID" name="tbl_user_id" value="<?php echo $userID ?>" hidden>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control user-detail" id="name" name="name" value="<?php echo $name ?>" disabled>
                        </div>
                        <div class="form-group">
    <label for="phoneNumber">Phone Number</label>
    <input 
        type="text" 
        class="form-control user-detail" 
        id="phoneNumber" 
        name="phone_number" 
        value="<?php echo $phoneNumber ?>" 
        pattern="\d{10}" 
        maxlength="10" 
        minlength="10"
        title="Please enter exactly 10 digits"
        disabled
    >
</div>

                        <div class="form-group">
                            <label for="emailAddress">Email Address</label>
                            <input type="text" class="form-control user-detail" id="emailAddress" name="email_address" value="<?php echo $emailAddress ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="createUsername">Username</label>
                            <input type="text" class="form-control user-detail" id="createUsername" name="username" value="<?php echo $username ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="createPassword">Password</label>
                            <input type="text" class="form-control user-detail" id="createPassword" name="password" value="<?php echo $password ?>" disabled>
                        </div>
    
                        <button type="submit" class="col-3 form-control btn btn-danger" id="deleteButton" style="display:none;" onclick="deleteUser(<?php echo $userID ?>)">Delete Account</button>
                        <button type="submit" class="col-3 form-control btn btn-dark float-right" id="saveButton" style="display:none;">Save Changes</button>
                        <button type="button" class="col-2 mr-2 form-control btn btn-secondary float-right" id="cancelButton" style="display:none;" onclick="cancelEditDetails(this)">Cancel</button>
                        <button type="button" class="col-3 form-control btn btn-dark float-right" id="editButton" onclick="editDetails(this)">Edit Details</button>
                    <?php
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Account Modal -->
<div class="modal fade mt-5" id="addAccountModal" tabindex="-1" aria-labelledby="addAccount" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAccount">Add Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="./endpoint/add-account.php" method="POST">
                    <div class="form-group">
                        <i class="fa-solid fa-user"></i>
                        <label for="accountName">Account Name</label>
                        <input type="text" class="form-control" id="accountName" name="account_name"placeholder="Enter Account Name">
                    </div>
                    <div class="form-group">
                        <i class="fa-solid fa-users"></i>
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username"placeholder="Enter Username">
                    </div>
         <div class="form-group password-wrapper">
    <label class="password-label">
        <i class="fa-solid fa-lock"></i> Password
    </label>
    <div class="input-container">
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
        <i class="fa-solid fa-eye-slash toggle-password" id="togglePassword2"></i>
    </div>
</div>



                    <div class="form-group">
                         <i class="fa-solid fa-link"></i>
                        <label for="link">Link</label>
                       
                        <input type="text" class="form-control" id="link" name="link" placeholder="Enter Link">
                    </div>
                    <div class="form-group">
                          <i class="fa-solid fa-file-prescription"></i>
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="5"placeholder="Description"></textarea>
                    </div>
                    <div class="form-group">
    <label for="created_at">Date & Time</label>
    <input type="datetime-local" name="created_at" class="form-control" required>
</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Save Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Account Modal -->
<div class="modal fade mt-5" id="updateAccountModal" tabindex="-1" aria-labelledby="updateAccount" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateAccount">Update Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="./endpoint/update-account.php" method="POST">
                    <input type="text" id="updateAccountID" name="tbl_account_id" hidden>
                    <div class="form-group">
                        <label for="updateAccountName">Account Name</label>
                        <input type="text" class="form-control" id="updateAccountName" name="account_name">
                    </div>
                    <div class="form-group">
                        <label for="updateUsername">Username</label>
                        <input type="text" class="form-control" id="updateUsername" name="username">
                    </div>
                    <div class="form-group">
                        <label for="updatePassword">Password</label>
                        <input type="text" class="form-control" id="updatePassword" name="password">
                    </div>
                    <div class="form-group">
                        <label for="updateLink">Link</label>
                        <input type="text" class="form-control" id="updateLink" name="link">
                    </div>
                    <div class="form-group">
                        <label for="updateDescription">Description</label>
                        <textarea class="form-control" name="description" id="updateDescription" rows="5"></textarea>
                    </div>
                    <div class="form-group">
    <label for="created_at">Date & Time</label>
    <input type="datetime-local" name="created_at" class="form-control" required>
</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Save changes</button>
                    </div>
                </form>
            </div>
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
