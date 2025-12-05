


document.addEventListener('DOMContentLoaded', function () {
  const loginForm        = document.querySelector('.login-form');
  const registrationForm = document.querySelector('.registration-form');
  const toRegisterLink   = document.querySelector('.to-register');
  const toLoginLink      = document.querySelector('.to-login');

  if (!loginForm || !registrationForm) return;

  // Read "?show=register" from URL
  const urlParams = new URLSearchParams(window.location.search);
  const showForm = urlParams.get('show');

  if (showForm === "register") {
    loginForm.style.display = 'none';
    registrationForm.style.display = 'block';
  } else {
    loginForm.style.display = 'block';
    registrationForm.style.display = 'none';
  }

  // Switch to Registration
  if (toRegisterLink) {
    toRegisterLink.addEventListener('click', function (e) {
      e.preventDefault();
      loginForm.style.display = 'none';
      registrationForm.style.display = 'block';
    });
  }

  // Switch to Login
  if (toLoginLink) {
    toLoginLink.addEventListener('click', function (e) {
      e.preventDefault();
      registrationForm.style.display = 'none';
      loginForm.style.display = 'block';
    });
  }
});


/*
document.querySelector("registration-form").addEventListener("submit", function (e) {

    const name = document.querySelector("input[name='name']").value.trim();
    const phone = document.querySelector("input[name='phone_number']").value.trim();
    const email = document.querySelector("input[name='email_address']").value.trim();
    const username = document.querySelector("input[name='username']").value.trim();
    const pass = document.getElementById("registerPassword").value.trim();
    const cpass = document.getElementById("confirmPassword").value.trim();

    

    // Phone validation
    const phoneRegex = /^[0-9]{10}$/;
    if (!phoneRegex.test(phone)) {
        alert("Phone number must be exactly 10 digits");
        e.preventDefault();
        return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Enter a valid email address");
        e.preventDefault();
        return;
    }

    // Username validation
    const userRegex = /^[a-zA-Z0-9_]{4,16}$/;
    if (!userRegex.test(username)) {
        alert("Username must be 4–16 characters (letters, numbers, underscore)");
        e.preventDefault();
        return;
    }

    // Password validation
    if (pass.length < 6) {
        alert("Password must be at least 6 characters");
        e.preventDefault();
        return;
    }

    if (pass !== cpass) {
        alert("Passwords do not match");
        e.preventDefault();
        return;
    }
});


*/


function editDetails() {
  // Enable inputs
  document.querySelectorAll("#viewUserModal .user-detail").forEach(el => {
    el.removeAttribute("disabled");
    el.removeAttribute("readonly");
  });

  // Show Save + Cancel, hide Edit
  document.getElementById("editButton").classList.add("d-none");
  document.getElementById("saveButton").classList.remove("d-none");
  document.getElementById("cancelButton").classList.remove("d-none");
}

function cancelEditDetails() {
  // Re-disable inputs
  document.querySelectorAll("#viewUserModal .user-detail").forEach(el => {
    el.setAttribute("disabled", "");
    el.setAttribute("readonly", "");
  });

  // Reset buttons
  document.getElementById("editButton").classList.remove("d-none");
  document.getElementById("saveButton").classList.add("d-none");
  document.getElementById("cancelButton").classList.add("d-none");
}



// Show password
function togglePasswordVisibility(accountID) {
    var passwordInput = document.getElementById("password-input-" + accountID);
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}


function delete_account(accountId) {
    // Direct redirect for delete
    window.location.href = "./endpoint/delete-account.php?id=" + accountId;
}















