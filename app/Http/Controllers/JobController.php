<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\JobMatch;
use App\Models\Resume;
use App\Models\AppliedJob;

class JobController extends Controller
{
    public function liveJobs()
    {
        $latestResume = Resume::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->first();

        if (!$latestResume) {

            return redirect('/dashboard')
                ->with(
                    'error',
                    'Please upload a resume first.'
                );
        }

        $topMatch = JobMatch::where(
            'resume_id',
            $latestResume->id
        )
        ->orderByDesc('match_score')
        ->first();

        if (
            !$topMatch ||
            $topMatch->match_score <= 0
        ) {
            return redirect('/dashboard')
                ->with(
                    'error',
                    'No valid career recommendation found.'
                );
        }

        $response = Http::withHeaders([
            'X-RapidAPI-Key' => config('services.rapidapi.key'),
            'X-RapidAPI-Host' => 'jsearch.p.rapidapi.com'
        ])->get(
            'https://jsearch.p.rapidapi.com/search',
            [
                'query' => $topMatch->job_title,
                'page' => 1,
                'num_pages' => 1
            ]
        );

        $jobs = $response->json()['data'] ?? [];

        return view(
            'live-jobs',
            compact(
                'jobs',
                'topMatch'
            )
        );
    }
    public function apply(Request $request)
{
    $validated = $request->validate([
        'resume_id'    => 'nullable|integer|exists:resumes,id',
        'job_title'    => 'required|string|max:255',
        'company_name' => 'required|string|max:255',
        'job_location' => 'nullable|string|max:255',
        'apply_link'   => 'required|url',
    ]);

    $alreadyApplied = AppliedJob::where(
        'user_id',
        auth()->id()
    )
    ->where('job_title', $validated['job_title'])
    ->where('company_name', $validated['company_name'])
    ->exists();

    if (!$alreadyApplied) {
        AppliedJob::create([
            'user_id'      => auth()->id(),
            'resume_id'    => $validated['resume_id'] ?? null,
            'job_title'    => $validated['job_title'],
            'company_name' => $validated['company_name'],
            'job_location' => $validated['job_location'] ?? null,
            'apply_link'   => $validated['apply_link'],
            'applied_at'   => now(),
        ]);
    }

    return redirect()->away($validated['apply_link']);
}
}