<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Resume;
use App\Models\JobMatch;
use App\Models\AppliedJob;

class ProfileController extends Controller
{
    public function uploadPhoto(Request $request)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);

    $user = auth()->user();

    if ($user->profile_photo) {
        Storage::disk('public')->delete($user->profile_photo);
    }

    $path = $request->file('photo')->store('profile_photos', 'public');

    $user->profile_photo = $path;
    $user->save();

    return back()->with('success', 'Photo updated successfully');
}
public function deletePhoto()
{
    $user = auth()->user();

    if ($user->profile_photo) {
        Storage::disk('public')->delete($user->profile_photo);

        $user->profile_photo = null;
        $user->save();
    }

    return back()->with('success', 'Profile photo removed.');
}
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        // 1. Get all resumes for the upload history list
        $resumes = Resume::where('user_id', $user->id)
            ->latest()
            ->get();

        // 2. Handle the Match History logic
        $selectedResumeId = request('resume');
        
        $matches = collect(); // Default to an empty collection
        $selectedMatches = null; 

        if ($selectedResumeId) {
            // If they clicked a specific resume, fetch matches for that exact ID
            $selectedMatches = JobMatch::where('resume_id', $selectedResumeId)
                ->orderByDesc('match_score')
                ->get();
        } else {
            // If they just loaded the page normally, fetch matches for their most recent resume
            $latestResume = $resumes->first();
            
            if ($latestResume) {
                $matches = JobMatch::where('resume_id', $latestResume->id)
                    ->orderByDesc('match_score')
                    ->get();
            }
        }

        // 3. Get applied jobs
        $appliedJobs = AppliedJob::where('user_id', $user->id)
            ->orderByDesc('applied_at')
            ->get();
        $topMatchesCount = JobMatch::whereIn(
            'resume_id',
            $resumes->pluck('id')
        )
        ->where('match_score', '>=', 50)
        ->count();
        return view('profile.edit', compact(
            'user',
            'resumes',
            'matches',
            'selectedMatches',
            'appliedJobs',
            'topMatchesCount'
        ));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // The existing verification only ever applied to the old number —
        // if the phone field changed, it needs to be verified again.
        $phoneChanged = $request->phone !== $user->phone;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'phone_verified' => $phoneChanged ? false : $user->phone_verified,
            'otp' => $phoneChanged ? null : $user->otp,
            'two_factor_enabled' => $request->has('two_factor_enabled'),
        ]);

        return Redirect::route('profile.edit')->with(
            'status',
            $phoneChanged ? 'phone-changed' : 'profile-updated'
        );
    }

    public function sendOtp(Request $request)
    {
        $otp = rand(1000, 9999);
        $user = auth()->user();

        $user->update([
            'phone' => $request->phone,
            'otp' => $otp,
            'phone_verified' => false,
        ]);

        return back()->with('otp', $otp);
    }

    public function verifyOtp(Request $request)
    {
        $user = auth()->user();

        if ($request->otp == $user->otp) {
            $user->update([
                'phone_verified' => true,
                'otp' => null,
            ]);

            return back()->with('success', 'Phone verified successfully.');
        }

        // Re-flash the same code so the verify box stays open and the
        // person can just try again instead of generating a new OTP.
        return back()
            ->with('error', 'Invalid OTP.')
            ->with('otp', $user->otp);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function resumeMatches($id)
    {
        $resume=Resume::findOrFail($id);
        $matches=JobMatch::where(
            'resume_id',
            $resume->id
        )
        ->orderByDesc('match_score')
        ->get();

        return view(
            'profile.resume-matches',
            compact(
                'resume',
                'matches'
            )
        );
    }


}