@extends('layouts.admins_dashboard')

@section('title', 'Kelola Pasien - Smart Hospital')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-700">Kelola Pasien</h2>
        <div class="flex items-center space-x-2">
            <form method="GET" action="{{ route('admins.dashboard.pasien') }}" class="flex">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, nomor kartu..." class="border rounded-l px-3 py-2" />
                <button type="submit" class="bg-blue-600 text-white px-3 rounded-r">Cari</button>
            </form>
            <button id="open-add-patient" class="bg-green-600 text-white px-4 py-2 rounded">Tambah Pasien</button>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Pasien</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Kelola data pasien di rumah sakit.</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Kartu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($patients as $patient)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $patient->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $patient->card_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $patient->phone_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $patient->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admins.dashboard.pasien.show', $patient->id) }}" class="text-blue-600 hover:underline">Detail</a>

                                    <form method="POST" action="{{ route('admins.dashboard.pasien.destroy', $patient->id) }}" onsubmit="return confirm('Hapus pasien ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada pasien.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Patient Modal -->
<div id="add-patient-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-medium">Tambah Pasien Baru</h3>
        </div>
        <form method="POST" action="{{ route('admins.dashboard.pasien.store') }}" class="px-6 py-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" required class="mt-1 block w-full border rounded px-2 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. Kartu (opsional)</label>
                    <input type="text" name="card_number" class="mt-1 block w-full border rounded px-2 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="gender" required class="mt-1 block w-full border rounded px-2 py-2">
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="birth_date" required class="mt-1 block w-full border rounded px-2 py-2" />
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="address" required class="mt-1 block w-full border rounded px-2 py-2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="text" name="phone_number" required class="mt-1 block w-full border rounded px-2 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" required class="mt-1 block w-full border rounded px-2 py-2" />
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                    <textarea name="notes" class="mt-1 block w-full border rounded px-2 py-2"></textarea>
                </div>
            </div>

            <div class="mt-4 flex justify-end space-x-2 border-t pt-4">
                <button type="button" id="close-add-patient" class="px-4 py-2 rounded border">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('open-add-patient').addEventListener('click', function() {
        document.getElementById('add-patient-modal').classList.remove('hidden');
    });
    document.getElementById('close-add-patient').addEventListener('click', function() {
        document.getElementById('add-patient-modal').classList.add('hidden');
    });
</script>
@endpush

@endsection
