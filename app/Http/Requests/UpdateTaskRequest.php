<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=> 'sometimes|string|max:255',
            'due_date' => 'nullable|date',
            'status' =>'sometimes|in:pending,in_progress,completed',
            'project_id' => 'sometimes|integer|exists:projects,id',
            'assigned_to' => 'nullable|integer|exists:users,id'
        ];
    }
}
