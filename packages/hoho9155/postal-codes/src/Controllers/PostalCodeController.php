<?php

namespace Hoho9155\PostalCodes\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Hoho9155\PostalCodes\Controllers\Traits\PostalCodeTrait;


class PostalCodeController extends BaseController
{
    use PostalCodeTrait;

    public function search()
    {
        // $cities = $this->getCities('GB', 'W7');
        $records = $this->fetchRecords('GB', 'W7 1AA');
        return response()->json($records, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
