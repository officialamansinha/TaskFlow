<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'name'=> 'string|required|max:255',
            'due_date' => 'nullable|date',
            'status' =>'sometimes|in:pending,in_progress,completed',
            'project_id' => 'integer|required|exists:projects,id',
            'assigned_to' => 'nullable|integer|exists:users,id'
        ];
    }
}
