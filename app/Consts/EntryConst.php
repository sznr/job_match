<?php

namespace App\Consts;

class EntryConst
{
    // ステータス
    const STATUS_ENTRY = 0;
    const STATUS_APPROVAL = 1;
    const STATUS_REJECT = 2;
    const STATUS_LIST = [
        'エントリー中' => self::STATUS_ENTRY,
        '承認' => self::STATUS_APPROVAL,
        '却下' => self::STATUS_REJECT,
    ];
}