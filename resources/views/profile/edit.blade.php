@extends('layouts.app')

@section('content')

@php
    // Keep the Security tab open across redirects whenever there's
    // OTP activity to show — a code was just generated, a verify
    // attempt failed/succeeded, or the phone number just changed.
    $activeTab = (
        session('otp')
        || session('error')
        || session('success')
        || session('status') === 'phone-changed'
    ) ? 'security' : 'personal';
@endphp

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

<style>
    /* Add this block at the very top of your stylesheet */
html, body {
    margin: 0;
    padding: 0;
    background-color: #0A0E1F; /* Matches your --bg-deep color */
    min-height: 100vh;
    width: 100%;
}

    .profile-page, .profile-page * { box-sizing: border-box; }

    .profile-page {
        --bg-deep:     #0A0E1F;
        --bg-card:     #12162C;
        --bg-field:    #0D1126;
        --border:      rgba(255,255,255,0.08);
        --border-glow: rgba(108,99,255,0.35);
        --indigo:      #6C63FF;
        --indigo-dim:  rgba(108,99,255,0.14);
        --indigo-soft: #8B85FF;
        --green:       #34D399;
        --green-dim:   rgba(52,211,153,0.14);
        --amber:       #FBBF24;
        --amber-dim:   rgba(251,191,36,0.14);
        --rose:        #F87171;
        --rose-dim:    rgba(248,113,113,0.14);
        --text-1:      #EDEFF7;
        --text-2:      #9CA3C4;
        --text-3:      #5E6488;
        --font-display:'Space Grotesk', sans-serif;
        --font-body:   'Inter', sans-serif;
        --font-mono:   'JetBrains Mono', monospace;

        background: var(--bg-deep);
        color: var(--text-1);
        font-family: var(--font-body);
        min-height: 100vh;
        padding: 40px 40px 64px;
    }

    /* ── Header ── */
    .profile-header { margin-bottom: 28px; }
    .profile-title {
        font-family: var(--font-display);
        font-size: 26px;
        font-weight: 700;
        letter-spacing: -0.01em;
        color: var(--text-1);
    }
    .profile-subtitle {
        font-size: 13px;
        color: var(--text-2);
        margin-top: 4px;
    }

    .success-msg, .warning-msg {
        font-size: 13px;
        font-weight: 500;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .success-msg { background: var(--green-dim); color: var(--green); border: 1px solid rgba(52,211,153,0.3); }
    .warning-msg { background: var(--amber-dim); color: var(--amber); border: 1px solid rgba(251,191,36,0.3); }

    /* ── Layout ── */
    .profile-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 24px;
        align-items: start;
    }

    /* ── Identity card ── */
    .identity-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 28px 24px;
        text-align: center;
        position: sticky;
        top: 24px;
    }

    .avatar-wrap { position: relative; width: 96px; height: 96px; margin: 0 auto 18px; }
    .avatar-ring-svg { position: absolute; inset: 0; width: 96px; height: 96px; transform: rotate(-90deg); }
    .avatar-circle {
        position: absolute;
        top: 8px; left: 8px;
        width: 80px; height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--indigo), var(--indigo-soft));
        display: flex; align-items: center; justify-content: center;
        font-family: var(--font-display);
        font-size: 28px;
        font-weight: 700;
        color: #fff;
        overflow: hidden;
    }
    .avatar-remove {
    width: 24px;
    height: 24px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    background: #F87171;
    color: white;
    font-size: 12px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-remove:hover {
    opacity: 0.9;
}
    .completeness-badge {
        position: absolute;
        bottom: -2px; right: -4px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--indigo-soft);
        font-family: var(--font-mono);
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 20px;
        line-height: 1.4;
    }
    .avatar-edit {
        position: absolute;
        bottom: -2px; left: -4px;
        width: 26px; height: 26px;
        background: var(--bg-deep);
        border: 1px solid var(--border);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: var(--text-2);
        transition: color 0.15s, border-color 0.15s;
    }
    .avatar-edit:hover { color: var(--indigo-soft); border-color: var(--border-glow); }
    .avatar-edit svg { width: 13px; height: 13px; }

    .identity-name { font-family: var(--font-display); font-size: 17px; font-weight: 700; margin-bottom: 2px; }
    .identity-email { font-size: 12.5px; color: var(--text-2); margin-bottom: 16px; word-break: break-all; }

    .verify-pill {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 11.5px; font-weight: 600;
        padding: 6px 13px; border-radius: 20px;
        margin-bottom: 22px;
    }
    .verify-pill svg { width: 14px; height: 14px; flex-shrink: 0; }
    .verify-pill.is-verified { background: var(--green-dim); color: var(--green); border: 1px solid rgba(52,211,153,0.3); }
    .verify-pill.is-unverified { background: var(--amber-dim); color: var(--amber); border: 1px solid rgba(251,191,36,0.3); }

    .identity-stats {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 12px; margin-bottom: 22px;
        padding-bottom: 22px;
        border-bottom: 1px solid var(--border);
    }
    .stat-block { display: flex; flex-direction: column; }
    .stat-number { font-family: var(--font-mono); font-size: 22px; font-weight: 700; color: var(--indigo-soft); }
    .stat-label { font-size: 10.5px; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.07em; margin-top: 3px; }

    .identity-nav { display: flex; flex-direction: column; gap: 6px; }
    .identity-nav-link {
        display: flex; align-items: center; gap: 10px;
        background: none; border: 1px solid transparent;
        color: var(--text-2);
        font-family: var(--font-body);
        font-size: 13px; font-weight: 500;
        padding: 10px 14px; border-radius: 10px;
        cursor: pointer; text-align: left;
        transition: all 0.15s;
        width: 100%;
    }
    .identity-nav-link svg { width: 16px; height: 16px; flex-shrink: 0; }
    .identity-nav-link:hover { color: var(--text-1); background: rgba(255,255,255,0.04); }
    .identity-nav-link.is-active {
        color: var(--indigo-soft);
        background: var(--indigo-dim);
        border-color: var(--border-glow);
    }

    /* ── Main column ── */
    .profile-main { display: flex; flex-direction: column; gap: 20px; min-width: 0; }

    .panel-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 26px 28px;
    }
    .panel-card-head {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 20px;
    }
    .panel-card-head h2 {
        font-family: var(--font-display);
        font-size: 15px; font-weight: 600;
        color: var(--text-1);
    }
    .head-icon { width: 18px; height: 18px; color: var(--text-3); }

    .tab-panel { display: none; }
    .tab-panel.is-active { display: block; }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px 20px;
        margin-bottom: 24px;
    }
    .field label {
        display: block;
        font-size: 10.5px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.07em;
        color: var(--text-3); margin-bottom: 7px;
    }
    .field input {
        width: 100%;
        background: var(--bg-field);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 11px 14px;
        font-family: var(--font-body);
        font-size: 13.5px;
        color: var(--text-1);
        outline: none;
        transition: border-color 0.15s;
    }
    .field input:focus { border-color: var(--border-glow); background-color:rgba(110, 110, 110, 0.29);}

    .static-pill {
        display: inline-flex; align-items: center;
        font-size: 12px; font-weight: 600;
        padding: 9px 14px; border-radius: 10px;
    }
    .static-pill.is-verified { background: var(--green-dim); color: var(--green); }
    .static-pill.is-unverified { background: var(--amber-dim); color: var(--amber); }

    .form-actions { display: flex; justify-content: flex-end; }
    .btn-primary {
        background: linear-gradient(135deg, var(--indigo), var(--indigo-soft));
        color: #fff;
        border: none;
        font-family: var(--font-display);
        font-size: 13px; font-weight: 600;
        padding: 11px 22px;
        border-radius: 10px;
        cursor: pointer;
        transition: opacity 0.15s, transform 0.1s;
    }
    .btn-primary:hover { opacity: 0.9; }
    .btn-primary:active { transform: scale(0.97); }

    .flash-success, .flash-error {
        font-size: 13px; font-weight: 500;
        padding: 11px 15px; border-radius: 10px;
        margin-bottom: 18px;
    }
    .flash-success { background: var(--green-dim); color: var(--green); border: 1px solid rgba(52,211,153,0.3); }
    .flash-error   { background: var(--rose-dim);  color: var(--rose);  border: 1px solid rgba(248,113,113,0.3); }

    .verified-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--green-dim); color: var(--green);
        border: 1px solid rgba(52,211,153,0.3);
        font-size: 13px; font-weight: 600;
        padding: 10px 16px; border-radius: 10px;
    }
    .verified-badge svg { width: 16px; height: 16px; }

    .not-verified {
        font-size: 13px; color: var(--amber);
        margin-bottom: 14px; font-weight: 500;
    }

    .otp-trigger-form, .otp-verify-form { display: flex; gap: 10px; }
    .otp-box {
        margin-top: 18px;
        background: var(--bg-field);
        border: 1px solid var(--border-glow);
        border-radius: 12px;
        padding: 16px 18px;
    }
    .otp-box h4 {
        font-family: var(--font-mono);
        font-size: 13px; font-weight: 700;
        color: var(--indigo-soft);
        margin-bottom: 12px;
        letter-spacing: 0.03em;
    }
    .otp-verify-form input[type="text"] {
        background: var(--bg-deep);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 10px 14px;
        font-family: var(--font-mono);
        font-size: 13px;
        color: var(--text-1);
        outline: none;
        flex: 1;
        min-width: 0;
    }
    .otp-verify-form input[type="text"]:focus { border-color: var(--border-glow); }

    /* ── History grid ── */
    .history-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .history-list { display: flex; flex-direction: column; gap: 10px; }
    .history-row {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px;
        background: var(--bg-field);
        border: 1px solid var(--border);
        border-radius: 12px;
        transition: border-color 0.15s;
    }
    .history-row:hover { border-color: rgba(255,255,255,0.16); background-color:rgba(107, 107, 107, 0.45) }
    .history-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: var(--indigo-dim);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .history-icon svg { width: 17px; height: 17px; color: var(--indigo-soft); }
    .history-info { display: flex; flex-direction: column; gap: 2px; flex: 1; min-width: 0; }
    .history-info strong { font-size: 13px; font-weight: 600; color: var(--text-1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .history-info strong a { color: inherit; text-decoration: none; }
    .history-info strong a:hover { color: var(--indigo-soft); }
    .history-info span { font-size: 11px; color: var(--text-3); }

    .score-pill {
        font-family: var(--font-mono);
        font-size: 12px; font-weight: 700;
        padding: 5px 11px; border-radius: 20px;
        flex-shrink: 0;
    }
    .score-pill.is-high { background: var(--green-dim); color: var(--green); }
    .score-pill.is-mid  { background: var(--amber-dim); color: var(--amber); }
    .score-pill.is-low  { background: var(--rose-dim);  color: var(--rose); }
    .applied-link-pill {
    flex-shrink: 0;
    font-family: var(--font-mono);
    font-size: 11.5px;
    font-weight: 700;
    color: var(--indigo-soft);
    background: var(--indigo-dim);
    border: 1px solid var(--border-glow);
    padding: 6px 14px;
    border-radius: 20px;
    text-decoration: none;
    transition: opacity 0.15s;
}
.applied-link-pill:hover { opacity: 0.85; }
    .empty-text { font-size: 12.5px; color: var(--text-3); padding: 8px 2px; }

    @media (max-width: 900px) {
        .profile-layout { grid-template-columns: 1fr; }
        .identity-card { position: static; }
        .history-grid { grid-template-columns: 1fr; }
        .info-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 560px) {
        .profile-page { padding: 24px 16px 48px; }
        .panel-card { padding: 20px; }
        .otp-trigger-form, .otp-verify-form { flex-direction: column; }
    }
</style>

<div
    class="profile-page"
    data-has-phone="{{ $user->phone ? '1' : '0' }}"
    data-phone-verified="{{ $user->phone_verified ? '1' : '0' }}"
    data-resume-count="{{ $resumes->count() }}"
    data-profile-photo="{{ $user->profile_photo ? '1' : '0' }}"
>

    <div class="profile-header">
        <h1 class="profile-title">My Profile</h1>
        <p class="profile-subtitle">Manage your personal information, security and activity</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="success-msg">Profile updated successfully.</div>
    @elseif (session('status') === 'phone-changed')
        <div class="warning-msg">Your phone number was updated — please verify it again below.</div>
    @endif

    <div class="profile-layout">

        {{-- LEFT: Identity card --}}
        <aside class="identity-card">

            <div class="avatar-wrap">
                <svg class="avatar-ring-svg" viewBox="0 0 96 96">
                    <circle cx="48" cy="48" r="44" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="4"/>
                    <circle id="completenessRing" cx="48" cy="48" r="44" fill="none" stroke="#6C63FF" stroke-width="4" stroke-linecap="round" stroke-dasharray="0 276.5"/>
                </svg>
                <div class="avatar-circle" id="avatarCircle">

    @if($user->profile_photo)
        <img
            src="{{ asset('storage/'.$user->profile_photo) }}"
            style="width:100%;height:100%;object-fit:cover;">
    @else
        <span id="avatarInitial">
            {{ strtoupper(substr($user->name,0,1)) }}
        </span>
    @endif

</div>
@if($user->profile_photo)
<form action="{{ route('profile.photo.delete') }}"
      method="POST"
      style="position:absolute; top:-8px; right:-8px;">

    @csrf
    @method('DELETE')

    <button
        type="submit"
        onclick="return confirm('Remove profile photo?')"
        class="avatar-remove"
        title="Remove photo">
        ✕
    </button>
</form>
@endif
                <span class="completeness-badge" id="completenessBadge">0%</span>
                <label class="avatar-edit" for="avatarInput" title="Change photo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7h3l2-3h8l2 3h3v13H3z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                </label>
                <form id="avatarForm"
                    action="{{ route('profile.photo') }}"
                    method="POST"
                    enctype="multipart/form-data">

    @csrf

    <input
        type="file"
        id="avatarInput"
        name="photo"
        accept="image/*"
        hidden>
</form>
            </div>
            @if($user->profile_photo)

@endif

            <h3 class="identity-name">{{ $user->name }}</h3>
            <p class="identity-email">{{ $user->email }}</p>

            <div class="verify-pill {{ $user->phone_verified ? 'is-verified' : 'is-unverified' }}">
                @if ($user->phone_verified)
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.5 2.5 5-5"/>
                    </svg>
                    Phone Verified
                @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/><path d="M12 7.5v5"/><circle cx="12" cy="16" r="0.6" fill="currentColor"/>
                    </svg>
                    Phone Not Verified
                @endif
            </div>

            <div class="identity-stats">
                <div class="stat-block">
                    <span class="stat-number">{{ $topMatchesCount }}</span>
                    <span class="stat-label">Top Matches</span>
                </div>
                <div class="stat-block">
                    <span class="stat-number">{{ $resumes->count() }}</span>
                    <span class="stat-label">Resumes</span>
                </div>
            </div>

            <nav class="identity-nav">
                <button type="button" class="identity-nav-link {{ $activeTab === 'personal' ? 'is-active' : '' }}" data-tab="personal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="3.2"/><path d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/>
                    </svg>
                    Personal Details
                </button>
                <button type="button" class="identity-nav-link {{ $activeTab === 'security' ? 'is-active' : '' }}" data-tab="security">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z"/>
                    </svg>
                    Security
                </button>
            </nav>
            <form
            method="POST"
            action="{{ route('logout') }}"
            class="logout-form">

            @csrf

            <button type="submit" class="logout-btn">
                Logout
            </button>

        </form>

        </aside>

        {{-- RIGHT: Tabbed content + history --}}
        <section class="profile-main">

            {{-- Personal Details tab --}}
            <div class="tab-panel {{ $activeTab === 'personal' ? 'is-active' : '' }}" id="tab-personal" @if($activeTab !== 'personal') hidden @endif>
                <div class="panel-card">

                    <div class="panel-card-head">
                        <h2>Personal Info</h2>
                        <svg class="head-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 16.5V20h3.5L18 9.5l-3.5-3.5z"/><path d="M13 7l3.5 3.5"/>
                        </svg>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" class="info-form">
                        @csrf
                        @method('PATCH')

                        <div class="info-grid">
                            <div class="field">
                                <label>Full Name</label>
                                <input type="text" name="name" value="{{ $user->name }}">
                            </div>

                            <div class="field">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ $user->email }}">
                            </div>

                            <div class="field">
                                <label>Phone Number</label>
                                <input type="text" name="phone" value="{{ $user->phone }}">
                            </div>

                            <div class="field">
                                <label>Verification Status</label>
                                <div class="static-pill {{ $user->phone_verified ? 'is-verified' : 'is-unverified' }}">
                                    {{ $user->phone_verified ? 'Verified' : 'Unverified' }}
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Save Changes</button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- Security tab --}}
            <div class="tab-panel {{ $activeTab === 'security' ? 'is-active' : '' }}" id="tab-security" @if($activeTab !== 'security') hidden @endif>
                <div class="panel-card">

                    <div class="panel-card-head">
                        <h2>Phone Verification</h2>
                    </div>

                    @if (session('success'))
                        <div class="flash-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="flash-error">{{ session('error') }}</div>
                    @endif

                    @if ($user->phone_verified)

                        <div class="verified-badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.5 2.5 5-5"/>
                            </svg>
                            Phone Verified
                        </div>

                    @else

                        <div class="not-verified">
                            Phone Not Verified
                        </div>

                        <form action="/send-otp" method="POST" class="otp-trigger-form">
                            @csrf
                            <input type="hidden" name="phone" value="{{ $user->phone }}">
                            <button type="submit" class="btn-primary">Generate OTP</button>
                        </form>

                    @endif

                    @if (session('otp'))

                        <div class="otp-box">
                            <h4>Demo OTP: {{ session('otp') }}</h4>

                            <form action="/verify-otp" method="POST" class="otp-verify-form">
                                @csrf
                                <input type="text" name="otp" placeholder="Enter OTP">
                                <button type="submit" class="btn-primary">Verify OTP</button>
                            </form>
                        </div>

                    @endif

                </div>
            </div>

            {{-- Activity history — always visible below the tabs --}}
            <div class="history-grid">

                <div class="panel-card">
                    <div class="panel-card-head">
                        <h2>Career Match History</h2>
                    </div>

                    <div class="history-list">
                        @forelse ($selectedMatches ?? $matches as $match)
                            <div class="history-row">
                                <div class="history-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="7.5" width="18" height="12" rx="2"/>
                                        <path d="M8 7.5V6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1.5"/>
                                        <path d="M3 12.5h18"/>
                                    </svg>
                                </div>
                                <div class="history-info">
                                    <strong>{{ $match->job_title }}</strong>
                                    <span>{{ optional($match->created_at)->format('d M Y') }}</span>
                                </div>
                                <div class="score-pill {{ $match->match_score >= 80 ? 'is-high' : ($match->match_score >= 50 ? 'is-mid' : 'is-low') }}">
                                    {{ $match->match_score }}%
                                </div>
                            </div>
                        @empty
                            <p class="empty-text">No career matches found.</p>
                        @endforelse
                    </div>
                </div>

                <div class="panel-card">
                    <div class="panel-card-head">
                        <h2>Resume Upload History</h2>
                    </div>

                    <div class="history-list">
                        @forelse ($resumes as $resume)
                            <div class="history-row">
                                <div class="history-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M7 3h7l4 4v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1z"/>
                                        <path d="M14 3v4h4"/>
                                        <path d="M9 12h6M9 15.5h6M9 8.5h2"/>
                                    </svg>
                                </div>
                                <div class="history-info">
                                    <strong><a href="{{ route('profile.edit',['resume'=>$resume->id]) }}">Resume #{{$resume->id}}</a></strong>
                                    <span>{{ $resume->created_at->format('d M Y h:i A') }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="empty-text">No resumes uploaded yet.</p>
                        @endforelse
                    </div>
                </div>

            </div>
            {{-- Applied Jobs History --}}
<div class="panel-card">
    <div class="panel-card-head">
        <h2>Applied Jobs History</h2>
    </div>

    <div class="history-list">
        @forelse ($appliedJobs as $appliedJob)
            <div class="history-row">
                <div class="history-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="7" width="18" height="13" rx="2"/>
                        <path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        <path d="M3 12.5h18"/>
                    </svg>
                </div>
                <div class="history-info">
                    <strong>{{ $appliedJob->job_title }}</strong>
                    <span>{{ $appliedJob->company_name }}{{ $appliedJob->job_location ? ' • '.$appliedJob->job_location : '' }}</span>
                    <span>Applied {{ optional($appliedJob->applied_at)->format('d M Y') }}</span>
                </div>
                @if ($appliedJob->apply_link)
                    <a href="{{ $appliedJob->apply_link }}" target="_blank" rel="noopener" class="applied-link-pill">View</a>
                @endif
            </div>
        @empty
            <p class="empty-text">No jobs applied yet.</p>
        @endforelse
    </div>
</div>

        </section>

    </div>

</div>

<script>
    // Tab switching (Personal Details / Security)
    document.querySelectorAll('.identity-nav-link').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.identity-nav-link').forEach(function (b) { b.classList.remove('is-active'); });
            document.querySelectorAll('.tab-panel').forEach(function (p) { p.hidden = true; p.classList.remove('is-active'); });

            btn.classList.add('is-active');
            var panel = document.getElementById('tab-' + btn.dataset.tab);
            panel.hidden = false;
            panel.classList.add('is-active');
        });
    });

    // Avatar preview (client-side only — wire this up to an upload
    // route/controller if you want it to persist on the server)
    var avatarInput = document.getElementById('avatarInput');
    var avatarCircle = document.getElementById('avatarCircle');
    var avatarInitial = document.getElementById('avatarInitial');

    avatarInput.addEventListener('change', function () {
    if (this.files.length) {
        document.getElementById('avatarForm').submit();
    }
});

    // Profile completeness ring — name, email, phone, phone verified,
    // and at least one resume each count for one fifth of the ring.
    (function () {

    var page = document.querySelector('.profile-page');

    var hasName = "{{ $user->name }}" !== "";
    var hasEmail = "{{ $user->email }}" !== "";
    var hasPhone = page.dataset.hasPhone === '1';
    var phoneVerified = page.dataset.phoneVerified === '1';
    var hasResume = parseInt(page.dataset.resumeCount) > 0;
    var hasPhoto = page.dataset.profilePhoto === '1';

    var checks = [
        hasName,
        hasEmail,
        hasPhone,
        phoneVerified,
        hasResume,
        hasPhoto
    ];

    var done = checks.filter(Boolean).length;
    var pct = Math.round((done / checks.length) * 100);

    var circumference = 2 * Math.PI * 44;
    var dash = (pct / 100) * circumference;

    document
        .getElementById('completenessRing')
        .setAttribute(
            'stroke-dasharray',
            dash.toFixed(1) + ' ' + circumference.toFixed(1)
        );

    document.getElementById('completenessBadge').textContent = pct + '%';

})();
    
</script>
@endsection