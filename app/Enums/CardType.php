<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CardType extends Enum
{
    const OCCUPATION = 'occupation';
    const MINOR_IMPROVEMENT = 'minor_improvement';
    const MAJOR_IMPROVEMENT = 'major_improvement';
}
