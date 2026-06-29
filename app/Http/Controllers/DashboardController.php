<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resume;
use App\Models\Skill;
use App\Models\JobMatch;

class DashboardController extends Controller
{
    public function index()
    {
        $latestResume = Resume::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->first();

        $skills = [];

        if ($latestResume) {

            $skills = Skill::where(
                'resume_id',
                $latestResume->id
            )
            ->pluck('skill_name')
            ->unique()
            ->values();
        }

        $topMatch = null;

        if ($latestResume) {

            $topMatch = JobMatch::where(
                'resume_id',
                $latestResume->id
            )
            ->orderByDesc('match_score')
            ->first();
        }

        return view(
            'dashboard',
            compact(
                'latestResume',
                'skills',
                'topMatch'
            )
        );
    }
}