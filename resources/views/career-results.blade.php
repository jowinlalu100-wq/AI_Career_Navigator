@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500;600&display=swap');

.results-container{
    --void:#050617;
    --panel:#11142e;
    --panel-soft:#171b42;
    --line: rgba(255,255,255,0.08);
    --violet:#7c6cff;
    --violet-glow: rgba(124,108,255,0.45);
    --gold:#f3c673;
    --gold-glow: rgba(243,198,115,0.4);
    --cyan:#43e6c8;
    --cyan-glow: rgba(67,230,200,0.35);
    --danger:#ff5d73;
    --danger-glow: rgba(255,93,115,0.35);
    --text:#eef0fb;
    --text-dim:#8d92b8;
    --font-display:'Space Grotesk', sans-serif;
    --font-body:'Inter', sans-serif;
    --font-mono:'JetBrains Mono', monospace;

    position:relative;
    isolation:isolate;
    max-width:1180px;
    margin:0 auto;
    padding:48px 32px 80px;
    font-family:var(--font-body);
    color:var(--text);
    overflow:hidden;
    
}

.results-container::before{
    content:'';
    position:fixed;
    inset:0;
    z-index:-2;
    background:
        radial-gradient(circle at 12% 8%, var(--violet-glow), transparent 38%),
        radial-gradient(circle at 88% 78%, var(--gold-glow), transparent 42%),
        radial-gradient(circle at 50% 50%, #090b22, var(--void) 70%);
    pointer-events:none;
}

.results-container::after{
    content:'';
    position:fixed;
    inset:0;
    z-index:-1;
    background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 42px 42px;
    mask-image: radial-gradient(circle at 50% 30%, black, transparent 75%);
    pointer-events:none;
}

/* ---------- Upload alert ---------- */
.alert{
    position:relative;
    background:linear-gradient(160deg, var(--panel) 0%, var(--panel-soft) 100%);
    border:1px solid var(--line);
    border-radius:18px;
    padding:30px 28px;
    margin-bottom:36px;
    box-shadow: 0 0 0 1px rgba(255,255,255,0.02) inset, 0 18px 40px rgba(0,0,0,0.35);
    animation: rise-in .5s ease both;
}
.alert::before{
    content:'';
    position:absolute;
    inset:0;
    border-radius:18px;
    padding:1px;
    background:linear-gradient(135deg, rgba(67,230,200,0.55), transparent 45%, rgba(243,198,115,0.3));
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events:none;
}
.alert .card-tag{ color:var(--cyan); }
.alert h2{
    font-family:var(--font-display);
    font-size:1.4rem;
    font-weight:600;
    margin:0 0 18px;
}
.alert h3{
    font-family:var(--font-mono);
    font-size:.7rem;
    letter-spacing:.2em;
    text-transform:uppercase;
    color:var(--text-dim);
    margin:0 0 12px;
}
.alert .primary-btn{ margin-top:22px; display:inline-block; text-decoration:none; }

/* ---------- Page header ---------- */
.page-header{ position:static; margin-bottom:36px; }
.page-header .eyebrow{
    display:inline-block;
    font-family:var(--font-mono);
    font-size:.7rem;
    letter-spacing:.32em;
    text-transform:uppercase;
    color:var(--cyan);
    margin-bottom:14px;
}
.page-header h1{
    font-family:var(--font-display);
    font-weight:700;
    font-size:2.4rem;
    letter-spacing:-0.01em;
    margin:0 0 6px;
    background:linear-gradient(120deg, #fff 10%, var(--violet) 60%, var(--gold) 100%);
    -webkit-background-clip:text;
    background-clip:text;
    color:transparent;
}
.page-header p{ font-size:.96rem; color:var(--text-dim); margin:0 0 20px; }
.header-divider{
    height:2px;
    border-radius:2px;
    background:linear-gradient(90deg, var(--violet), var(--cyan), var(--gold), transparent);
    background-size:200% 100%;
    animation: shimmer 6s linear infinite;
}

/* ---------- Results grid ---------- */
.results-grid{
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap:24px;
}

.career-card{
    --delay:0;
    position:relative;
    background:linear-gradient(160deg, var(--panel) 0%, var(--panel-soft) 100%);
    border:1px solid var(--line);
    border-radius:18px;
    padding:26px 24px;
    box-shadow: 0 0 0 1px rgba(255,255,255,0.02) inset, 0 18px 40px rgba(0,0,0,0.35);
    animation: rise-in .55s ease both;
    animation-delay: calc(var(--delay) * 90ms);
}
.career-card::before{
    content:'';
    position:absolute;
    inset:0;
    border-radius:18px;
    padding:1px;
    background:linear-gradient(135deg, rgba(124,108,255,0.5), transparent 40%, rgba(243,198,115,0.3));
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events:none;
    opacity:.7;
}
.career-card h2{
    font-family:var(--font-display);
    font-size:1.18rem;
    font-weight:600;
    margin:6px 0 14px;
    color: rgb(106, 255, 0);
}
.score-text{
    font-family:var(--font-mono);
    font-size:1.6rem;
    font-weight:600;
    color:var(--gold);
    margin-bottom:8px;
}
.progress-bar{
    height:8px;
    border-radius:6px;
    background:rgba(255,255,255,0.07);
    overflow:hidden;
    margin-bottom:22px;
}
.progress-fill{
    height:100%;
    border-radius:6px;
    background:linear-gradient(90deg, var(--violet), var(--gold));
    box-shadow:0 0 12px var(--gold-glow);
    transition: width 1s cubic-bezier(.16,1,.3,1);
}

.career-card h4{
    font-family:var(--font-mono);
    font-size:.68rem;
    letter-spacing:.16em;
    text-transform:uppercase;
    margin:18px 0 10px;
    color:var(--text-dim);
}

.skill-badge{
    display:inline-block;
    font-family:var(--font-mono);
    font-size:.76rem;
    letter-spacing:.02em;
    padding:7px 13px;
    margin:0 6px 8px 0;
    border:1px solid var(--line);
    background:rgba(255,255,255,0.03);
    color:var(--text);
    clip-path: polygon(8px 0, 100% 0, calc(100% - 8px) 100%, 0 100%);
}
.skill-badge.success{
    color:var(--cyan);
    border-color:rgba(67,230,200,0.35);
    background:rgba(67,230,200,0.06);
}
.skill-badge.danger{
    color:var(--danger);
    border-color:rgba(255,93,115,0.35);
    background:rgba(255,93,115,0.06);
}

.resources-section{
    margin-top:18px;
    padding-top:18px;
    border-top:1px dashed var(--line);
}
.resource-link{
    display:inline-block;
    font-family:var(--font-body);
    font-size:.82rem;
    font-weight:500;
    color:var(--cyan);
    text-decoration:none;
    padding:8px 14px;
    margin:0 8px 8px 0;
    border:1px solid rgba(67,230,200,0.35);
    border-radius:9px;
    transition: background .15s ease, color .15s ease;
}
.resource-link:hover{ background:var(--cyan); color:#06061a; }

/* ---------- Shared buttons ---------- */
.primary-btn{
    font-family:var(--font-body);
    font-weight:600;
    font-size:.92rem;
    color:#06061a;
    background:linear-gradient(120deg, var(--cyan), var(--violet));
    border:none;
    border-radius:12px;
    padding:13px 20px;
    cursor:pointer;
    box-shadow: 0 8px 24px var(--cyan-glow);
    transition: transform .15s ease, box-shadow .15s ease;
}
.primary-btn:hover{ transform:translateY(-1px); box-shadow:0 10px 28px var(--violet-glow); }

.card-tag{
    display:inline-block;
    font-family:var(--font-mono);
    font-size:.65rem;
    letter-spacing:.22em;
    color:var(--text-dim);
    margin-bottom:6px;
}

/* ---------- Motion ---------- */
@keyframes rise-in{ from{ opacity:0; transform:translateY(10px); } to{ opacity:1; transform:translateY(0); } }
@keyframes shimmer{ to{ background-position:-200% 0; } }

a:focus-visible, button:focus-visible{ outline:2px solid var(--cyan); outline-offset:3px; }

@media (prefers-reduced-motion: reduce){
    .alert, .career-card{ animation:none; }
    .header-divider{ animation:none; }
    .progress-fill{ transition:none; }
}
</style>

<div class="results-container">

@if(isset($message))

<div class="alert">

    <span class="card-tag">UPLOAD COMPLETE</span>
    <h2>{{ $message }}</h2>

    @if(count($skills) > 0)

        <h3>Detected Skills</h3>

        @foreach($skills as $skill)

            <span class="skill-badge success">
                {{ $skill }}
            </span>

        @endforeach

    @endif

    <a href="/dashboard" class="primary-btn">
        Upload Another Resume
    </a>

</div>

@endif

@if(isset($results) && count($results) > 0)

<div class="page-header">

    <span class="eyebrow">AI Career Navigator</span>
    <h1>Career Match Results</h1>

    <p>
        Based on your uploaded resume and detected skills.
    </p>

    <div class="header-divider"></div>

</div>

<div class="results-grid">

    @foreach($results as $result)

    <div class="career-card" style="--delay: {{ $loop->index }};">

        <span class="card-tag">MATCH {{ sprintf('%02d', $loop->iteration) }}</span>

        <h2>{{ $result['career'] }}</h2>

        <div class="score-text">
            {{ $result['score'] }}%
        </div>

        <div class="progress-bar">

            <div
                class="progress-fill"
                style="width: {{ $result['score'] }}%"
                data-target="{{ $result['score'] }}">
            </div>

        </div>

        <h4>✓ Matched Skills</h4>

        @foreach($result['matched_skills'] as $skill)

            <span class="skill-badge success">
                {{ $skill }}
            </span>

        @endforeach

        <h4>✗ Missing Skills</h4>

        @foreach($result['missing_skills'] as $skill)

            <span class="skill-badge danger">
                {{ $skill }}
            </span>

        @endforeach

        @if(count($result['resources']) > 0)

            <div class="resources-section">

                <h4>📚 Learning Resources</h4>

                @foreach($result['resources'] as $skill => $link)

                    <a
                        href="{{ $link }}"
                        target="_blank"
                        class="resource-link">

                        Learn {{ $skill }}

                    </a>

                @endforeach

            </div>

        @endif

    </div>

    @endforeach

</div>

@endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion) return;

    var fills = document.querySelectorAll('.progress-fill');
    fills.forEach(function (el, i) {
        var target = el.dataset.target || 0;
        el.style.width = '0%';
        setTimeout(function () {
            el.style.width = target + '%';
        }, 150 + i * 90);
    });
});
</script>

@endsection