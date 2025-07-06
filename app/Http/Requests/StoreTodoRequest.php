<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTodoRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'status' => 'required|in:offen,erledigt',
            'category_type' => 'required|string|max:50',
        ];

        switch ($this->input('category_type')) {
            case 'WorkTask':
                $rules['priority'] = 'required|string|max:255';
                break;
            case 'PersonalTask':
                $rules['mood'] = 'required|string|max:255';
                break;
            case 'ShoppingTask':
                $rules['estimated_cost'] = 'required|numeric';
                break;
        }

        return $rules;
    }
}
