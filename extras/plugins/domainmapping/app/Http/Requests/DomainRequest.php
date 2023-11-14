<?php

namespace extras\plugins\domainmapping\app\Http\Requests;

use App\Http\Requests\Request;

class DomainRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        
        $rules['country_code'] = 'required|min:2|max:2';
        $notRegex = '#http[^:]*://#ui';
        $rules['host'] = 'required|not_regex:' . $notRegex;
        
        if (in_array($this->method(), ['POST'])) {
            $rules['country_code'] = $rules['country_code'] . '|unique:domains,country_code';
            $rules['host'] = $rules['host'] . '|unique:domains,host';
        }
        
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            if (isset($this->id) && !empty($this->id)) {
                $rules['country_code'] = $rules['country_code'] . '|unique:domains,country_code,' . $this->id;
                $rules['host'] = $rules['host'] . '|unique:domains,host,' . $this->id;
            }
        }
        
        return $rules;
    }
    
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'country_code.required' => trans('domainmapping::messages.validation.country_code.required'),
            'country_code.min'      => trans('domainmapping::messages.validation.country_code.min'),
            'country_code.max'      => trans('domainmapping::messages.validation.country_code.max'),
            'country_code.unique'   => trans('domainmapping::messages.validation.country_code.unique'),
            'host.required'         => trans('domainmapping::messages.validation.host.required'),
            'host.regex'            => trans('domainmapping::messages.validation.host.regex'),
            'host.unique'           => trans('domainmapping::messages.validation.host.unique'),
        ];
    }
}
