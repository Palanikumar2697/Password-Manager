<?php if (!empty($modal)): ?>
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-<?= htmlspecialchars($modal['type']) ?>">
      <div class="modal-header bg-<?= htmlspecialchars($modal['type']) ?> text-white">
        <h5 class="modal-title"><?= htmlspecialchars($modal['title']) ?></h5>
      </div>
      <div class="modal-body">
        <?= $modal['message'] ?>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const el = document.getElementById('statusModal');
    const modal = new bootstrap.Modal(el, {
        backdrop: 'static',
        keyboard: false
    });
    modal.show();
});
</script>

<?php unset($_SESSION['modal']); ?>
<?php endif; ?>
