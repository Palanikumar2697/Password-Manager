


document.addEventListener('DOMContentLoaded', function () {
  const loginForm        = document.querySelector('.login-form');
  const registrationForm = document.querySelector('.registration-form');
  const toRegisterLink   = document.querySelector('.show-login-form');          // "No Account? Register Here!"
  const toLoginLink      = document.querySelector('.show-registration-form');   // "Already have an account? Log in here!"

  if (!loginForm || !registrationForm) return;

  // Show login by default
  loginForm.style.display = 'block';
  registrationForm.style.display = 'none';

  // When clicking "No Account? Register Here!" → show Registration form
  if (toRegisterLink) {
    toRegisterLink.addEventListener('click', function (e) {
      e.preventDefault();
      loginForm.style.display = 'none';
      registrationForm.style.display = 'block';
    });
  }

  // When clicking "Already have an account? Log in here!" → show Login form
  if (toLoginLink) {
    toLoginLink.addEventListener('click', function (e) {
      e.preventDefault();
      registrationForm.style.display = 'none';
      loginForm.style.display = 'block';
    });
  }
});


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















