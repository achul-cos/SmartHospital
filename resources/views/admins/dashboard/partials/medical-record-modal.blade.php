<div id="medical-record-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl p-6">
        <div class="flex items-center justify-between">
            <h3 id="modal-title" class="text-lg font-semibold">Detail Rekam Medis</h3>
            <div>
                <button id="modal-print" class="px-3 py-1 bg-gray-200 rounded mr-2">Print</button>
                <button id="modal-close" class="px-3 py-1 bg-red-500 text-white rounded">Tutup</button>
            </div>
        </div>

        <div id="modal-body" class="mt-4 max-h-[60vh] overflow-auto">
            <!-- content filled by AJAX -->
            <div id="modal-content-loading" class="text-gray-500">Memuat...</div>
        </div>

        <div class="mt-4 flex justify-end space-x-2">
            <button id="modal-edit" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
            <button id="modal-delete" class="px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
        </div>
    </div>
</div>
