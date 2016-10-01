<?php
/**
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: https://github.com/disono/Laravel-Template & http://www.webmons.com
 * Copyright 2016 Webmons Development Studio.
 * License: Apache 2.0
 */
namespace App\Http\Requests\Admin;

class RoleUpdate extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:roles,id',
            'name' => 'required|max:100',
            'slug' => 'required|max:100|alpha_dash|unique:roles,slug,' . $this->get('id'),
            'description' => 'max:500'
        ];
    }
}
