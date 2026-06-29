{{--
    Career Navigator floating CTA + assistant drawer.

    Props:
      userId         (string|int|null)  - authenticated user id, for personalizing/logging AI context
      currentRole    (string|null)      - job seeker's current role / title
      targetIndustry (string|null)      - industry they're aiming for

    Usage:
      <x-career-navigator-cta
          :user-id="auth()->id()"
          current-role="Frontend Developer"
          target-industry="Fintech"
      />
--}}

@props([
    'userId' => null,
    'currentRole' => 'Job Seeker',
    'targetIndustry' => null,
])

@php
    $navId = 'cnv-' . uniqid();
@endphp

<div
    id="{{ $navId }}"
    class="fixed bottom-6 right-6 z-[9999]"
    data-user-id="{{ $userId }}"
    data-current-role="{{ $currentRole }}"
    data-target-industry="{{ $targetIndustry }}"
>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap');

        #{{ $navId }} {
            font-family: 'Inter', sans-serif;
        }
        #{{ $navId }} .cnv-display { font-family: 'Space Grotesk', sans-serif; }
        #{{ $navId }} .cnv-mono { font-family: 'JetBrains Mono', monospace; }

        #{{ $navId }} .cnv-drawer {
            transform: translateY(16px) scale(0.96);
            opacity: 0;
            pointer-events: none;
            transition: transform .25s cubic-bezier(.16,1,.3,1), opacity .2s ease;
        }
        #{{ $navId }} .cnv-drawer.cnv-open {
            transform: translateY(0) scale(1);
            opacity: 1;
            pointer-events: auto;
        }

        #{{ $navId }} .cnv-scroll::-webkit-scrollbar { width: 6px; }
        #{{ $navId }} .cnv-scroll::-webkit-scrollbar-track { background: transparent; }
        #{{ $navId }} .cnv-scroll::-webkit-scrollbar-thumb {
            background: rgba(139,92,246,0.45);
            border-radius: 999px;
        }

        @media (prefers-reduced-motion: reduce) {
            #{{ $navId }} .cnv-drawer { transition: none; }
            #{{ $navId }} .animate-ping,
            #{{ $navId }} .animate-pulse,
            #{{ $navId }} .animate-bounce { animation: none !important; }
        }
    </style>

    <!-- Assistant Drawer -->
    <div
        id="{{ $navId }}-drawer"
        class="cnv-drawer absolute bottom-[76px] right-0 w-[380px] h-[500px] flex flex-col rounded-2xl overflow-hidden border border-indigo-500/20 bg-slate-900 shadow-2xl shadow-indigo-950/60"
        role="dialog"
        aria-label="Career Navigator assistant"
        aria-hidden="true"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border-b border-indigo-500/20 shrink-0">
            <div class="flex items-center gap-2.5">
                <span class="text-xl leading-none">🧭</span>
                <div>
                    <p class="cnv-display text-white text-sm font-semibold leading-tight">Career Navigator AI</p>
                    <p class="cnv-mono text-[10px] text-emerald-400 tracking-widest flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block animate-pulse"></span>
                        ONLINE
                    </p>
                </div>
            </div>
            <button
                type="button"
                class="cnv-close text-slate-400 hover:text-white text-xl leading-none transition"
                aria-label="Close assistant"
            >&times;</button>
        </div>

        <!-- Scrollable message body -->
        <div class="cnv-body cnv-scroll flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-slate-950/40">
            <div class="flex gap-2">
                <span class="text-base leading-none">🧭</span>
                <div class="bg-indigo-500/10 border border-indigo-500/20 text-slate-200 text-sm rounded-xl rounded-tl-sm px-3 py-2 max-w-[260px]">
                    Hi! I'm tracking your profile{{ $currentRole ? " as a {$currentRole}" : '' }}{{ $targetIndustry ? " heading into {$targetIndustry}" : '' }}. How can I help today?
                </div>
            </div>
        </div>

        <!-- Prompt pills (rendered from PREBUILT_QA below — add a question
             there with pill:true and it shows up here automatically) -->
        <div class="cnv-pills flex flex-wrap gap-2 px-4 pb-3 shrink-0"></div>

        <!-- Input bar -->
        <div class="flex items-center gap-2 px-3 py-3 border-t border-indigo-500/20 bg-slate-900 shrink-0">
            <input
                type="text"
                class="cnv-input flex-1 bg-slate-800/80 text-sm text-white placeholder-slate-500 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-violet-500/60"
                placeholder="Ask about your career..."
                aria-label="Message Career Navigator AI"
            >
            <button
                type="button"
                class="cnv-send w-9 h-9 flex items-center justify-center rounded-lg bg-gradient-to-r from-indigo-600 to-violet-500 text-white hover:brightness-110 active:scale-95 transition"
                aria-label="Send message"
            >➤</button>
        </div>
    </div>

    <!-- Floating toggle button -->
    <button
        type="button"
        class="cnv-toggle relative w-16 h-16 rounded-full bg-gradient-to-r from-indigo-600 to-violet-500 text-white text-2xl flex items-center justify-center shadow-lg shadow-violet-900/50 transition-all duration-200 hover:-translate-y-1 hover:shadow-violet-500/60 focus:outline-none focus-visible:ring-2 focus-visible:ring-violet-400"
        aria-expanded="false"
        aria-controls="{{ $navId }}-drawer"
        aria-label="Open Career Navigator assistant"
    >
        <span class="absolute inset-0 rounded-full bg-violet-500/40 animate-ping"></span>
        <span class="cnv-icon relative z-10 leading-none">🧭</span>
    </button>
