<?php include('./partials/header.php') ?>
    
    <div class="main">
        <div class="login-container">
            <i class="fa-solid fa-user-lock lock-icon"></i>
            <h2 class="text-center mb-5">Password Manager App</h2>
            <div class="login-form">
                <h5 class="text-center">Login Form</h5>
                <form action="./endpoint/login.php" method="POST">
                    <div class="form-group">
                        
                        <i class="fa-solid fa-user"></i>
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter UserName">
                    </div>
                    <div class="form-group">
                        <i class="fa-solid fa-lock"></i>
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                        <small class="show-login-form link-show">No Account? Register Here!</small>
                    </div>
                    <button type="submit" class="form-control btn btn-dark">Submit</button>
                </form>
            </div>

            <div class="registration-form" style="display: none;">
                <h5 class="text-center">Registration Form</h5>
                <form action="./endpoint/add-user.php" method="POST">
                    <div class="form-group">
                        <i class="fa-solid fa-user-tie"></i>
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Valid Name">
                    </div>
                    <div class="form-group">
                        <i class="fa-solid fa-phone"></i>
                        <label for="phoneNumber">Phone Number</label>
                        <input type="text" class="form-control" id="phoneNumber" name="phone_number"
       pattern="[0-9]{10}" maxlength="10" minlength="10" 
       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
       placeholder="Enter 10 digit phone number" required>

                    </div>
                    <div class="form-group">
                        <i class="fa-solid fa-envelope"></i>
                        <label for="emailAddress">Email Address</label>
                        <input type="text" class="form-control" id="emailAddress" name="email_address" placeholder="Enter Valid Email">
                    </div>
                    <div class="form-group">
                        <i class="fa-solid fa-users"></i>
                        <label for="createUsername">Username</label>
                        <input type="text" class="form-control" id="createUsername" name="username"placeholder="Enter UserName">
                    </div>
                    <div class="form-group">
                        <i class="fa-solid fa-lock"></i>
                        <label for="createPassword">Password</label>
     <div class="password-wrapper">

  <input type="password" class="form-control" id="createPassword" name="password"placeholder="Enter Password">
  <i class="fa-solid fa-eye-slash right-icon" id="togglePassword"></i>
</div>


                        <small class="show-registration-form link-show">Already have an account? Log in here!</small>
                    </div>
                    <button type="submit" class="form-control btn btn-dark">Create Account</button>
                </form>
            </div>
        </div>
    </div>


<?php include('./partials/footer.php') ?>