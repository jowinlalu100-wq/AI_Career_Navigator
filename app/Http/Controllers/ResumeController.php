<?php

namespace App\Http\Controllers;

use Smalot\PdfParser\Parser;
use Illuminate\Http\Request;
use App\Models\Resume;
use App\Models\Skill;

class ResumeController extends Controller
{
    public function upload(Request $request)
    {
        $path = $request->file('resume')
                        ->store('resumes', 'public');

        $resume = Resume::create([
            'user_id' => auth()->id(),
            'file_path' => $path
        ]);

        $this->parseResume($resume->id);

        return redirect('/career-match/' . $resume->id);
    }

    public function parseResume($resumeId)
    {
        $resume = Resume::findOrFail($resumeId);

        $parser = new Parser();

        $pdf = $parser->parseFile(
            storage_path('app/public/' . $resume->file_path)
        );

        $text = $pdf->getText();

        $resume->parsed_text = $text;
        $resume->save();

        $this->extractSkills($resume);
    }

    private function extractSkills($resume)
    {
        $knownSkills = [
            'PHP',
            'Laravel',
            'Java',
            'Python',
            'MySQL',
            'JavaScript',
            'React',
            'Git',
            'Docker'
        ];

        foreach ($knownSkills as $skill) {

            if (
                preg_match(
                    '/\b' . preg_quote($skill, '/') . '\b/i',
                    $resume->parsed_text
                )
            ) {

                Skill::create([
                    'resume_id' => $resume->id,
                    'skill_name' => $skill
                ]);
            }
        }
    }
}