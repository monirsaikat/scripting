<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="confirmModalLabel">Confirm?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" <?= up_form_attrs() ?>>
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>" />
                <div class="modal-body">
                    <p>Are you sure to continue?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button button--secondary" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="button button--primary">Sure</button>
                </div>
            </form>
        </div>
    </div>
</div>
