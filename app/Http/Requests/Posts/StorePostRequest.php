<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Store Post Request Validation
 */
class StorePostRequest extends FormRequest
{


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
     */
    public function authorize(): bool
    {
        return Auth::hasUser();

    }//end authorize()


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title'   => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],
            'content' => [
                'required',
                'string',
                'min:5',
                'max:65535',
            ],
        ];

    }//end rules()


}//end class
