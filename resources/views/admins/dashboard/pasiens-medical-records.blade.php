@extends('layouts.admins_dashboard')

@section('title', 'Rekam Medis Pasien - Smart Hospital')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-700">Semua Rekam Medis</h2>
        <form method="GET" action="{{ route('admins.dashboard.pasien.medical-records') }}" class="flex">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari pasien atau nomor kartu..." class="border rounded-l px-3 py-2" />
            <button type="submit" class="bg-blue-600 text-white px-3 rounded-r">Cari</button>
        </form>
    </div>

    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Rekam Medis (Terbaru)</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ringkasan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($records as $record)
                        <tr>
                            <td class="px-6 py-4">{{ $record->record_date?->format('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $record->patient?->name }}<br><span class="text-xs text-gray-500">{{ $record->patient?->card_number }}</span></td>
                            <td class="px-6 py-4">{{ $record->doctor?->name ?? 'Admin' }}</td>
                            <td class="px-6 py-4">{{ Str::limit($record->chief_complaint, 80) }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admins.dashboard.pasien.show', $record->patient->id) }}" class="text-blue-600 hover:underline">Lihat Pasien</a>
                                <a href="{{ route('admins.dashboard.pasien.medical-record.show', [$record->patient->id, $record->id]) }}" class="ml-2 text-indigo-600 hover:underline">Lihat Rekam</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada rekam medis.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $records->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Optional: open a modal if record_id query param present
    (function(){
        const params = new URLSearchParams(window.location.search);
        const recordId = params.get('record_id');
        if (!recordId) return;
        // Ideally we would fetch and show in modal — for now navigate to patient detail with fragment
        // This is a placeholder behavior.
    })();
</script>
@endpush

@endsection
