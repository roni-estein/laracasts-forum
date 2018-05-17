<?php

namespace App\Http\Requests\Form;

use App\Exceptions\ThrottleException;
use App\Reply;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class AddReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return Gate::allows('create', new Reply);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|spamfree'
        ];
    }

    protected function failedAuthorization()
    {
        throw new ThrottleException('You are posting too frequently, please take a break', 429);
    }

    public function persist($thread)
    {
        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
    }
}
