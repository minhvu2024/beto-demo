<?php
namespace Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormDataPostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function messages()
    {
        return [
            'name.required' => 'The name is Required.',
            'description.required'  => 'The description is Required.',
            'content.required'  => 'The content is Required.',
        ];
    }
    public function rules()
    {
        return [
            'name' => 'bail|required|max:255',
            'description' => 'required|max:255',
            'content' => 'required',
            "status"=>'required',
        ];
    }
}