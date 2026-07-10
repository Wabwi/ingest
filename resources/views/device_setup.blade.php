@extends('layouts.bootstrap')

@section('styles')
<style>
    .code-container {
        background: rgba(255, 255, 255, 0.05);
        border: 2px dashed var(--primary-accent);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 2rem;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
    }
    
    .setup-code {
        font-size: 3rem;
        font-weight: 800;
        letter-spacing: 0.5rem;
        color: #fff;
        margin: 0;
        text-shadow: 0 0 15px rgba(99, 102, 241, 0.6);
        font-family: 'Courier New', Courier, monospace;
    }
    
    [data-bs-theme="light"] .setup-code {
        color: var(--primary-accent);
        text-shadow: none;
    }
    
    .instruction-step {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .step-number {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--primary-accent);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
        margin-top: 0.2rem;
    }
    
    .step-text {
        font-size: 1rem;
        color: var(--text-main);
    }
    
    .step-text strong {
        color: var(--primary-accent);
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-card p-4 p-md-5">
            <!-- Header Section -->
            <div class="text-center mb-4">
                <i class="bi bi-phone-vibrate-fill fs-1 text-indigo" style="color: var(--primary-accent); font-size: 3.5rem !important;"></i>
                <h2 class="fw-bold mt-3">Offline Mobile App Setup</h2>
                <p class="text-muted">Register your smartphone to log meals and bowel movements offline, then automatically sync them back to your account.</p>
            </div>

            <hr class="border-secondary opacity-25 my-4">

            <div class="row g-4 align-items-center">
                <!-- Left Column: Registration Code -->
                <div class="col-md-6 border-end border-secondary border-opacity-10 pe-md-4">
                    <h4 class="fw-semibold mb-3">Your Registration Code</h4>
                    
                    @if($user->device_setup_code && $user->device_setup_expires_at && \Carbon\Carbon::parse($user->device_setup_expires_at)->isFuture())
                        <div class="code-container">
                            <span class="setup-code">{{ $user->device_setup_code }}</span>
                            <div class="small text-muted mt-2">
                                <i class="bi bi-clock-history"></i> Expires in {{ \Carbon\Carbon::parse($user->device_setup_expires_at)->diffForHumans(null, true) }}
                            </div>
                        </div>
                        
                        <form action="{{ route('device-setup.generate') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100 border border-secondary border-opacity-50 nav-link-custom justify-content-center py-2 px-3">
                                <i class="bi bi-arrow-clockwise"></i> Generate New Code
                            </button>
                        </form>
                    @else
                        <div class="code-container bg-opacity-25 border-secondary text-center py-4">
                            <p class="text-muted mb-0">No active setup code.</p>
                        </div>
                        
                        <form action="{{ route('device-setup.generate') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary-custom w-100 py-3">
                                <i class="bi bi-plus-circle me-2"></i> Generate Setup Code
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Right Column: Instructions -->
                <div class="col-md-6 ps-md-4">
                    <h4 class="fw-semibold mb-3">Setup Instructions</h4>
                    
                    <div class="instruction-step">
                        <div class="step-number">1</div>
                        <div class="step-text">
                            Open the <strong>Ingest Mobile App</strong> on your smartphone.
                        </div>
                    </div>
                    
                    <div class="instruction-step">
                        <div class="step-number">2</div>
                        <div class="step-text">
                            Enter the 6-digit <strong>Registration Code</strong> displayed on the left.
                        </div>
                    </div>
                    
                    <div class="instruction-step">
                        <div class="step-number">3</div>
                        <div class="step-text">
                            Verify the Web Server URL is set to <code class="px-2 py-1 rounded bg-black bg-opacity-25 text-indigo" style="color: var(--primary-accent); font-size: 0.9rem;">https://ingest.wabwi.com</code>
                        </div>
                    </div>
                    
                    <div class="instruction-step">
                        <div class="step-number">4</div>
                        <div class="step-text">
                            Confirm connection. Your account will automatically download to the device, enabling secure email and password login even when <strong>completely offline</strong>!
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sync Info Section -->
            @if($user->api_token)
                <hr class="border-secondary opacity-25 my-4">
                <div class="d-flex align-items-center gap-3 p-3 rounded bg-black bg-opacity-25">
                    <i class="bi bi-shield-check text-success fs-3"></i>
                    <div>
                        <h6 class="fw-semibold mb-0 text-success">Device Successfully Linked</h6>
                        <p class="text-muted mb-0 small">This account has a secure API synchronization token initialized. Any offline changes logged on your mobile app will be backing up automatically.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
