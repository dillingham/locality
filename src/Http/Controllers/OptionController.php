<?php

namespace Dillingham\Locality\Http\Controllers;

use Dillingham\Locality\Facades\Locality;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OptionController extends Controller
{
    public function countryCodes(Request $request)
    {
        return Locality::countryCode()
            ->select(['display', 'id as value'])
            ->paginate(200);
    }

    public function adminLevel1(Request $request)
    {
        $request->validate(['country_code_id' => ['required', 'numeric']]);

        return Locality::adminLevel1()
            ->select(['display', 'id as value'])
            ->where(['country_code_id' => $request->country_code_id])
            ->paginate(200);
    }

    public function adminLevel2(Request $request)
    {
        $request->validate(['admin_level_1_id' => ['required', 'numeric']]);

        return Locality::adminLevel2()
            ->select(['display', 'id as value'])
            ->where(['admin_level_1_id' => $request->admin_level_1_id])
            ->paginate(200);
    }

    public function adminLevel3(Request $request)
    {
        $request->validate(['admin_level_2_id' => ['required', 'numeric']]);

        return Locality::adminLevel3()
            ->select(['display', 'id as value'])
            ->where(['admin_level_2_id' => $request->admin_level_2_id])
            ->paginate(200);
    }

    public function postalCodes(Request $request)
    {
        $request->validate(['admin_level_3_id' => ['required', 'numeric']]);

        return Locality::postalCode()
            ->select(['display', 'id as value'])
            ->where(['admin_level_3_id' => $request->admin_level_3_id])
            ->paginate(200);
    }
}
