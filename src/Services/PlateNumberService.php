<?php

namespace MPWT\VRDL\Services;

use Illuminate\Http\Request;
use MPWT\VRDL\Models\VPNPlateNumber;

class PlateNumberService
{

    private function makeCriteriaFromRequest(Request $request)
    {
        $criterias = [];

        if ($request->is_sale) {
            $criterias[] = PlateNumberEnum::AVAILABLE;
        }

        if ($request->is_request) {
            $criterias[] = PlateNumberEnum::REQUEST;
        }

        if (empty($criterias)) {
            $criterias = [
                PlateNumberEnum::AVAILABLE,
                PlateNumberEnum::REQUEST
            ];
        }

        return $criterias;
    }

    public function index(Request $request)
    {
        // select latest 30 base on criteria [is_sale, is_request]

        $criterias = $this->makeCriteriaFromRequest($request);

        return VPNPlateNumber::with('vpnStatus')
            ->whereIn('vpn_status_id', $criterias)
            ->orderBy('vpn_status_id', 'desc')
            ->orderBy('last_search_date', 'desc')
            ->orderBy('n_of_hits', 'desc')
            ->limit(30);
    }

    public function search(Request $request)
    {
        // select searching plate number base on criteria [is_sale, is_request]
        $criterias = $this->makeCriteriaFromRequest($request);

        array_push(
            $criterias,
            PlateNumberService::SOLD,
            PlateNumberService::BAN
        );

        return VPNPlateNumber::with('vpnStatus')
            ->whereIn('vpn_status_id', $criterias)
            ->orderBy('vpn_status_id', 'desc')
            ->orderBy('last_search_date', 'desc')
            ->orderBy('n_of_hits', 'desc')
            ->first();
    }
}
