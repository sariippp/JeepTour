<!-- Jeep Modal -->
<div class="modal fade" id="jeepModal" tabindex="-1" aria-labelledby="jeepModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-blue-50">
                <h5 class="modal-title text-lg font-semibold text-gray-900" id="jeepModalTitle">Tambah Jeep</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form id="jeepForm" class="space-y-6">
                    <input type="hidden" id="jeepId" name="jeepId">
                    
                    <!-- Owner Selection -->
                    <div class="space-y-2">
                        <label for="jeepOwnerId" class="block text-sm font-semibold text-gray-700">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Pemilik Jeep
                            </span>
                        </label>
                        <select id="jeepOwnerId" name="jeepOwnerId" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                            <option value="">Pilih Pemilik</option>
                            @foreach($ownerData as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500">Pilih pemilik yang akan mengelola jeep ini</p>
                    </div>

                    <!-- Number Plate -->
                    <div class="space-y-2">
                        <label for="numberPlate" class="block text-sm font-semibold text-gray-700">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a4 4 0 01-4-4V7a4 4 0 014-4z" />
                                </svg>
                                Plat Nomor
                            </span>
                        </label>
                        <input type="text" 
                               id="numberPlate" 
                               name="numberPlate" 
                               required 
                               placeholder="Contoh: B 1234 ABC" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base uppercase"
                               style="text-transform: uppercase;">
                        <p class="text-sm text-gray-500">Masukkan plat nomor kendaraan (akan otomatis kapital)</p>
                    </div>

                    <!-- Total Passenger -->
                    <div class="space-y-2">
                        <label for="totalPassenger" class="block text-sm font-semibold text-gray-700">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Kapasitas Penumpang
                            </span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="totalPassenger" 
                                   name="totalPassenger" 
                                   required 
                                   min="1" 
                                   max="20"
                                   placeholder="6" 
                                   class="w-full px-4 py-3 pr-16 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base">
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-base">orang</span>
                        </div>
                        <p class="text-sm text-gray-500">Jumlah maksimal penumpang yang dapat diangkut (tidak termasuk supir)</p>
                    </div>

                    <!-- Info Card -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm">
                                <p class="font-medium text-blue-800 mb-1">Informasi Penting:</p>
                                <ul class="text-blue-700 space-y-1">
                                    <li>• Kapasitas mencakup supir dan penumpang</li>
                                    <li>• Pastikan sesuai dengan kapasitas fisik kendaraan</li>
                                    <li>• Data ini akan digunakan untuk perhitungan tarif</li>
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
                            onclick="saveJeep()" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Jeep
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>