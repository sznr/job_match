<?php

namespace App\Consts;

class JobOfferConst
{
    // 並び替え
    const SORT_NEW_ARRIVALS = 1;
    const SORT_VIEW_RANK = 2;
    const SORT_LIST = [
        '新着' => self::SORT_NEW_ARRIVALS,
        '人気' => self::SORT_VIEW_RANK,
    ];
}
