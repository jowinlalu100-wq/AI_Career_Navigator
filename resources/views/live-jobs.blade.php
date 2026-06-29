<!DOCTYPE html>
<html>
<head>
    <title>Live Job Recommendations</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <style>
        html, body {
            margin: 0;
            padding: 0;
            background-color: #0A0E1F;
            min-height: 100vh;
            width: 100%;
        }

        .jobs-page, .jobs-page * { box-sizing: border-box; }

        .jobs-page {
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
        .jobs-header {
            max-width: 1180px;
            margin: 0 auto 32px;
        }
        .jobs-eyebrow {
            font-family: var(--font-mono);
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: var(--indigo-soft);
            margin: 0 0 8px;
        }
        .jobs-title {
            font-family: var(--font-display);
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: var(--text-1);
            margin: 0 0 16px;
        }
        .jobs-title span { color: var(--indigo-soft); }

        .match-score-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-mono);
            font-size: 13px;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 20px;
        }
        .match-score-chip svg { width: 15px; height: 15px; }
        .match-score-chip.is-high { background: var(--green-dim); color: var(--green); border: 1px solid rgba(52,211,153,0.3); }
        .match-score-chip.is-mid  { background: var(--amber-dim); color: var(--amber); border: 1px solid rgba(251,191,36,0.3); }
        .match-score-chip.is-low  { background: var(--rose-dim);  color: var(--rose);  border: 1px solid rgba(248,113,113,0.3); }

        /* ── Job grid ── */
        .jobs-grid {
            max-width: 1180px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 22px;
        }

        .job-card {
            position: relative;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 24px;
            overflow: hidden;
            transition: border-color 0.2s, transform 0.2s;
            display: flex;
            flex-direction: column;
        }
        .job-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--indigo), transparent);
            opacity: 0.7;
        }
        .job-card:hover {
            border-color: var(--border-glow);
            transform: translateY(-4px);
        }

        .job-type-tag {
            display: inline-flex;
            align-self: flex-start;
            font-family: var(--font-mono);
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--indigo-soft);
            background: var(--indigo-dim);
            border: 1px solid var(--border-glow);
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 14px;
        }

        .job-card h2 {
            font-family: var(--font-display);
            font-size: 17px;
            font-weight: 700;
            color: var(--text-1);
            margin: 0 0 10px;
            line-height: 1.3;
        }

        .job-meta-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-2);
            margin-bottom: 6px;
        }
        .job-meta-row svg { width: 14px; height: 14px; flex-shrink: 0; color: var(--text-3); }
        .job-meta-row.location { color: var(--text-3); margin-bottom: 18px; }

        .apply-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: linear-gradient(135deg, var(--indigo), var(--indigo-soft));
            color: #fff;
            border: none;
            font-family: var(--font-display);
            font-size: 13px;
            font-weight: 600;
            padding: 12px 18px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: auto;
            transition: opacity 0.15s, transform 0.1s;
        }
        .apply-btn:hover { opacity: 0.9; }
        .apply-btn:active { transform: scale(0.97); }
        .apply-btn svg { width: 15px; height: 15px; }

        @media (max-width: 700px) {
            .jobs-page { padding: 28px 18px 48px; }
            .jobs-title { font-size: 22px; }
            .jobs-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="jobs-page">

    <div class="jobs-header">
        <p class="jobs-eyebrow">LIVE JOB MATCHES</p>
        <h1 class="jobs-title">
            Live Jobs for <span>{{ $topMatch->job_title }}</span>
        </h1>

        <div class="match-score-chip {{ $topMatch->match_score >= 80 ? 'is-high' : ($topMatch->match_score >= 50 ? 'is-mid' : 'is-low') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13 2L4 14h6l-1 8 9-12h-6z"/>
            </svg>
            Match Score: {{ $topMatch->match_score }}%
        </div>
    </div>

    <div class="jobs-grid">
        @foreach($jobs as $job)

        <div class="job-card">

            <span class="job-type-tag">{{ $job['job_employment_type'] }}</span>

            <h2>{{ $job['job_title'] }}</h2>

            <div class="job-meta-row">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="7" width="18" height="14" rx="2"/>
                    <path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                </svg>
                {{ $job['employer_name'] }}
            </div>

            <div class="job-meta-row location">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 21s-7-6.5-7-11a7 7 0 1 1 14 0c0 4.5-7 11-7 11z"/>
                    <circle cx="12" cy="10" r="2.5"/>
                </svg>
                {{ $job['job_location'] }}
            </div>

            <form action="{{ route('jobs.apply') }}" method="POST">
                @csrf
                <input type="hidden" name="resume_id" value="{{ $topMatch->resume_id }}">
                <input type="hidden" name="job_title" value="{{ $job['job_title'] }}">
                <input type="hidden" name="company_name" value="{{ $job['employer_name'] }}">
                <input type="hidden" name="job_location" value="{{ $job['job_location'] }}">
                <input type="hidden" name="apply_link" value="{{ $job['job_apply_link'] }}">

                <button type="submit" class="apply-btn">
                    Apply Job
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 17L17 7M17 7H8M17 7v9"/>
                    </svg>
                </button>
            </form>

        </div>

        @endforeach
    </div>

</div>

</body>
</html>