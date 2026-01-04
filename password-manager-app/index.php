<?php
session_start();   // 👈 MUST BE FIRST, before any HTML output
$showRegister = isset($_GET['show']) && $_GET['show'] === 'register';

$modal = $_SESSION['modal'] ?? null;
unset($_SESSION['modal']);
?>

<?php include('./partials/header.php') ?>




<div class="main d-flex justify-content-center align-items-center min-vh-100">
    <div class="login-container card shadow p-4" style="max-width: 500px; width: 100%;">
        <div class="text-center mb-4">
            <i class="fa-solid fa-user-lock lock-icon fa-3x mb-3"></i>
            <h5>Password Manager App</h5>
        </div>

        <!-- Login Form -->
        <div class="login-form" style="<?= $showRegister ? 'display:none;' : 'display:block;' ?>">
            <h5 class="text-center mb-3">Login Form</h5>

            <form action="./endpoint/login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fa-solid fa-user me-2"></i> Username
                    </label>
                    <input type="text" class="form-control" name="username" placeholder="Enter Username">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="fa-solid fa-lock me-2"></i> Password
                    </label>

                    <div class="position-relative">
                        <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Enter Password">
                        <i class="fa-solid fa-eye-slash toggle-password"
                           data-target="loginPassword"
                           style="position:absolute;top:50%;right:12px;transform:translateY(-50%);cursor:pointer;color:#666;"></i>
                    </div>

                    <div class="form-text mt-1">
                        <a class="show-registration-form link-primary" style="cursor:pointer;">No Account? Register Here!</a>
                    </div>
                </div>
                <button type="submit" class="btn btn-dark w-100">Submit</button>
            </form>
        </div>

       <!-- Registration Form -->
