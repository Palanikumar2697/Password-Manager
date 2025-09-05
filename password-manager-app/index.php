<?php include('./partials/header.php') ?>

<div class="main d-flex justify-content-center align-items-center min-vh-100">
    <div class="login-container card shadow p-4" style="max-width: 420px; width: 100%;">
        <div class="text-center mb-4">
            <i class="fa-solid fa-user-lock lock-icon fa-3x mb-3"></i>
            <h2>Password Manager App</h2>
        </div>

        <!-- Login Form -->
        <div class="login-form">
            <h5 class="text-center mb-3">Login Form</h5>
            <form action="./endpoint/login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">
                        <i class="fa-solid fa-user me-2"></i> Username
                    </label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter UserName">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fa-solid fa-lock me-2"></i> Password
                    </label>
                             <div class="position-relative">
  <input type="password" class="form-control" id="createPassword" name="password" placeholder="Enter Password">
  <i class="fa-solid fa-eye-slash toggle-password"
     style="position: absolute; top: 50%; right: 12px; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
</div>
                    <div class="form-text">
                        <a href="#" class="show-login-form link-primary">No Account? Register Here!</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark w-100">Submit</button>
            </form>
        </div>

        <!-- Registration Form -->
        <div class="registration-form" style="display: none;">
            <h5 class="text-center mb-3">Registration Form</h5>
            <form action="./endpoint/add-user.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="fa-solid fa-user-tie me-2"></i> Name
                    </label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Valid Name">
                </div>

                <div class="mb-3">
                    <label for="phoneNumber" class="form-label">
                        <i class="fa-solid fa-phone me-2"></i> Phone Number
                    </label>
                    <input type="text" class="form-control" id="phoneNumber" name="phone_number"
                           pattern="[0-9]{10}" maxlength="10" minlength="10"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           placeholder="Enter 10 digit phone number" required>
                </div>

                <div class="mb-3">
                    <label for="emailAddress" class="form-label">
                        <i class="fa-solid fa-envelope me-2"></i> Email Address
                    </label>
                    <input type="email" class="form-control" id="emailAddress" name="email_address" placeholder="Enter Valid Email">
                </div>

                <div class="mb-3">
                    <label for="createUsername" class="form-label">
                        <i class="fa-solid fa-users me-2"></i> Username
                    </label>
                    <input type="text" class="form-control" id="createUsername" name="username" placeholder="Enter UserName">
                </div>

                <div class="mb-3">
                    <label for="createPassword" class="form-label">
                        <i class="fa-solid fa-lock me-2"></i> Password
                    </label>
                  <div class="position-relative">
  <input type="password" class="form-control" id="createPassword" name="password" placeholder="Enter Password">
  <i class="fa-solid fa-eye-slash toggle-password"
     style="position: absolute; top: 50%; right: 12px; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
</div>

                    <div class="form-text">
                        <a href="#" class="show-registration-form link-primary">Already have an account? Log in here!</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark w-100">Create Account</button>
            </form>
        </div>
    </div>
</div>

<?php include('./partials/footer.php') ?>
