<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CardStatus extends Enum
{
    const IN_HAND = 'in_hand';
    const PLAYED = 'played';
    const TURNED_OVER = 'turned_over';
    const DISCARDED = 'discarded';
}
