<div 
    x-data="{ open: false, message: '', type: 'success' }"
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-75"
    x-transition:enter-end="opacity-100 scale-105"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-75"
    x-cloak
    id="flashModal"
    class="fixed inset-0 flex items-center justify-center z-50 shadow-4xl"
    @flash.window="
        message = $event.detail.message;
        type = $event.detail.type;
        open = true;
        setTimeout(() => open = false, 1500);
    "
>
    <div 
        class="relative bg-white rounded-xl shadow-2xl p-6 w-80 text-center border-t-4 transform transition-transform duration-300"
        :class="{
            'border-green-500': type === 'success',
            'border-red-500': type === 'error',
            'border-blue-500': type === 'info'
        }"
    >
        <!-- Tombol close -->
        <button @click="open = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 8.586l4.95-4.95a1 1 0 111.414 1.414L11.414 10l4.95 4.95a1 1 0 11-1.414 1.414L10 11.414l-4.95 4.95a1 1 0 11-1.414-1.414L8.586 10l-4.95-4.95a1 1 0 111.414-1.414L10 8.586z" clip-rule="evenodd" />
            </svg>
        </button>

        <!-- Ikon animasi -->
        <div class="mb-4">
            <template x-if="type === 'success'">
                <svg class="w-16 h-16 mx-auto text-green-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </template>
            <template x-if="type === 'error'">
                <svg class="w-16 h-16 mx-auto text-red-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </template>
            <template x-if="type === 'info'">
                <svg class="w-16 h-16 mx-auto text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                </svg>
            </template>
        </div>

        <!-- Pesan -->
        <p class="text-lg font-semibold" x-text="message"></p>
    </div>
</div>

<script>
window.showFlashModal = function(message, type = 'success') {
    window.dispatchEvent(new CustomEvent('flash', { detail: { message, type } }));
};
</script>