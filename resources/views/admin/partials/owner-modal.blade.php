<!-- Enhanced Owner Modal -->
<div class="modal fade" id="ownerModal" tabindex="-1" aria-labelledby="ownerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-blue-50">
                <h5 class="modal-title text-lg font-semibold text-gray-900" id="ownerModalTitle">Tambah Pemilik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form id="ownerForm" class="space-y-6">
                    <input type="hidden" id="ownerId" name="ownerId">
                    
                    <!-- Owner Name -->
                    <div class="space-y-2">
                        <label for="ownerName" class="block text-sm font-semibold text-gray-700">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Nama Pemilik
                            </span>
                        </label>
                        <input type="text" 
                               id="ownerName" 
                               name="ownerName" 
                               required 
                               placeholder="Masukkan nama lengkap pemilik" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                        <p class="text-sm text-gray-500">Nama ini akan digunakan untuk identifikasi pemilik jeep</p>
                    </div>

                    <!-- Info Card -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm">
                                <p class="font-medium text-blue-800 mb-1">Setelah menambah pemilik:</p>
                                <ul class="text-blue-700 space-y-1">
                                    <li>• Anda dapat menambahkan jeep untuk pemilik ini</li>
                                    <li>• Data pemilik akan muncul di dashboard</li>
                                    <li>• Sistem akan menghitung gaji otomatis</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-gray-50 px-6 py-4">
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium" 
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" 
                            onclick="saveOwner()" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Pemilik
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>