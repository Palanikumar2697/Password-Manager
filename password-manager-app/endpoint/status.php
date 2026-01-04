<?php if (!empty($modal)): ?>
<?php
$type    = $modal['type']    ?? 'info';
$title   = $modal['title']   ?? 'Message';
$message = $modal['message'] ?? '';
?>
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-<?= htmlspecialchars($type) ?>">
      <div class="modal-header bg-<?= htmlspecialchars($type) ?> text-white">
        <h5 class="modal-title"><?= htmlspecialchars($title) ?></h5>
      </div>
      <div class="modal-body">
        <?= htmlspecialchars($message) ?>
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
    if (el && typeof bootstrap !== "undefined") {
        new bootstrap.Modal(el, {
            backdrop: 'static',
            keyboard: false
        }).show();
    }
});
</script>
<?php endif; ?>
