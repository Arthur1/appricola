<?php

use Illuminate\Database\Seeder;

class PoolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pools')->truncate();
        DB::table('pool_decks')->truncate();
        DB::table('pool_ban_cards')->truncate();
        DB::table('pools')->insert([
            [
                'id' => 1,
                'name' => '旧版基本',
                'owner_id' => null,
            ],
            [
                'id' => 2,
                'name' => '旧版全拡張',
                'owner_id' => null,
            ],
            [
                'id' => 3,
                'name' => '旧版全拡張(主要BAN)',
                'owner_id' => null,
            ],
            [
                'id' => 4,
                'name' => 'リバイズド基本',
                'owner_id' => null,
            ],
            [
                'id' => 5,
                'name' => 'リバイズド日本語版全拡張',
                'owner_id' => null,
            ],
            [
                'id' => 6,
                'name' => 'リバイズド全拡張',
                'owner_id' => null,
            ],
            [
                'id' => 7,
                'name' => '旧版+リバイズド全拡張',
                'owner_id' => null,
            ],
        ]);

        $pool_decks_list = [
            1 => ['E', 'I', 'K'],
            2 => ['E', 'I', 'K', 'alpha', 'beta', 'gamma', 'delta', 'epsilon', 'BI', 'CZ', 'FL', 'FR', 'G', 'NL', 'O', 'P', 'WA', 'Z'],
            3 => ['E', 'I', 'K', 'alpha', 'beta', 'gamma', 'delta', 'epsilon', 'BI', 'CZ', 'FL', 'FR', 'G', 'NL', 'O', 'P', 'WA', 'Z'],
            4 => ['RA', 'RB', '5A', '5B', '5C', '5D', 'LR', 'L5'],
            5 => ['RA', 'RB', '5A', '5B', '5C', '5D', 'LR', 'L5', 'A', 'B'],
            6 => ['RA', 'RB', '5A', '5B', '5C', '5D', 'LR', 'L5', 'A', 'B', 'L17', 'L18', 'WCB', 'WCG', 'WCP', 'WCR', 'WCW', 'WCY', 'WDB', 'WDG', 'WDP', 'WDR', 'WDW', 'WDY'],
            7 => ['E', 'I', 'K', 'alpha', 'beta', 'gamma', 'delta', 'epsilon', 'BI', 'CZ', 'FL', 'FR', 'G', 'NL', 'O', 'P', 'WA', 'Z', 'RA', 'RB', '5A', '5B', '5C', '5D', 'LR', 'L5', 'A', 'B', 'L17', 'L18', 'WCB', 'WCG', 'WCP', 'WCR', 'WCW', 'WCY', 'WDB', 'WDG', 'WDP', 'WDR', 'WDW', 'WDY'],
        ];
        $pool_decks_rows = [];
        foreach ($pool_decks_list as $pool_id => $deck_ids) {
            foreach ($deck_ids as $deck_id) {
                $pool_decks_rows[] = [
                    'pool_id' => $pool_id,
                    'deck_id' => $deck_id
                ];
            }
        }
        DB::table('pool_decks')->insert($pool_decks_rows);

        $pool_ban_cards_list = [
            3 => ['O03', 'Z329', 'NL084', 'Z320', 'BI17'],
            7 => ['306', '200', '175', '194', '267', 'FL046', '184', '196', 'NL112', '202', '176', '242', '272', 'WA050', '197', '211', '259', '167', '254', 'NL111', '279', '274', '174',
                '231', 'WA040', '218', '282', '168', '294', '288', '193', '250', '191', '208', '225', '179', 'Z335', 'NL119', 'NL118', '150', '224', '300', 'NL065', 'NL109', 'NL117', '235', '243',
                'FL035', 'NL063', '276', '182', '245', '240', '249', '261', '201', '151', '207', '195', '293', '256', 'Z332', '187', '241', 'O07', '236', '172', '173', '11', '60', '59', '67', '139', '23', 'Z321',
                'NL019', 'NL030', 'NL009', '34', 'NL001', '35', 'NL021', 'NL049', '30', '56', '77', '40', '39', 'NL016', '13', '63', '88', '111', 'WA024', 'NL043', '146', '69', 'NL033', '53',
                'NL012', 'NL060', '46', '18', 'WA002', '131', '52', 'NL051', '102', '99', '115', 'WA008', '113', 'WA004', '21', 'FL019', 'NL029', '84', 'WA009', 'NL005', '98', '116', 'NL022', '47',
                '45', '79', '142', '120', '37', '62', '49', '133', 'NL054', 'WA019', '112', '117', '78', '48', 'FL023', '338', '43']
        ];
        $pool_ban_cards_rows = [];
        foreach ($pool_ban_cards_list as $pool_id => $card_ids) {
            foreach ($card_ids as $card_id) {
                $pool_ban_cards_rows[] = [
                    'pool_id' => $pool_id,
                    'card_id' => $card_id
                ];
            }
        }
        DB::table('pool_ban_cards')->insert($pool_ban_cards_rows);
    }
}
