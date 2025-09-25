<?php

namespace App\Services;

use App\Traits\CheckAuthorize;
use Illuminate\Support\Facades\Validator;

class BaseService {
    use CheckAuthorize;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Validate all datas to execute the service.
     *
     * @param  array  $data
     * @return bool
     */
    public function validate(array $data): bool
    {
        Validator::make($data, $this->rules())
            ->validate();

        return true;
    }

    /**
     * Summary of execute
     * @param array $data
     * @return void
     */
    public function execute(array $data)
    {
        $this->checkAuthorize();
        $this->validate($data);
    }
}