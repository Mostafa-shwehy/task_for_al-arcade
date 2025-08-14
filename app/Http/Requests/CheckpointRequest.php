<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckpointRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
       public function rules(): array
    {
        return [
            'timestamp_seconds' => [
                'required', 'integer', 'min:1',
                function ($attribute, $value, $fail) {
                    $video = request()->route('video');
                    if ($video && $value > $video->duration_seconds) {
                        error('The timestamp must be less than or equal to the video duration.',[],402);
                    }
                }
            ],
            'event_type' => 'required|in:quiz,note,popup',
            'event_data' => 'required|array'
        ];
    }
}
