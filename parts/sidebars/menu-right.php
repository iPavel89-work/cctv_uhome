<?php if (isset($_SESSION['auth_state']) || $_SESSION['auth_state'] === true): ?>
<button type="button" class="btn btn-icon btn-base" data-modal-btn="user_profile">
    <i class="bi bi-person"></i>
</button>
<?php endif; ?>

<button type="button" class="btn btn-icon btn-base" data-modal-btn="languages">
    <i class="bi bi-translate"></i>
</button>