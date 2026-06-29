<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resume;
use App\Models\Skill;
use App\Models\Career;
use App\Models\JobMatch;

class CareerController extends Controller
{
    public function recommend($resumeId)
    {
        $resume = Resume::findOrFail($resumeId);

        $userSkills = Skill::where(
            'resume_id',
            $resume->id
        )
        ->pluck('skill_name')
        ->unique()
        ->values()
        ->toArray();

        if (count($userSkills) == 0) {

            return view(
                'career-results',
                [
                    'results' => [],
                    'message' => 'No skills detected in the uploaded resume.',
                    'skills' => []
                ]
            );
        }

        $careers = Career::all();

        $results = [];

        $learningResources = [
            'Laravel' => 'https://laravel.com/docs',
            'React' => 'https://react.dev/learn',
            'JavaScript' => 'https://developer.mozilla.org/en-US/docs/Web/JavaScript',
            'PHP' => 'https://www.php.net/manual/en/',
            'Python' => 'https://docs.python.org/3/tutorial/',
            'Django' => 'https://docs.djangoproject.com/en/stable/',
            'Flask' => 'https://flask.palletsprojects.com/',
            'MySQL' => 'https://dev.mysql.com/doc/',
            'Git' => 'https://git-scm.com/doc',
            'Docker' => 'https://docs.docker.com/',
            'Figma' => 'https://help.figma.com/',
            'Adobe XD' => 'https://helpx.adobe.com/xd.html'
        ];

        foreach ($careers as $career) {

            $requiredSkills = array_map(
                'trim',
                explode(',', $career->required_skills)
            );

            $matches = count(
                array_intersect(
                    $userSkills,
                    $requiredSkills
                )
            );

            $missingSkills = array_diff(
                $requiredSkills,
                $userSkills
            );

            $totalSkills = count($requiredSkills);

            $score = $totalSkills > 0
                ? ($matches / $totalSkills) * 100
                : 0;

            $score = min(
                round($score, 2),
                100
            );

            if ($score >= 20) {

                $results[] = [

                    'career' => $career->career_name,

                    'score' => $score,

                    'matched_skills' => array_unique(
                        array_intersect(
                            $userSkills,
                            $requiredSkills
                        )
                    ),

                    'missing_skills' => $missingSkills,

                    'resources' => array_filter(
                        $learningResources,
                        function ($key) use ($missingSkills) {
                            return in_array(
                                $key,
                                $missingSkills
                            );
                        },
                        ARRAY_FILTER_USE_KEY
                    )
                ];

                JobMatch::updateOrCreate(
    // 1st Array: The conditions to check (Find by Resume ID and Job Title)
    [
        'resume_id' => $resume->id,
        'job_title' => $career->career_name,
    ],
    // 2nd Array: The data to insert or update (Update the score and user)
    [
        'user_id' => auth()->id(),
        'company' => 'Career Navigator',
        'match_score' => $score,
    ]
);
            }
        }

        if (count($results) == 0) {

            return view(
                'career-results',
                [
                    'results' => [],
                    'message' => 'No suitable career matches found for this resume.',
                    'skills' => $userSkills
                ]
            );
        }

        usort(
            $results,
            fn($a, $b) => $b['score'] <=> $a['score']
        );

        return view(
            'career-results',
            compact('results')
        );
    }

    public function latestMatch()
    {
        $resume = Resume::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->first();

        if (!$resume) {

            return redirect('/dashboard')
                ->with(
                    'error',
                    'Please upload a resume first.'
                );
        }

        return redirect(
            '/career-match/' . $resume->id
        );
    }
}