<div class="registration-form" style="<?= isset($_SESSION['show_registration']) && $_SESSION['show_registration'] ? 'display:block;' : 'display:none;' ?>">
    <h5 class="text-center mb-3">Registration Form</h5>

    <form action="./endpoint/add-user.php" method="POST" id="registerForm" autocomplete="off" class="needs-validation" novalidate>

        <!-- Name -->
        <div class="mb-3">
            <label class="form-label">
                <i class="fa-solid fa-user-tie me-2"></i> Name
            </label>
            <input type="text" class="form-control <?= isset($_SESSION['errors']['name']) ? 'is-invalid' : '' ?>"
                   name="name" placeholder="Enter Your Name" required
                   value="<?= htmlspecialchars($_SESSION['form_data']['name'] ?? '', ENT_QUOTES) ?>">
            <?php if (isset($_SESSION['errors']['name'])): ?>
                <div class="invalid-feedback d-block">
                    <?= htmlspecialchars($_SESSION['errors']['name'], ENT_QUOTES) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Phone Number -->
        <div class="mb-3">
            <label class="form-label">
                <i class="fa-solid fa-phone me-2"></i> Phone Number <span class="required">*</span>
            </label>
            <input type="tel" class="form-control <?= isset($_SESSION['errors']['phone_number']) ? 'is-invalid' : '' ?>"
                   name="phone_number" placeholder="Enter 10 digit phone number" maxlength="10" minlength="10"
                   pattern="[0-9]{10}" required
                   value="<?= htmlspecialchars($_SESSION['form_data']['phone_number'] ?? '', ENT_QUOTES) ?>">
            <?php if (isset($_SESSION['errors']['phone_number'])): ?>
                <div class="invalid-feedback d-block">
                    <?= htmlspecialchars($_SESSION['errors']['phone_number'], ENT_QUOTES) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label class="form-label">
                <i class="fa-solid fa-envelope me-2"></i> Email Address <span class="required">*</span>
            </label>
            <input type="email" class="form-control <?= isset($_SESSION['errors']['email_address']) ? 'is-invalid' : '' ?>"
                   name="email_address" placeholder="Enter Valid Email" required autocomplete="email"
                   value="<?= htmlspecialchars($_SESSION['form_data']['email_address'] ?? '', ENT_QUOTES) ?>">
            <?php if (isset($_SESSION['errors']['email_address'])): ?>
                <div class="invalid-feedback d-block">
                    <?= htmlspecialchars($_SESSION['errors']['email_address'], ENT_QUOTES) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Username -->
        <div class="mb-3">
            <label class="form-label">
                <i class="fa-solid fa-users me-2"></i> Username <span class="required">*</span>
            </label>
            <input type="text" class="form-control <?= isset($_SESSION['errors']['username']) ? 'is-invalid' : '' ?>"
                   name="username" placeholder="Enter Username" required pattern="[a-zA-Z0-9]{4,}"
                   title="Username must be at least 4 characters, letters & numbers only"
                   value="<?= htmlspecialchars($_SESSION['form_data']['username'] ?? '', ENT_QUOTES) ?>">
            <?php if (isset($_SESSION['errors']['username'])): ?>
                <div class="invalid-feedback d-block">
                    <?= htmlspecialchars($_SESSION['errors']['username'], ENT_QUOTES) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label class="form-label">
                <i class="fa-solid fa-lock me-2"></i> Password <span class="required">*</span>
            </label>
            <div class="position-relative">
                <input type="password" class="form-control <?= isset($_SESSION['errors']['password']) ? 'is-invalid' : '' ?>"
                       id="registerPassword" name="password" placeholder="Enter Password" required minlength="8" autocomplete="new-password">
                
                       
                <?php if (isset($_SESSION['errors']['password'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= htmlspecialchars($_SESSION['errors']['password'], ENT_QUOTES) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label class="form-label">
                <i class="fa-solid fa-lock me-2"></i> Confirm Password <span class="required">*</span>
            </label>
            <div class="position-relative">
                <input type="password" class="form-control <?= isset($_SESSION['errors']['confirmpassword']) ? 'is-invalid' : '' ?>"
                       id="confirmPassword" name="confirmpassword" placeholder="Confirm Password" required minlength="8" autocomplete="new-password">
               
                <?php if (isset($_SESSION['errors']['confirmpassword'])): ?>
                    <div class="invalid-feedback d-block">
                        <?= htmlspecialchars($_SESSION['errors']['confirmpassword'], ENT_QUOTES) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-text mt-1">
                <a class="show-login-form link-primary" style="cursor:pointer;">Already have an account? Login here!</a>
            </div>
        </div>

        <!-- General error (if any) -->
        <?php if (isset($_SESSION['errors']['general'])): ?>
            <div class="alert alert-warning">
                <?= htmlspecialchars($_SESSION['errors']['general'], ENT_QUOTES) ?>
            </div>
        <?php endif; ?>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-dark w-100">Create Account</button>
    </form>
</div>

    </div>
</div>

<script>

    
    // Bootstrap form validation
    (function() {
        'use strict';
        const form = document.getElementById('registerForm');
        form.addEventListener('submit', function(event) {
            // Custom password match validation
            const password = document.getElementById('registerPassword').value;
            const confirm = document.getElementById('confirmPassword').value;

            if (password !== confirm) {
                document.getElementById('confirmPassword').setCustomValidity("Passwords do not match.");
            } else {
                document.getElementById('confirmPassword').setCustomValidity("");
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    })();
    // Toggle Login/Register View
    document.querySelector(".show-registration-form")?.addEventListener("click", () => {
        document.querySelector(".login-form").style.display = "none";
        document.querySelector(".registration-form").style.display = "block";
    });

    document.querySelector(".show-login-form")?.addEventListener("click", () => {
        document.querySelector(".login-form").style.display = "block";
        document.querySelector(".registration-form").style.display = "none";
    });

    // Show/Hide password toggle
    document.querySelectorAll(".toggle-password").forEach(icon => {
        icon.addEventListener("click", function () {
            let target = document.getElementById(this.getAttribute("data-target"));
            if (target.type === "password") {
                target.type = "text";
                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");
            } else {
                target.type = "password";
                this.classList.remove("fa-eye");
                this.classList.add("fa-eye-slash");
            }
        });
    });
   

document.querySelectorAll("#registerPassword, #confirmPassword").forEach(input => {
    input.addEventListener("click", () => {
        input.type = (input.type === "password") ? "text" : "password";
    });
});



</script>

<?php
// Clear the session helpers so errors/old values don't persist on refresh
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}
if (isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}
if (isset($_SESSION['show_registration'])) {
    unset($_SESSION['show_registration']);
}
?>


<?php include('./partials/footer.php') 


?>
<?php if ($modal): ?>
    <?php include "./endpoint/status.php"; ?>
<?php endif; ?>