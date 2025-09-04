const loginForm = document.querySelector('.login-form');
const registrationForm = document.querySelector('.registration-form');
const showLoginForm = document.querySelector('.show-login-form');
const showRegistrationForm = document.querySelector('.show-registration-form');

// registrationForm.style.display = "none";

function showLogin() {
    loginForm.style.display = "none";
    registrationForm.style.display = "";
}
showLoginForm.addEventListener("click", showLogin);

function showRegistration() {
    loginForm.style.display = "";
    registrationForm.style.display = "none";
}
showRegistrationForm.addEventListener("click", showRegistration);






// Delete account
function delete_account(id) {
    if (confirm("Do you want to delete this account?")) {
        window.location = "./endpoint/delete-account.php?account=" + id;
    }
}

// Enable and disable editing
function editDetails(button) {
    const form = button.form;
    const inputElements = form.querySelectorAll('.user-detail');
    for (var i = 0; i < inputElements.length; i++) {
        inputElements[i].disabled = !inputElements[i].disabled;
    }

    document.getElementById('editButton').style.display = "none";
    document.getElementById('saveButton').style.display = "";
    document.getElementById('deleteButton').style.display = "";
    document.getElementById('cancelButton').style.display = "";
}

function cancelEditDetails(button) {
    const form = button.form;
    const inputElements = form.querySelectorAll('.user-detail');
    for (var i = 0; i < inputElements.length; i++) {
        inputElements[i].disabled = !inputElements[i].disabled;
    }

    document.getElementById('editButton').style.display = "";
    document.getElementById('deleteButton').style.display = "none";
    document.getElementById('saveButton').style.display = "none";
    document.getElementById('cancelButton').style.display = "none";
}

function openDeleteModal(type, id) {
    if (!id || id === 0) {
        alert("❌ Invalid request. No ID found.");
        return;
    }

    let url = "";

    if (type === "account") {
        url = "./endpoint/delete.php?account=" + id;
        document.getElementById("deleteMessage").innerText =
            "Are you sure you want to delete this account?";
    } else if (type === "user") {
        url = "./endpoint/delete.php?user=" + id;
        document.getElementById("deleteMessage").innerText =
            "Are you sure you want to delete your user account?";
    }

    document.getElementById("confirmDeleteBtn").href = url;
    $('#deleteConfirmModal').modal('show');
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

<script>
function delete_account(accountId) {
    // Direct redirect for delete
    window.location.href = "./endpoint/delete-account.php?id=" + accountId;
}
</script>














