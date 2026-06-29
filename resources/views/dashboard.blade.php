@extends('layouts.app')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500;600&display=swap');

@property --score {
    syntax: '<number>';
    inherits: true;
    initial-value: 0;
}

.dashboard-container{
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
    --text:#eef0fb;
    --text-dim:#8d92b8;
    --font-display:'Space Grotesk', sans-serif;
    --font-body:'Inter', sans-serif;
    --font-mono:'JetBrains Mono', monospace;

    position:relative;
    isolation:isolate;
    max-width:1180px;
    margin:0 auto;
    padding:48px 32px 20px;
    font-family:var(--font-body);
    color:var(--text);
    margin-top: -90px;
    
}

.dashboard-container::before{
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

.dashboard-container::after{
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

/* ---------- Header ---------- */
.page-header{ position:relative; margin-bottom:40px; }
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
    font-size:2.6rem;
    letter-spacing:-0.01em;
    margin:0 0 6px;
    background:linear-gradient(120deg, #fff 10%, var(--violet) 60%, var(--gold) 100%);
    -webkit-background-clip:text;
    background-clip:text;
    color:transparent;
}
.page-header p{
    font-size:.98rem;
    color:var(--text-dim);
    margin:0;
}
.header-divider{
    margin-top:24px;
    height:2px;
    border-radius:2px;
    background:linear-gradient(90deg, var(--violet), var(--cyan), var(--gold), transparent);
    background-size:200% 100%;
    animation: shimmer 6s linear infinite;
}

/* ---------- Grid ---------- */
.dashboard-grid{
    display:grid;
    grid-template-columns: 1fr 1.35fr 1fr;
    gap:24px;
    align-items:start;
}

.card, .match-card{
    position:relative;
    background:linear-gradient(160deg, var(--panel) 0%, var(--panel-soft) 100%);
    border:1px solid var(--line);
    border-radius:18px;
    padding:28px 26px;
    box-shadow: 0 0 0 1px rgba(255,255,255,0.02) inset, 0 18px 40px rgba(0,0,0,0.35);
    animation: rise-in .6s ease both;
}
.card::before, .match-card::before{
    content:'';
    position:absolute;
    inset:0;
    border-radius:18px;
    padding:1px;
    background:linear-gradient(135deg, rgba(124,108,255,0.5), transparent 40%, rgba(243,198,115,0.35));
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events:none;
    opacity:.7;
}
.card h2, .match-card h2{
    font-family:var(--font-display);
    font-size:1.18rem;
    font-weight:600;
    margin:0 0 18px;
    color:var(--text);
}
.card-tag{
    display:inline-block;
    font-family:var(--font-mono);
    font-size:.65rem;
    letter-spacing:.22em;
    color:var(--text-dim);
    margin-bottom:10px;
}

/* ---------- Upload card ---------- */
.upload-card form{ display:flex; flex-direction:column; gap:16px; }
.upload-dropzone{
    position:relative;
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:8px;
    text-align:center;
    padding:30px 16px;
    border:1.5px dashed rgba(124,108,255,0.4);
    border-radius:14px;
    cursor:pointer;
    background:rgba(124,108,255,0.04);
    transition: border-color .2s ease, background .2s ease;
}
.upload-dropzone:hover{
    border-color:var(--violet);
    background:rgba(124,108,255,0.09);
}
.upload-dropzone .file-input{
    position:absolute;
    inset:0;
    opacity:0;
    cursor:pointer;
}
.upload-icon{
    width:38px; height:38px;
    border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    background:rgba(124,108,255,0.15);
    color:var(--violet);
    font-size:1.1rem;
}
.upload-text{ font-size:.88rem; color:var(--text-dim); }
.file-name{
    font-family:var(--font-mono);
    font-size:.74rem;
    color:var(--cyan);
}
.primary-btn{
    font-family:var(--font-body);
    font-weight:600;
    font-size:.92rem;
    color:#06061a;
    background:linear-gradient(120deg, var(--cyan), var(--violet));
    border:none;
    border-radius:12px;
    padding:13px 18px;
    cursor:pointer;
    box-shadow: 0 8px 24px var(--cyan-glow);
    transition: transform .15s ease, box-shadow .15s ease;
}
.primary-btn:hover{ transform:translateY(-1px); box-shadow:0 10px 28px var(--violet-glow); }

/* ---------- Match card ---------- */
.match-card{ text-align:center; }
.career-title{
    font-family:var(--font-display);
    font-size:1.3rem;
    font-weight:600;
    margin-bottom:18px;
    color:var(--text);
}
.score-ring{
    --score:0;
    width:172px; height:172px;
    margin:6px auto 26px;
    border-radius:50%;
    background: conic-gradient(var(--gold) calc(var(--score)*1%), rgba(255,255,255,0.07) 0);
    display:flex; align-items:center; justify-content:center;
    filter: drop-shadow(0 0 18px var(--gold-glow));
    transition: --score 1.4s cubic-bezier(.16,1,.3,1);
}
.score-ring-inner{
    width:130px; height:130px;
    border-radius:50%;
    background:radial-gradient(circle at 32% 28%, var(--panel-soft), var(--panel) 72%);
    border:1px solid var(--line);
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    box-shadow: inset 0 0 26px rgba(0,0,0,0.55);
}
.score-value{ font-family:var(--font-mono); font-size:2.4rem; font-weight:600; line-height:1; }
.score-unit{ font-family:var(--font-mono); font-size:1rem; color:var(--gold); margin-left:2px; }
.score-label{ font-family:var(--font-mono); font-size:.62rem; letter-spacing:.24em; color:var(--text-dim); margin-top:6px; }

.action-buttons{ display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
.btn-primary, .btn-secondary{
    font-family:var(--font-body);
    font-weight:600;
    font-size:.86rem;
    text-decoration:none;
    padding:12px 20px;
    border-radius:10px;
    transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
}
.btn-primary{
    color:#06061a;
    background:linear-gradient(120deg, var(--violet), var(--gold));
    box-shadow: 0 8px 22px var(--violet-glow);
}
.btn-primary:hover{ transform:translateY(-1px); box-shadow:0 10px 26px var(--gold-glow); }
.btn-secondary{
    color:var(--text);
    border:1px solid var(--line);
    background:rgba(255,255,255,0.03);
}
.btn-secondary:hover{ border-color:var(--cyan); color:var(--cyan); }

/* Empty match state */
.radar-empty{
    position:relative;
    width:120px; height:120px;
    margin:8px auto 22px;
    display:flex; align-items:center; justify-content:center;
}
.radar-ring{
    position:absolute;
    border:1px solid rgba(124,108,255,0.4);
    border-radius:50%;
    animation: pulse-ring 2.6s ease-out infinite;
}
.radar-ring{ width:100%; height:100%; }
.radar-ring-2{ animation-delay:1.3s; }
.radar-dot{
    width:10px; height:10px;
    border-radius:50%;
    background:var(--violet);
    box-shadow:0 0 14px var(--violet-glow);
}

/* ---------- Skills card ---------- */
.skills-container{ display:flex; flex-wrap:wrap; gap:10px; }
.skill-badge{
    font-family:var(--font-mono);
    font-size:.78rem;
    letter-spacing:.02em;
    color:var(--cyan);
    padding:8px 14px;
    border:1px solid rgba(67,230,200,0.35);
    background:rgba(67,230,200,0.06);
    clip-path: polygon(8px 0, 100% 0, calc(100% - 8px) 100%, 0 100%);
    animation: rise-in .45s ease both;
    animation-delay: calc(var(--delay, 0) * 60ms);
}

.card p{ color:var(--text-dim); font-size:.92rem; line-height:1.5; }

/* ---------- Motion ---------- */
@keyframes rise-in{ from{ opacity:0; transform:translateY(10px); } to{ opacity:1; transform:translateY(0); } }
@keyframes shimmer{ to{ background-position:-200% 0; } }
@keyframes pulse-ring{
    0%{ transform:scale(.4); opacity:.9; }
    100%{ transform:scale(1.15); opacity:0; }
}

a, button, input{ outline-offset:3px; }
a:focus-visible, button:focus-visible, input:focus-visible{
    outline:2px solid var(--cyan);
}

@media (prefers-reduced-motion: reduce){
    .card, .match-card, .skill-badge{ animation:none; }
    .header-divider{ animation:none; }
    .radar-ring{ animation:none; }
    .score-ring{ transition:none; }
}

@media (max-width: 980px){
    .dashboard-grid{ grid-template-columns:1fr; }
    .match-card{ order:-1; }
}
</style>

<div class="dashboard-container">

    <div class="page-header">
        <span class="eyebrow">AI Career Navigator</span>
        <h1>Dashboard</h1>
        <p>AI Career Navigator Overview</p>
        <div class="header-divider"></div>
    </div>

    <div class="dashboard-grid">

        <!-- Upload Resume -->

        <div class="card" id="upload-section">

           
            <h2>Upload Resume</h2>

            <form action="/resume/upload"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <label for="resume-input" class="upload-dropzone">
                    <input
                        type="file"
                        name="resume"
                        id="resume-input"
                        class="file-input"
                        required>
                    <span class="upload-icon">&#8593;</span>
                    <span class="upload-text">Click or drop your resume file</span>
                    <span class="file-name" id="file-name-display">No file selected</span>
                </label>

                <button
                    type="submit"
                    class="primary-btn">

                    Upload Resume

                </button>

            </form>

        </div>

        <!-- Top Career Match -->

        <div class="match-card">

@if($topMatch)

        {{--Top Match Card--}}

    <h2>Top Career Match</h2>

    <div class="career-title">
        {{ $topMatch->job_title }}
    </div>

    <div class="score-ring"
         style="--score: {{ $topMatch->match_score ?? 0 }};"
         data-score="{{ $topMatch->match_score ?? 0 }}">
        <div class="score-ring-inner">
            <span class="score-value">{{ $topMatch->match_score ?? 0 }}</span><span class="score-unit">%</span>
            <span class="score-label">MATCH</span>
        </div>
    </div>

    <div class="action-buttons">

        @if($latestResume)
            <a href="/career-match/{{$latestResume->id}}"
               class="btn-primary">

               Career Match
            </a>
        @else
            <a href="#upload-section"
               class="btn-primary">

               Upload Resume first
            </a>
        @endif
        <a href="/live-jobs"
           class="btn-secondary">
            Live Jobs
        </a>

    </div>

@else

   

    <div class="radar-empty">
        <div class="radar-ring"></div>
        <div class="radar-ring radar-ring-2"></div>
        <div class="radar-dot"></div>
    </div>

    <h2>No Career Match Available</h2>

    <p>
        Upload a resume to generate recommendations.
    </p>

@endif

</div>

    <!-- Skills -->

    <div class="card">

        
        <h2>Detected Skills</h2>

        @if(count($skills))

            <div class="skills-container">

                @foreach($skills as $skill)

                    <span class="skill-badge" style="--delay: {{ $loop->index }};">
                        {{ $skill }}
                    </span>

                @endforeach

            </div>

        @else

            <p>
                Upload a resume to detect skills.
            </p>

        @endif

    </div>



</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var ring = document.querySelector('.score-ring');
    if (ring) {
        var target = ring.dataset.score || 0;
        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduceMotion) {
            ring.style.setProperty('--score', target);
        } else {
            ring.style.setProperty('--score', 0);
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    ring.style.setProperty('--score', target);
                });
            });
        }
    }

    var fileInput = document.getElementById('resume-input');
    var fileNameDisplay = document.getElementById('file-name-display');
    if (fileInput && fileNameDisplay) {
        fileInput.addEventListener('change', function () {
            fileNameDisplay.textContent = fileInput.files.length ? fileInput.files[0].name : 'No file selected';
        });
    }
});
</script>
<x-career-navigator-cta
    :user-id="auth()->id()"
    :current-role="$latestResume->desired_role ?? 'Job Seeker'"
    :target-industry="$latestResume->target_industry ?? null"
/>


@endsection