<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow all authenticated users to create a project. Adjust if necessary.
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Here we assume your project has these fields:
     * - project_name: required, string, maximum 255 characters.
     * - project_date: required, must be a valid date.
     * - due_date: required, must be a valid date, and must be on or after the project_date.
     * - supervisor_name: required, string, maximum 255 characters.
     */
    public function rules(): array
    {
        return [
            'project_name'    => 'required|string|max:255',
            'project_description' => 'required|string|min:10|max:1000',
            'project_date'    => 'required|date',
            'due_date'        => 'required|date|after_or_equal:project_date',
            'supervisor_name' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'project_name.required'    => 'Please enter the project name.',
            'project_name.string'      => 'The project name must be a valid string.',
            'project_name.max'         => 'The project name may not exceed 255 characters.',

            'project_description.required' => 'Please provide a project description.',
            'project_description.string'   => 'The project description must be valid text.',
            'project_description.min'      => 'The project description must be at least 10 characters long.',
            'project_description.max'      => 'The project description may not exceed 1000 characters.',
            
            'project_date.required'    => 'The project start date is required.',
            'project_date.date'        => 'Please enter a valid project start date.',
            
            'due_date.required'        => 'The project due date is required.',
            'due_date.date'            => 'Please enter a valid project due date.',
            'due_date.after_or_equal'  => 'The due date must be on or after the project start date.',
            
            'supervisor_name.required' => 'Please specify the supervisor name.',
            'supervisor_name.string'   => 'The supervisor name must be a valid string.',
            'supervisor_name.max'      => 'The supervisor name may not exceed 255 characters.',
        ];
    }
}
