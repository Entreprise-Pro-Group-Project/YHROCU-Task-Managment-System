<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
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
        'task_name'         => 'required|string|max:255',
        'task_description'  => 'required|string',
        'assigned_staff' => 'required|string|max:255',
        'assigned_date'     => 'required|date',
        'due_date'          => 'required|date|after_or_equal:assigned_date',
        ];
    }

    /**
     * Get custom validation error messages.
     */
    public function messages(): array
    {
        return [
        'task_name.required'         => 'Please enter the task name.',
        'task_name.string'           => 'The task name must be a valid string.',
        'task_name.max'              => 'The task name may not exceed 255 characters.',

        'task_description.required'  => 'Please provide a task description.',
        'task_description.string'    => 'The task description must be a valid string.',

        'assigned_staff.required' => 'Please specify the assigned staff first name.',
        'assigned_staff.string'   => 'The assigned staff first name must be valid text.',
        'assigned_staff.max'      => 'The assigned staff first name may not exceed 255 characters.',

        'assigned_date.required'     => 'Please provide an assigned date.',
        'assigned_date.date'         => 'The assigned date must be a valid date.',

        'due_date.required'          => 'Please provide a due date.',
        'due_date.date'              => 'The due date must be a valid date.',
        'due_date.after_or_equal'    => 'The due date must be the same as or after the assigned date.',
        ];
    }
}
