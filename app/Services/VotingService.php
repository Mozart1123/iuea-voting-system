<?php

namespace App\Services;

use App\Models\Voter;
use App\Models\Vote;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class VotingService
{
    /**
     * Process a student's ballot atomically.
     */
    public function castBallot(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Register the Voter participation
            $voter = Voter::create([
                'registration_number' => $data['registration_number'],
                'full_name' => $data['full_name'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'voted_at' => now(),
            ]);

            // 2. Clear out any previous votes for this reg number just in case (should be blocked by unique constraint)
            
            // 3. Process each category vote
            foreach ($data['votes'] as $categoryId => $candidateId) {
                if (!$candidateId) continue;

                // Create integrity hash
                $hash = hash('sha256', implode('|', [
                    $voter->id,
                    $categoryId,
                    $candidateId,
                    config('app.key') // Secret salt
                ]));

                Vote::create([
                    'voter_id' => $voter->id,
                    'category_id' => $categoryId,
                    'candidate_id' => $candidateId,
                    'integrity_hash' => $hash,
                ]);
            }

            // 4. Log the action
            AuditLog::create([
                'action' => 'VOTE_SUBMITTED',
                'description' => "Votes submitted for registration number: {$data['registration_number']}",
                'ip_address' => request()->ip(),
                'payload' => [
                    'voter_id' => $voter->id,
                    'categories' => array_keys($data['votes'])
                ]
            ]);

            return $voter;
        });
    }

    /**
     * Verify the integrity of all votes.
     */
    public function verifyIntegrity()
    {
        $votes = Vote::all();
        $compromised = [];

        foreach ($votes as $vote) {
            $expectedHash = hash('sha256', implode('|', [
                $vote->voter_id,
                $vote->category_id,
                $vote->candidate_id,
                config('app.key')
            ]));

            if ($vote->integrity_hash !== $expectedHash) {
                $compromised[] = $vote->id;
                Log::critical("Vote integrity compromised: ID {$vote->id}");
            }
        }

        return $compromised;
    }
}
