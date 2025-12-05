<?php if (!empty($modal)): ?>
<div class="modal fade" id="statusModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content border-<?php echo $modal['type']; ?>">
      <div class="modal-header bg-<?php echo $modal['type']; ?> text-white">
        <h5 class="modal-title"><?php echo $modal['title']; ?></h5>
      </div>
      <div class="modal-body">
        <?php echo $modal['message']; ?>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
});
</script>
<?php endif; ?>
