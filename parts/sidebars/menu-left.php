
<?php if (isset($_SESSION['auth_state']) || $_SESSION['auth_state'] === true): ?>
    <a href="<?= $document_root; ?>/dashboard/index.php" class="btn btn-icon btn-base">
        <i class="bi bi-house"></i>
    </a>

    <button type="button" data-modal-btn="addresses" class="btn btn-icon btn-base">
        <i class="bi bi-geo"></i>
    </button>
<?php endif; ?>