</div>

<script>
(function () {
    const root = document.getElementById('{{ $navId }}');
    if (!root) return;

    const toggleBtn  = root.querySelector('.cnv-toggle');
    const closeBtn   = root.querySelector('.cnv-close');
    const drawer     = root.querySelector('.cnv-drawer');
    const body       = root.querySelector('.cnv-body');
    const input      = root.querySelector('.cnv-input');
    const sendBtn    = root.querySelector('.cnv-send');
    const pillsHost  = root.querySelector('.cnv-pills');
    const icon       = root.querySelector('.cnv-icon');

    // Context passed from the parent Blade view — use this to personalize
    // answers and once you wire up a real backend.
    const context = {
        userId: root.dataset.userId || null,
        currentRole: root.dataset.currentRole || null,
        targetIndustry: root.dataset.targetIndustry || null,
    };
    const ctxText = {
        role: context.currentRole || 'your current role',
        industry: context.targetIndustry ? ` in ${context.targetIndustry}` : '',
    };

    // ---- Prebuilt Q&A knowledge base --------------------------------------
    // This is the whole "brain" of the assistant. Add as many entries as you
    // like. `pill: true` also renders it as a quick-tap chip in the drawer.
    // `keywords` are lowercase substrings checked against anything the user
    // types — if none match, CONTACT_FALLBACK is shown instead.
    const PREBUILT_QA = [
        {
            question: 'Applied Job History',
            icon: '📄',
            pill: true,
            keywords: ['applied jobs', 'history', 'my applications', 'track jobs'],
            answer: () => 'To track your application history and review your latest updates, navigate to your Profile page and check the Applied Jobs section.',
        },

        {
            question: 'Find skill gaps',
            icon: '🧠',
            pill: true,
            keywords: ['skill gap', 'skills gap', 'missing skill'],
            answer: () => `Let's analyze your trajectory. Navigate to your personalized Career Match page 
            to cross-reference your profile, isolate missing critical skills, and unlock custom learning resources`,
        },

        {
        question: 'Get live jobs',
        icon: '💼',
        pill: true,
        keywords: ['live jobs', 'openings', 'current vacancies', 'find jobs'],
        answer: () => 'To view real-time vacancies matched to your current background, navigate to the Live Jobs page.',
    },
    {
        question: 'Top career match',
        icon: '🎯',
        pill: true,
        keywords: ['career match', 'best fit', 'compatible roles', 'career paths'],
        answer: () => 'To discover your highest-probability career paths and alternative trajectories, navigate to the Career Matches dashboard.',
    },
        // Example of a question that's answerable but not shown as a pill —
        // copy this block to add more without cluttering the chip row.
        // {
        //     question: 'How do I write a cover letter?',
        //     icon: '✉️',
        //     pill: false,
        //     keywords: ['cover letter'],
        //     answer: () => 'Keep it to three short paragraphs: why this role, your strongest proof point, and a clear close.',
        // },
    ];

    const CONTACT_FALLBACK_HTML =
        'I don\'t have a preset answer for that one yet. For anything outside these topics, email ' +
        '<a href="mailto:career.navigator@gmail.com" class="text-violet-300 underline hover:text-violet-200">career.navigator@gmail.com</a> ' +
        'and our team will help directly.';

    function normalize(str) {
        return (str || '').toLowerCase().trim().replace(/[?!.]+$/, '');
    }

    function findMatch(text) {
        const normalized = normalize(text);
        if (!normalized) return null;
        return PREBUILT_QA.find(function (entry) {
            if (normalized === entry.question.toLowerCase()) return true;
            return entry.keywords.some(function (kw) { return normalized.includes(kw); });
        }) || null;
    }

    function renderPills() {
        if (!pillsHost) return;
        PREBUILT_QA.filter(function (entry) { return entry.pill; }).forEach(function (entry) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'cnv-mono text-[11px] px-3 py-1.5 rounded-full border border-indigo-400/30 bg-indigo-500/10 text-indigo-200 hover:bg-indigo-500/20 hover:border-indigo-400/60 transition';
            btn.textContent = `${entry.icon} ${entry.question}`;
            btn.addEventListener('click', function () { send(entry.question, entry); });
            pillsHost.appendChild(btn);
        });
    }

    function openDrawer() {
        drawer.classList.add('cnv-open');
        drawer.setAttribute('aria-hidden', 'false');
        toggleBtn.setAttribute('aria-expanded', 'true');
        icon.textContent = '✕';
        input.focus();
    }

    function closeDrawer() {
        drawer.classList.remove('cnv-open');
        drawer.setAttribute('aria-hidden', 'true');
        toggleBtn.setAttribute('aria-expanded', 'false');
        icon.textContent = '🧭';
    }

    function toggleDrawer() {
        drawer.classList.contains('cnv-open') ? closeDrawer() : openDrawer();
    }

    toggleBtn.addEventListener('click', toggleDrawer);
    closeBtn.addEventListener('click', closeDrawer);

    // For plain text — always safe to use with arbitrary/user-typed input.
    function appendMessage(text, from) {
        const wrap = document.createElement('div');
        if (from === 'user') {
            wrap.className = 'flex justify-end';
            const bubble = document.createElement('div');
            bubble.className = 'bg-gradient-to-r from-indigo-600 to-violet-500 text-white text-sm rounded-xl rounded-tr-sm px-3 py-2 max-w-[260px]';
            bubble.textContent = text;
            wrap.appendChild(bubble);
        } else {
            wrap.className = 'flex gap-2';
            const avatar = document.createElement('span');
            avatar.className = 'text-base leading-none';
            avatar.textContent = '🧭';
            const bubble = document.createElement('div');
            bubble.className = 'bg-indigo-500/10 border border-indigo-500/20 text-slate-200 text-sm rounded-xl rounded-tl-sm px-3 py-2 max-w-[260px]';
            bubble.textContent = text;
            wrap.appendChild(avatar);
            wrap.appendChild(bubble);
        }
        body.appendChild(wrap);
        body.scrollTop = body.scrollHeight;
    }

    // For trusted, hardcoded bot strings only (e.g. CONTACT_FALLBACK_HTML)
    // — never pass user-typed input through this one.
    function appendHtmlMessage(html) {
        const wrap = document.createElement('div');
        wrap.className = 'flex gap-2';
        const avatar = document.createElement('span');
        avatar.className = 'text-base leading-none';
        avatar.textContent = '🧭';
        const bubble = document.createElement('div');
        bubble.className = 'bg-indigo-500/10 border border-indigo-500/20 text-slate-200 text-sm rounded-xl rounded-tl-sm px-3 py-2 max-w-[260px]';
        bubble.innerHTML = html;
        wrap.appendChild(avatar);
        wrap.appendChild(bubble);
        body.appendChild(wrap);
        body.scrollTop = body.scrollHeight;
    }

    function showTyping() {
        const wrap = document.createElement('div');
        wrap.className = 'flex gap-2';
        wrap.innerHTML =
            '<span class="text-base leading-none">🧭</span>' +
            '<div class="bg-indigo-500/10 border border-indigo-500/20 rounded-xl rounded-tl-sm px-3 py-2.5 flex gap-1 items-center">' +
            '<span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></span>' +
            '<span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay:.15s"></span>' +
            '<span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay:.3s"></span>' +
            '</div>';
        body.appendChild(wrap);
        body.scrollTop = body.scrollHeight;
        return wrap;
    }

    function send(text, matchedEntry) {
        text = (text || '').trim();
        if (!text) return;

        appendMessage(text, 'user');
        input.value = '';

        const typing = showTyping();

        setTimeout(function () {
            typing.remove();

            const entry = matchedEntry || findMatch(text);
            if (entry) {
                appendMessage(entry.answer(), 'bot');
            } else {
                appendHtmlMessage(CONTACT_FALLBACK_HTML);
            }

            // --- Real backend integration point ---
            // Once you have a real AI endpoint, you could call it only when
            // there's no local match, e.g.:
            //
            // if (!entry) {
            //     fetch('/api/career-navigator/chat', {
            //         method: 'POST',
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            //         },
            //         body: JSON.stringify({ message: text, ...context }),
            //     })
            //         .then(r => r.json())
            //         .then(data => appendMessage(data.reply, 'bot'));
            // }
        }, 700);
    }

    sendBtn.addEventListener('click', function () { send(input.value); });
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') send(input.value);
    });

    renderPills();
})();
</script>