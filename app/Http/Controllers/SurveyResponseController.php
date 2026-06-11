<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Support\Facades\DB;

class SurveyResponseController extends Controller
{
    public function show(string $token)
    {
        $invite = $this->getInvite($token);

        if (! $invite->canBeAnswered()) {

            return response()->view(
                'survey.invalid',
                [
                    'invite' => $invite,
                    'reason' => $invite->unavailableReason(),
                ],
                403
            );
        }

        return view('survey.show', [
            'invite' => $invite,
            'survey' => $invite->survey,
        ]);
    }

    public function store(string $token)
    {
        $invite = $this->getInvite($token);

        if (! $invite->canBeAnswered()) {

            return response()->view(
                'survey.invalid',
                compact('invite'),
                403
            );
        }

        $rules = [];

        if (! $invite->survey->anonymous) {

            $rules['name'] = [
                'required',
                'string',
                'max:255',
            ];

            $rules['email'] = [
                'required',
                'email',
                'max:255',
            ];
        }

        foreach ($invite->survey->questions as $question) {

            $field = "questions.{$question->id}";

            $rules[$field] = $question->required
                ? ['required']
                : ['nullable'];
        }

        $data = request()->validate($rules);

        DB::transaction(function () use ($invite, $data) {

            $respondent = null;

            if (! $invite->survey->anonymous) {

                $respondent = $invite->respondent()->create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                ]);
            }

            $response = $invite->response()->create([
                'survey_id' => $invite->survey_id,
                'respondent_id' => $respondent?->id,
                'submitted_at' => now(),
                'user_agent' => request()->userAgent(),
            ]);

            foreach ($invite->survey->questions as $question) {

                $response->items()->create([
                    'question_id' => $question->id,
                    'answer' => $data['questions'][$question->id] ?? null,
                ]);
            }

            $invite->update([
                'status' => 'answered',
                'responded_at' => now(),
                'responded_ip' => request()->ip(),
            ]);
        });

        return view('survey.thank-you', [
            'survey' => $invite->survey,
        ]);
    }

    private function getInvite(string $token): Invite
    {
        return Invite::query()
            ->with([
                'survey.questions',
            ])
            ->where('token', $token)
            ->firstOrFail();
    }
}
