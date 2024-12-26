<!-- resources/views/admin/partials/owner-modal.blade.php -->
<div class="modal fade" id="ownerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ownerModalTitle">Add/Edit Owner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="ownerForm">
                    <input type="hidden" id="ownerId">
                    <div class="mb-3">
                        <label for="ownerName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="ownerName" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveOwner()">Save</button>
            </div>
        </div>
    </div>
</div>