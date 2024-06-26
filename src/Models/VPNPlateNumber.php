<?php

namespace MPWT\VRDL\Models;

use App\Enums\V2_0_0\PlateNumbers\PlateNumberButton;
use App\Enums\V2_0_0\PlateNumbers\PlateNumberEnum;
use Illuminate\Database\Eloquent\Model;

class VPNPlateNumber extends Model
{

    protected $table = 'V_PLATE_NUMBER_PRICINGS';

    protected $appends = ['hide_button_name'];

    protected $visible = [
        'vpn_id',
        'plate_no',
        'range_no',
        'selling_price',
        'add_price_per_range',
        'vehicle_type_code',
        'plate_type_code',
        'plate_type_name_kh',
        'plate_type_name_en',
        'vpn_status_id',
        'vpn_status_name_kh',
        'vpn_status_name_en',
        'n_of_hits',
        'last_search_date',
        'is_personalized_plate',
        'is_own_plate',
        'required_note_en',
        'required_note_kh',
        'discount',
        'extra_price',
        'n_of_range',
        'total_charge_amount',
        'full_plate_no',
        'hide_button_name',
        'auction',
        'vpnStatus'
    ];

    public function toArray()
    {
        $this->setHiddenAttribute();
        return array_merge(parent::toArray());
    }

    protected function setHiddenAttribute()
    {
        // n_of_range, selling_price, total_charged_amount, discount
        if (in_array($this->status, [
            PlateNumberEnum::BAN,
            PlateNumberEnum::SOLD,
            PlateNumberEnum::BLOCKED
        ])) {
            $this->setHidden([
                'n_of_range',
                'selling_price',
                'total_charge_amount',
                'add_price_per_range',
                'extra_price'
            ]);
        }
    }

    public function plateType()
    {
        return $this->belongsTo('App\Models\V2_0_0\PlateNumbers\VPNClassProvince', 'vpn_class_province_code', 'province_code');
    }

    public function vpnStatus()
    {
        return $this->hasOne('App\Models\V2_0_0\PlateNumbers\VPNStatus', 'vpn_status_id', 'vpn_status_id')->with(['vpnStatusAction']);
    }

    /// =============>> Auction
    public function auction()
    {
        return $this->hasOne('App\Models\V2_0_0\Auction\Auction', 'plate_id', 'vpn_id')
            ->select('id', 'plate_id', 'current_price', 'current_offering_amount', 'started_price', 'started_at', 'expired_at')
            ->with([
                'lastOffer',
                //'receipt'
            ])->withCount([
                'offers',
                'watchers'
            ]);
    }

    public function getHideButtonNameAttribute()
    {
        $isZeroPrice = '';
        if ($this->total_charge_amount <= 0) {
            $isZeroPrice = PlateNumberButton::BOOK_PLATE_NUMBER;
        }
        return $isZeroPrice;
    }

    public function getRequiredNoteEnAttribute()
    {
        if ($this->vpn_status_id == PlateNumberEnum::REQUEST) {
            return 'This number not for sale to purchase this number, please submit it to the commission. Click the Request button to proceed';
        }
        return '';
    }

    public function getRequiredNoteKhAttribute()
    {
        if ($this->vpn_status_id == PlateNumberEnum::REQUEST) {
            return 'លេខនេះមិនទាន់មានតម្លៃនោះទេចង់ទិញលេខនេះត្រូវស្នើរទៅគណៈកម្មការសូមចុចបូតុងស្នើរសុំដើម្បីបន្ត';
        }
        return '';
    }
}
