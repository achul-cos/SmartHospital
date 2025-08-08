@extends('layouts.doctors_dashboard')

@section('title', 'Hubungi Admin')

@section('header', 'Hubungi Admin')
@section('subheader', 'Hubungi Admin jika ada suatu hal yang mendesak dan diperlukan.')

@section('content')
<div class="space-y-6">
    <!-- Emergency Contact Banner -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Keadaan Darurat</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>Untuk keadaan darurat, hubungi langsung: <strong>+62 21 9999 9999</strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Phone -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-gray-900">Telepon</h4>
                    <p class="text-sm text-gray-500">+62 21 1234 5678</p>
                </div>
            </div>
        </div>

        <!-- Email -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-gray-900">Email</h4>
                    <p class="text-sm text-gray-500">admin@smarthospital.com</p>
                </div>
            </div>
        </div>

        <!-- WhatsApp -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-gray-900">WhatsApp</h4>
                    <p class="text-sm text-gray-500">+62 812 3456 7890</p>
                </div>
            </div>
        </div>

        <!-- Office Hours -->
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-gray-900">Jam Kerja</h4>
                    <p class="text-sm text-gray-500">Senin - Jumat: 08:00 - 17:00</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Statistics -->
    @if(isset($chatStats))
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Statistik Chat</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $chatStats['total_chats'] }}</div>
                    <div class="text-sm text-gray-500">Total Chat</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $chatStats['active_chats'] }}</div>
                    <div class="text-sm text-gray-500">Chat Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-600">{{ $chatStats['closed_chats'] }}</div>
                    <div class="text-sm text-gray-500">Chat Selesai</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $chatStats['urgent_chats'] }}</div>
                    <div class="text-sm text-gray-500">Chat Urgent</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $chatStats['average_response_time'] }}m</div>
                    <div class="text-sm text-gray-500">Rata-rata Respon</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Chat Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Chat Langsung dengan Admin</h3>
                    <p class="mt-1 text-sm text-gray-500">Kelola percakapan dengan admin yang tersedia</p>
                </div>
                <button onclick="openCreateChatModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Mulai Chat Baru
                </button>
            </div>
            
            <!-- Chat Rooms List -->
            @if(isset($chatRooms) && $chatRooms->count() > 0)
                <div class="space-y-4">
                    @foreach($chatRooms as $chatRoom)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $chatRoom->subject }}</h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $chatRoom->statusColor }}">
                                            {{ $chatRoom->statusText }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $chatRoom->priorityColor }}">
                                            {{ $chatRoom->priorityText }}
                                        </span>
                                    </div>
                                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                        @if($chatRoom->admin)
                                            <span>Admin: {{ $chatRoom->admin->name }}</span>
                                        @else
                                            <span>Menunggu admin...</span>
                                        @endif
                                        <span>{{ $chatRoom->lastMessage }}</span>
                                        <span>{{ $chatRoom->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('doctors.dashboard.chat.room', $chatRoom->room_id) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Buka Chat
                                    </a>
                                    @if($chatRoom->status !== 'closed')
                                        <form action="{{ route('doctors.dashboard.chat.close', $chatRoom->room_id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Tutup
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada chat</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai chat baru untuk berbicara dengan admin.</p>
                    <div class="mt-4">
                        <button onclick="openCreateChatModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Mulai Chat Baru
                        </button>
                    </div>
                </div>
            @endif

            <!-- Available Admins -->
            @if(isset($availableAdmins) && $availableAdmins->count() > 0)
            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Admin yang Tersedia</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($availableAdmins as $adminAssignment)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-green-600">{{ $adminAssignment->admin->name[0] ?? 'A' }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $adminAssignment->admin->name }}</h4>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $adminAssignment->statusColor }}">
                                            {{ $adminAssignment->statusText }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $adminAssignment->current_chats }}/{{ $adminAssignment->max_concurrent_chats }} chat
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Topics -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Topik Cepat untuk Chat</h3>
            <p class="text-sm text-gray-500 mb-4">Pilih topik untuk memulai chat baru dengan admin</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <button onclick="fillQuickChatTopic('schedule_conflict')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Konflik Jadwal
                </button>
                <button onclick="fillQuickChatTopic('patient_emergency')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    Keadaan Darurat
                </button>
                <button onclick="fillQuickChatTopic('system_issue')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Masalah Sistem
                </button>
                <button onclick="fillQuickChatTopic('equipment_request')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    Permintaan Alat
                </button>
                <button onclick="fillQuickChatTopic('policy_question')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pertanyaan Kebijakan
                </button>
                <button onclick="fillQuickChatTopic('technical_support')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
                    </svg>
                    Dukungan Teknis
                </button>
                <button onclick="fillQuickChatTopic('patient_referral')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Rujukan Pasien
                </button>
                <button onclick="fillQuickChatTopic('other')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Lainnya
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Contact History -->
    @if(isset($recentContacts) && $recentContacts->count() > 0)
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Riwayat Kontak Terbaru</h3>
            <div class="space-y-4">
                @foreach($recentContacts as $contact)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">{{ $contact->description }}</h4>
                            <p class="text-sm text-gray-500 mt-1">
                                @if(isset($contact->metadata['priority']))
                                    Prioritas: 
                                    @switch($contact->metadata['priority'])
                                        @case('low')
                                            <span class="text-green-600">Rendah</span>
                                            @break
                                        @case('medium')
                                            <span class="text-yellow-600">Sedang</span>
                                            @break
                                        @case('high')
                                            <span class="text-orange-600">Tinggi</span>
                                            @break
                                        @case('urgent')
                                            <span class="text-red-600">Mendesak</span>
                                            @break
                                    @endswitch
                                @endif
                                @if(isset($contact->metadata['contact_method']))
                                    • Metode: 
                                    @switch($contact->metadata['contact_method'])
                                        @case('email')
                                            <span class="text-blue-600">Email</span>
                                            @break
                                        @case('phone')
                                            <span class="text-green-600">Telepon</span>
                                            @break
                                        @case('whatsapp')
                                            <span class="text-green-600">WhatsApp</span>
                                            @break
                                        @case('internal_message')
                                            <span class="text-purple-600">Pesan Internal</span>
                                            @break
                                    @endswitch
                                @endif
                            </p>
                            @if(isset($contact->metadata['message']))
                                <p class="text-sm text-gray-600 mt-2">{{ Str::limit($contact->metadata['message'], 150) }}</p>
                            @endif
                        </div>
                        <div class="ml-4 text-right">
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($contact->created_at)->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Create Chat Modal -->
<div id="createChatModal" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none; background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('doctors.dashboard.chat.create') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Mulai Chat Baru
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="chat_subject" class="block text-sm font-medium text-gray-700">Subjek</label>
                                    <input type="text" name="subject" id="chat_subject" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="chat_priority" class="block text-sm font-medium text-gray-700">Prioritas</label>
                                    <select name="priority" id="chat_priority" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="low">Rendah</option>
                                        <option value="medium" selected>Sedang</option>
                                        <option value="high">Tinggi</option>
                                        <option value="urgent">Mendesak</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="chat_initial_message" class="block text-sm font-medium text-gray-700">Pesan Awal</label>
                                    <textarea name="initial_message" id="chat_initial_message" rows="4" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis pesan awal Anda..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Mulai Chat
                    </button>
                    <button type="button" onclick="closeCreateChatModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Load chat rooms and admins on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOMContentLoaded triggered');
        console.log('DOMContentLoaded completed');
        
        // Auto-refresh chat list every 30 seconds
        setInterval(refreshChatList, 30000);
        
        // Check for new messages every 10 seconds
        setInterval(checkNewMessages, 10000);
    });

    function refreshChatList() {
        console.log('Auto-refreshing chat list...');
        location.reload();
    }

    function checkNewMessages() {
        // This would be implemented with WebSocket or Server-Sent Events
        // For now, we'll just log it
        console.log('Checking for new messages...');
    }

    function openCreateChatModal() {
        console.log('Opening create chat modal...');
        const modal = document.getElementById('createChatModal');
        if (modal) {
            // Remove hidden class and set display to block
            modal.classList.remove('hidden');
            modal.style.display = 'block';
            modal.style.zIndex = '9999';
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
            console.log('Modal should be visible now');
            console.log('Modal display:', modal.style.display);
            console.log('Modal z-index:', modal.style.zIndex);
            
            // Focus on first input
            const firstInput = modal.querySelector('input');
            if (firstInput) {
                firstInput.focus();
            }
        } else {
            console.error('Modal element not found');
        }
    }
    
    function closeCreateChatModal() {
        console.log('Closing create chat modal...');
        const modal = document.getElementById('createChatModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            
            // Reset form
            const form = modal.querySelector('form');
            if (form) {
                form.reset();
            }
        } else {
            console.error('Modal element not found');
        }
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('createChatModal');
        if (modal && e.target === modal) {
            closeCreateChatModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateChatModal();
        }
    });

    function fillQuickChatTopic(topic) {
        const subjectMap = {
            'schedule_conflict': 'Konflik Jadwal Praktik',
            'patient_emergency': 'Keadaan Darurat Pasien',
            'system_issue': 'Masalah Sistem/Aplikasi',
            'equipment_request': 'Permintaan Peralatan',
            'policy_question': 'Pertanyaan Kebijakan',
            'technical_support': 'Dukungan Teknis',
            'patient_referral': 'Rujukan Pasien',
            'other': 'Pertanyaan Lainnya'
        };
        
        const messageMap = {
            'schedule_conflict': 'Saya mengalami konflik jadwal praktik dan membutuhkan bantuan untuk mengatur ulang jadwal.',
            'patient_emergency': 'Ada keadaan darurat dengan pasien yang membutuhkan perhatian segera.',
            'system_issue': 'Saya mengalami masalah dengan sistem/aplikasi yang mengganggu praktik.',
            'equipment_request': 'Saya membutuhkan peralatan tambahan untuk praktik.',
            'policy_question': 'Saya memiliki pertanyaan tentang kebijakan rumah sakit.',
            'technical_support': 'Saya membutuhkan dukungan teknis untuk sistem atau peralatan.',
            'patient_referral': 'Saya membutuhkan bantuan untuk melakukan rujukan pasien.',
            'other': 'Saya memiliki pertanyaan atau masalah lainnya yang membutuhkan bantuan admin.'
        };
        
        // Open modal first
        openCreateChatModal();
        
        // Wait a bit for modal to open, then fill the form
        setTimeout(() => {
            const subjectInput = document.getElementById('chat_subject');
            const messageInput = document.getElementById('chat_initial_message');
            const prioritySelect = document.getElementById('chat_priority');
            
            if (subjectInput) subjectInput.value = subjectMap[topic] || '';
            if (messageInput) messageInput.value = messageMap[topic] || '';
            if (prioritySelect) prioritySelect.value = topic === 'patient_emergency' ? 'urgent' : 'medium';
            
            // Focus on message input
            if (messageInput) {
                messageInput.focus();
                messageInput.setSelectionRange(messageInput.value.length, messageInput.value.length);
            }
        }, 100);
    }

    // Show notification function
    function showNotification(title, message, type = 'info') {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(title, {
                body: message,
                icon: '/favicon.ico'
            });
        }
        
        // Also show in-app notification
        showInAppNotification(title, message, type);
    }

    function showInAppNotification(title, message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
        
        const bgColor = type === 'success' ? 'bg-green-500' : 
                       type === 'error' ? 'bg-red-500' : 
                       type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
        
        notification.className += ` ${bgColor} text-white`;
        
        notification.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${title}</p>
                    <p class="text-sm opacity-90">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }

    // Request notification permission
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
</script>
@endpush
