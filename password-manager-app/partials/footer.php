<!-- Your custom CSS -->
<link rel="stylesheet" href="http://localhost/PM/password-manager-app/assets/style.css">

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome (for icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- DataTables CSS with Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- jQuery (needed for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap 5 Bundle JS (includes Popper.js automatically) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS with Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- Your custom script -->
<script src="http://localhost/PM/password-manager-app/assets/script.js"></script>

<script>
$(document).ready(function () {
    // ✅ Initialize DataTable only once
    if (!$.fn.DataTable.isDataTable('#accountsTable')) {
        $('#accountsTable').DataTable({
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50],
            "ordering": true,
            "searching": true
        });
    }
}); // ✅ missing bracket fixed here
</script>
<script>
document.addEventListener("click", function (e) {
  const toggle = e.target.closest(".toggle-password");
  if (!toggle) return;

  const wrapper = toggle.closest(".position-relative, .input-group");
  const input = wrapper ? wrapper.querySelector("input") : null;
  if (!input) return;

  if (input.type === "password") {
    input.type = "text";
    toggle.classList.replace("fa-eye-slash", "fa-eye");
  } else {
    input.type = "password";
    toggle.classList.replace("fa-eye", "fa-eye-slash");
  }
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".toggle-password").forEach(icon => {
        icon.addEventListener("click", function () {
            const input = this.previousElementSibling; // the <input type="password">
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

<script>
// Password show/hide toggle (table + modal)
document.addEventListener("click", function (e) {
  if (e.target.closest(".toggle-password")) {
    const button = e.target.closest(".toggle-password");
    const input = button.parentElement.querySelector("input");
    const icon = button.querySelector("i");

    if (input.type === "password") {
      input.type = "text";
      icon.classList.replace("fa-eye-slash", "fa-eye");
    } else {
      input.type = "password";
      icon.classList.replace("fa-eye", "fa-eye-slash");
    }
  }
});
</script>
<script>
document.querySelectorAll('.toggle-password').forEach(icon => {
  icon.addEventListener('click', function () {
    const input = this.previousElementSibling; // the input just before the icon
    if (input.type === "password") {
      input.type = "text";
      this.classList.replace("fa-eye-slash", "fa-eye");
    } else {
      input.type = "password";
      this.classList.replace("fa-eye", "fa-eye-slash");
    }
  });
});

</script>