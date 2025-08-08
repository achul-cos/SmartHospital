@extends('layouts.app')

@section('title', 'Smart Hospital - Healthcare Excellence')
@section('description', 'Smart Hospital provides exceptional healthcare services with cutting-edge technology and compassionate care. Book appointments, access medical records, and experience world-class healthcare.')
@section('keywords', 'hospital, healthcare, medical services, appointments, emergency care, surgery, diagnostics')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-blue-600 to-blue-800 text-white overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl lg:text-6xl font-bold leading-tight mb-6">
                    World-Class Healthcare
                    <span class="text-blue-200">at Your Fingertips</span>
                </h1>
                <p class="text-xl lg:text-2xl text-blue-100 mb-8 leading-relaxed">
                    Experience exceptional medical care with cutting-edge technology and compassionate healthcare professionals. Your health is our priority.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-blue-50 transition duration-300 text-center">
                        Book Appointment
                    </a>
                    <a href="#services" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-blue-600 transition duration-300 text-center">
                        Our Services
                    </a>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="relative">
                    <div class="absolute inset-0 bg-blue-400 rounded-2xl transform rotate-6"></div>
                    <div class="relative bg-white rounded-2xl p-8 shadow-2xl">
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Quick Appointment</h3>
                                <p class="text-sm text-gray-600">Book in under 2 minutes</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">24/7 Emergency Care</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Expert Medical Team</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">Advanced Technology</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">50+</div>
                <div class="text-gray-600">Expert Doctors</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">10K+</div>
                <div class="text-gray-600">Happy Patients</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">24/7</div>
                <div class="text-gray-600">Emergency Care</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-blue-600 mb-2">15+</div>
                <div class="text-gray-600">Years Experience</div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Medical Services</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                We provide comprehensive healthcare services with state-of-the-art technology and experienced medical professionals.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Emergency Care -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Emergency Care</h3>
                <p class="text-gray-600 mb-6">24/7 emergency medical services with rapid response times and expert trauma care.</p>
                <a href="#" class="text-blue-600 font-semibold hover:text-blue-700 transition duration-300">Learn More →</a>
            </div>

            <!-- Surgery -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Advanced Surgery</h3>
                <p class="text-gray-600 mb-6">State-of-the-art surgical procedures with experienced surgeons and modern facilities.</p>
                <a href="#" class="text-blue-600 font-semibold hover:text-blue-700 transition duration-300">Learn More →</a>
            </div>

            <!-- Diagnostics -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Diagnostics</h3>
                <p class="text-gray-600 mb-6">Comprehensive diagnostic services including MRI, CT scans, and laboratory testing.</p>
                <a href="#" class="text-blue-600 font-semibold hover:text-blue-700 transition duration-300">Learn More →</a>
            </div>

            <!-- Cardiology -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Cardiology</h3>
                <p class="text-gray-600 mb-6">Specialized cardiac care with advanced heart monitoring and treatment options.</p>
                <a href="#" class="text-blue-600 font-semibold hover:text-blue-700 transition duration-300">Learn More →</a>
            </div>

            <!-- Pediatrics -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Pediatrics</h3>
                <p class="text-gray-600 mb-6">Compassionate care for children with specialized pediatric medical services.</p>
                <a href="#" class="text-blue-600 font-semibold hover:text-blue-700 transition duration-300">Learn More →</a>
            </div>

            <!-- Rehabilitation -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Rehabilitation</h3>
                <p class="text-gray-600 mb-6">Comprehensive rehabilitation programs for recovery and physical therapy.</p>
                <a href="#" class="text-blue-600 font-semibold hover:text-blue-700 transition duration-300">Learn More →</a>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Why Choose Smart Hospital?</h2>
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Expert Medical Team</h3>
                            <p class="text-gray-600">Our team consists of highly qualified and experienced healthcare professionals dedicated to providing the best care.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Advanced Technology</h3>
                            <p class="text-gray-600">State-of-the-art medical equipment and facilities to ensure accurate diagnosis and effective treatment.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Patient-Centered Care</h3>
                            <p class="text-gray-600">We prioritize patient comfort and satisfaction with personalized care plans and compassionate service.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Medical Team" class="rounded-2xl shadow-2xl">
                <div class="absolute -bottom-6 -left-6 bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">24/7</div>
                            <div class="text-sm text-gray-600">Emergency Care</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-blue-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Experience Better Healthcare?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
            Join thousands of satisfied patients who trust Smart Hospital for their healthcare needs. Book your appointment today.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-blue-50 transition duration-300">
                Book Appointment Now
            </a>
            <a href="" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-blue-600 transition duration-300">
                Contact Us
            </a>
        </div>
    </div>
</section>

@endsection
