<!-- resources/views/admin/partials/jeep-modal.blade.php -->
<div class="modal fade" id="jeepModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jeepModalTitle">Tambah/Edit Jeep</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="jeepForm">
                    <input type="hidden" id="jeepId">
                    <div class="mb-3">
                        <label for="numberPlate" class="form-label">Plat Nomor</label>
                        <input type="text" class="form-control" id="numberPlate" required>
                    </div>
                    <div class="mb-3">
                        <label for="jeepOwnerId" class="form-label">Pemilik</label>
                        <select class="form-control" id="jeepOwnerId" required>
                            @foreach($ownerData as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="total_passanger" value="4">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="saveJeep()">Simpan</button>
            </div>
        </div>
    </div>
</div>