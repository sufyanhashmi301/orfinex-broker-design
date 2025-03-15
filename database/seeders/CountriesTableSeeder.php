<?php

namespace Database\Seeders;

use App\Models\Rate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->truncate();
        DB::table('rates')->truncate();

        $countries = [

            [
                'dial_code' => 716,
                'name' => 'Zimbabwe',
                'country_code' => '716',
                'ISO_3166_2' => 'ZW',
                'ISO_3166_3' => 'ZWE',
                'currency_code' => 'ZWL',
                'status' => 1,
                'symbol' => 'Z$'
            ],
            [
                'dial_code' => 894,
                'name' => 'Zambia',
                'country_code' => '894',
                'ISO_3166_2' => 'ZM',
                'ISO_3166_3' => 'ZMB',
                'currency_code' => 'ZMK',
                'status' => 1,
                'symbol' => 'ZMK'
            ],
            [
                'dial_code' => 887,
                'name' => 'Yemen',
                'country_code' => '887',
                'ISO_3166_2' => 'YE',
                'ISO_3166_3' => 'YEM',
                'currency_code' => 'YER',
                'status' => 1,
                'symbol' => '﷼'
            ],
            [
                'dial_code' => 732,
                'name' => 'Western Sahara',
                'country_code' => '732',
                'ISO_3166_2' => 'EH',
                'ISO_3166_3' => 'ESH',
                'currency_code' => 'MAD',
                'status' => 1,
                'symbol' => 'MAD'
            ],
            [
                'dial_code' => 876,
                'name' => 'Wallis and Futuna',
                'country_code' => '876',
                'ISO_3166_2' => 'WF',
                'ISO_3166_3' => 'WLF',
                'currency_code' => 'XPF',
                'status' => 1,
                'symbol' => 'XPF'
            ],
            [
                'dial_code' => 850,
                'name' => 'Virgin Islands U.S.',
                'country_code' => '850',
                'ISO_3166_2' => 'VI',
                'ISO_3166_3' => 'VIR',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 92,
                'name' => 'Virgin Islands British',
                'country_code' => '92',
                'ISO_3166_2' => 'VG',
                'ISO_3166_3' => 'VGB',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 704,
                'name' => 'Viet Nam',
                'country_code' => '704',
                'ISO_3166_2' => 'VN',
                'ISO_3166_3' => 'VNM',
                'currency_code' => 'VND',
                'status' => 1,
                'symbol' => '₫'
            ],
            [
                'dial_code' => 862,
                'name' => 'Venezuela Bolivarian Republic of',
                'country_code' => '862',
                'ISO_3166_2' => 'VE',
                'ISO_3166_3' => 'VEN',
                'currency_code' => 'VEF',
                'status' => 1,
                'symbol' => 'Bs'
            ],
            [
                'dial_code' => 548,
                'name' => 'Vanuatu',
                'country_code' => '548',
                'ISO_3166_2' => 'VU',
                'ISO_3166_3' => 'VUT',
                'currency_code' => 'VUV',
                'status' => 1,
                'symbol' => 'VUV'
            ],
            [
                'dial_code' => 860,
                'name' => 'Uzbekistan',
                'country_code' => '860',
                'ISO_3166_2' => 'UZ',
                'ISO_3166_3' => 'UZB',
                'currency_code' => 'UZS',
                'status' => 1,
                'symbol' => 'лв'
            ],
            [
                'dial_code' => 858,
                'name' => 'Uruguay',
                'country_code' => '858',
                'ISO_3166_2' => 'UY',
                'ISO_3166_3' => 'URY',
                'currency_code' => 'UYU',
                'status' => 1,
                'symbol' => '$U'
            ],
            [
                'dial_code' => 581,
                'name' => 'United States Minor Outlying Islands',
                'country_code' => '581',
                'ISO_3166_2' => 'UM',
                'ISO_3166_3' => 'UMI',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 840,
                'name' => 'United States',
                'country_code' => '840',
                'ISO_3166_2' => 'US',
                'ISO_3166_3' => 'USA',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 826,
                'name' => 'United Kingdom',
                'country_code' => '826',
                'ISO_3166_2' => 'GB',
                'ISO_3166_3' => 'GBR',
                'currency_code' => 'GBP',
                'status' => 1,
                'symbol' => '£'
            ],
            [
                'dial_code' => 784,
                'name' => 'United Arab Emirates',
                'country_code' => '784',
                'ISO_3166_2' => 'AE',
                'ISO_3166_3' => 'ARE',
                'currency_code' => 'AED',
                'status' => 1,
                'symbol' => 'AED'
            ],
            [
                'dial_code' => 804,
                'name' => 'Ukraine',
                'country_code' => '804',
                'ISO_3166_2' => 'UA',
                'ISO_3166_3' => 'UKR',
                'currency_code' => 'UAH',
                'status' => 1,
                'symbol' => '₴'
            ],
            [
                'dial_code' => 800,
                'name' => 'Uganda',
                'country_code' => '800',
                'ISO_3166_2' => 'UG',
                'ISO_3166_3' => 'UGA',
                'currency_code' => 'UGX',
                'status' => 1,
                'symbol' => 'UGX'
            ],
            [
                'dial_code' => 798,
                'name' => 'Tuvalu',
                'country_code' => '798',
                'ISO_3166_2' => 'TV',
                'ISO_3166_3' => 'TUV',
                'currency_code' => 'AUD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 796,
                'name' => 'Turks and Caicos Islands',
                'country_code' => '796',
                'ISO_3166_2' => 'TC',
                'ISO_3166_3' => 'TCA',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 795,
                'name' => 'Turkmenistan',
                'country_code' => '795',
                'ISO_3166_2' => 'TM',
                'ISO_3166_3' => 'TKM',
                'currency_code' => 'TMT',
                'status' => 1,
                'symbol' => 'TMT'
            ],
            [
                'dial_code' => 792,
                'name' => 'Turkey',
                'country_code' => '792',
                'ISO_3166_2' => 'TR',
                'ISO_3166_3' => 'TUR',
                'currency_code' => 'TRY',
                'status' => 1,
                'symbol' => '₺'
            ],
            [
                'dial_code' => 788,
                'name' => 'Tunisia',
                'country_code' => '788',
                'ISO_3166_2' => 'TN',
                'ISO_3166_3' => 'TUN',
                'currency_code' => 'TND',
                'status' => 1,
                'symbol' => 'TND'
            ],
            [
                'dial_code' => 780,
                'name' => 'Trinidad and Tobago',
                'country_code' => '780',
                'ISO_3166_2' => 'TT',
                'ISO_3166_3' => 'TTO',
                'currency_code' => 'TTD',
                'status' => 1,
                'symbol' => 'TT$'
            ],
            [
                'dial_code' => 776,
                'name' => 'Tonga',
                'country_code' => '776',
                'ISO_3166_2' => 'TO',
                'ISO_3166_3' => 'TON',
                'currency_code' => 'TOP',
                'status' => 1,
                'symbol' => 'TOP'
            ],
            [
                'dial_code' => 772,
                'name' => 'Tokelau',
                'country_code' => '772',
                'ISO_3166_2' => 'TK',
                'ISO_3166_3' => 'TKL',
                'currency_code' => 'NZD',
                'status' => 1,
                'symbol' => 'NZD'
            ],
            [
                'dial_code' => 768,
                'name' => 'Togo',
                'country_code' => '768',
                'ISO_3166_2' => 'TG',
                'ISO_3166_3' => 'TGO',
                'currency_code' => 'XOF',
                'status' => 1,
                'symbol' => 'XOF'
            ],
            [
                'dial_code' => 626,
                'name' => 'Timor-Leste',
                'country_code' => '626',
                'ISO_3166_2' => 'TL',
                'ISO_3166_3' => 'TLS',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 764,
                'name' => 'Thailand',
                'country_code' => '764',
                'ISO_3166_2' => 'TH',
                'ISO_3166_3' => 'THA',
                'currency_code' => 'THB',
                'status' => 1,
                'symbol' => '฿'
            ],
            [
                'dial_code' => 834,
                'name' => 'Tanzania United Republic of',
                'country_code' => '834',
                'ISO_3166_2' => 'TZ',
                'ISO_3166_3' => 'TZA',
                'currency_code' => 'TZS',
                'status' => 1,
                'symbol' => 'TZS'
            ],
            [
                'dial_code' => 762,
                'name' => 'Tajikistan',
                'country_code' => '762',
                'ISO_3166_2' => 'TJ',
                'ISO_3166_3' => 'TJK',
                'currency_code' => 'TJS',
                'status' => 1,
                'symbol' => 'TJS'
            ],
            [
                'dial_code' => 158,
                'name' => 'Taiwan Province of China',
                'country_code' => '158',
                'ISO_3166_2' => 'TW',
                'ISO_3166_3' => 'TWN',
                'currency_code' => 'TWD',
                'status' => 1,
                'symbol' => 'NT$'
            ],
            [
                'dial_code' => 760,
                'name' => 'Syrian Arab Republic',
                'country_code' => '760',
                'ISO_3166_2' => 'SY',
                'ISO_3166_3' => 'SYR',
                'currency_code' => 'SYP',
                'status' => 1,
                'symbol' => '£'
            ],
            [
                'dial_code' => 756,
                'name' => 'Switzerland',
                'country_code' => '756',
                'ISO_3166_2' => 'CH',
                'ISO_3166_3' => 'CHE',
                'currency_code' => 'CHF',
                'status' => 1,
                'symbol' => 'CHF'
            ],
            [
                'dial_code' => 752,
                'name' => 'Sweden',
                'country_code' => '752',
                'ISO_3166_2' => 'SE',
                'ISO_3166_3' => 'SWE',
                'currency_code' => 'SEK',
                'status' => 1,
                'symbol' => 'kr'
            ],
            [
                'dial_code' => 748,
                'name' => 'Swaziland',
                'country_code' => '748',
                'ISO_3166_2' => 'SZ',
                'ISO_3166_3' => 'SWZ',
                'currency_code' => 'SZL',
                'status' => 1,
                'symbol' => 'SZL'
            ],
            [
                'dial_code' => 744,
                'name' => 'Svalbard and Jan Mayen',
                'country_code' => '744',
                'ISO_3166_2' => 'SJ',
                'ISO_3166_3' => 'SJM',
                'currency_code' => 'NOK',
                'status' => 1,
                'symbol' => 'NOK'
            ],
            [
                'dial_code' => 740,
                'name' => 'Suriname',
                'country_code' => '740',
                'ISO_3166_2' => 'SR',
                'ISO_3166_3' => 'SUR',
                'currency_code' => 'SRD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 729,
                'name' => 'Sudan',
                'country_code' => '729',
                'ISO_3166_2' => 'SD',
                'ISO_3166_3' => 'SDN',
                'currency_code' => 'SDG',
                'status' => 1,
                'symbol' => 'SDG'
            ],
            [
                'dial_code' => 144,
                'name' => 'Sri Lanka',
                'country_code' => '144',
                'ISO_3166_2' => 'LK',
                'ISO_3166_3' => 'LKA',
                'currency_code' => 'LKR',
                'status' => 1,
                'symbol' => '₨'
            ],
            [
                'dial_code' => 724,
                'name' => 'Spain',
                'country_code' => '724',
                'ISO_3166_2' => 'ES',
                'ISO_3166_3' => 'ESP',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 728,
                'name' => 'South Sudan',
                'country_code' => '728',
                'ISO_3166_2' => 'SS',
                'ISO_3166_3' => 'SSD',
                'currency_code' => 'SSP',
                'status' => 1,
                'symbol' => 'SSP'
            ],
            [
                'dial_code' => 239,
                'name' => 'South Georgia and the South Sandwich Islands',
                'country_code' => '239',
                'ISO_3166_2' => 'GS',
                'ISO_3166_3' => 'SGS',
                'currency_code' => '',
                'status' => 1,
                'symbol' => '₾'
            ],
            [
                'dial_code' => 710,
                'name' => 'South Africa',
                'country_code' => '710',
                'ISO_3166_2' => 'ZA',
                'ISO_3166_3' => 'ZAF',
                'currency_code' => 'ZAR',
                'status' => 1,
                'symbol' => 'R'
            ],
            [
                'dial_code' => 706,
                'name' => 'Somalia',
                'country_code' => '706',
                'ISO_3166_2' => 'SO',
                'ISO_3166_3' => 'SOM',
                'currency_code' => 'SOS',
                'status' => 1,
                'symbol' => 'S'
            ],
            [
                'dial_code' => 90,
                'name' => 'Solomon Islands',
                'country_code' => '90',
                'ISO_3166_2' => 'SB',
                'ISO_3166_3' => 'SLB',
                'currency_code' => 'SBD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 705,
                'name' => 'Slovenia',
                'country_code' => '705',
                'ISO_3166_2' => 'SI',
                'ISO_3166_3' => 'SVN',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 703,
                'name' => 'Slovakia',
                'country_code' => '703',
                'ISO_3166_2' => 'SK',
                'ISO_3166_3' => 'SVK',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 534,
                'name' => 'Sint Maarten (Dutch part)',
                'country_code' => '534',
                'ISO_3166_2' => 'SX',
                'ISO_3166_3' => 'SXM',
                'currency_code' => 'ANG',
                'status' => 1,
                'symbol' => 'ANG'
            ],
            [
                'dial_code' => 702,
                'name' => 'Singapore',
                'country_code' => '702',
                'ISO_3166_2' => 'SG',
                'ISO_3166_3' => 'SGP',
                'currency_code' => 'SGD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 694,
                'name' => 'Sierra Leone',
                'country_code' => '694',
                'ISO_3166_2' => 'SL',
                'ISO_3166_3' => 'SLE',
                'currency_code' => 'SLL',
                'status' => 1,
                'symbol' => 'SLL'
            ],
            [
                'dial_code' => 690,
                'name' => 'Seychelles',
                'country_code' => '690',
                'ISO_3166_2' => 'SC',
                'ISO_3166_3' => 'SYC',
                'currency_code' => 'SCR',
                'status' => 1,
                'symbol' => '₨'
            ],
            [
                'dial_code' => 688,
                'name' => 'Serbia',
                'country_code' => '688',
                'ISO_3166_2' => 'RS',
                'ISO_3166_3' => 'SRB',
                'currency_code' => 'RSD',
                'status' => 1,
                'symbol' => 'Дин.'
            ],
            [
                'dial_code' => 686,
                'name' => 'Senegal',
                'country_code' => '686',
                'ISO_3166_2' => 'SN',
                'ISO_3166_3' => 'SEN',
                'currency_code' => 'XOF',
                'status' => 1,
                'symbol' => 'XOF'
            ],
            [
                'dial_code' => 682,
                'name' => 'Saudi Arabia',
                'country_code' => '682',
                'ISO_3166_2' => 'SA',
                'ISO_3166_3' => 'SAU',
                'currency_code' => 'SAR',
                'status' => 1,
                'symbol' => '﷼'
            ],
            [
                'dial_code' => 678,
                'name' => 'Sao Tome and Principe',
                'country_code' => '678',
                'ISO_3166_2' => 'ST',
                'ISO_3166_3' => 'STP',
                'currency_code' => 'STD',
                'status' => 1,
                'symbol' => 'STD'
            ],
            [
                'dial_code' => 674,
                'name' => 'San Marino',
                'country_code' => '674',
                'ISO_3166_2' => 'SM',
                'ISO_3166_3' => 'SMR',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 882,
                'name' => 'Samoa',
                'country_code' => '882',
                'ISO_3166_2' => 'WS',
                'ISO_3166_3' => 'WSM',
                'currency_code' => 'WST',
                'status' => 1,
                'symbol' => 'WST'
            ],
            [
                'dial_code' => 670,
                'name' => 'Saint Vincent and the Grenadines',
                'country_code' => '670',
                'ISO_3166_2' => 'VC',
                'ISO_3166_3' => 'VCT',
                'currency_code' => 'XCD',
                'status' => 1,
                'symbol' => 'XCD'
            ],
            [
                'dial_code' => 666,
                'name' => 'Saint Pierre and Miquelon',
                'country_code' => '666',
                'ISO_3166_2' => 'PM',
                'ISO_3166_3' => 'SPM',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 663,
                'name' => 'Saint Martin (French part)',
                'country_code' => '663',
                'ISO_3166_2' => 'MF',
                'ISO_3166_3' => 'MAF',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 662,
                'name' => 'Saint Lucia',
                'country_code' => '662',
                'ISO_3166_2' => 'LC',
                'ISO_3166_3' => 'LCA',
                'currency_code' => 'XCD',
                'status' => 1,
                'symbol' => 'XCD'
            ],
            [
                'dial_code' => 659,
                'name' => 'Saint Kitts and Nevis',
                'country_code' => '659',
                'ISO_3166_2' => 'KN',
                'ISO_3166_3' => 'KNA',
                'currency_code' => 'XCD',
                'status' => 1,
                'symbol' => 'XCD'
            ],
            [
                'dial_code' => 654,
                'name' => 'Saint Helena Ascension and Tristan da Cunha',
                'country_code' => '654',
                'ISO_3166_2' => 'SH',
                'ISO_3166_3' => 'SHN',
                'currency_code' => 'SHP',
                'status' => 1,
                'symbol' => '	£'
            ],
            [
                'dial_code' => 652,
                'name' => 'Saint Barthelemy',
                'country_code' => '652',
                'ISO_3166_2' => 'BL',
                'ISO_3166_3' => 'BLM',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 646,
                'name' => 'Rwanda',
                'country_code' => '646',
                'ISO_3166_2' => 'RW',
                'ISO_3166_3' => 'RWA',
                'currency_code' => 'RWF',
                'status' => 1,
                'symbol' => 'RWF'
            ],
            [
                'dial_code' => 643,
                'name' => 'Russian Federation',
                'country_code' => '643',
                'ISO_3166_2' => 'RU',
                'ISO_3166_3' => 'RUS',
                'currency_code' => 'RUB',
                'status' => 1,
                'symbol' => '₽'
            ],
            [
                'dial_code' => 642,
                'name' => 'Romania',
                'country_code' => '642',
                'ISO_3166_2' => 'RO',
                'ISO_3166_3' => 'ROU',
                'currency_code' => 'RON',
                'status' => 1,
                'symbol' => 'lei'
            ],
            [
                'dial_code' => 638,
                'name' => 'Reunion',
                'country_code' => '638',
                'ISO_3166_2' => 'RE',
                'ISO_3166_3' => 'REU',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 634,
                'name' => 'Qatar',
                'country_code' => '634',
                'ISO_3166_2' => 'QA',
                'ISO_3166_3' => 'QAT',
                'currency_code' => 'QAR',
                'status' => 1,
                'symbol' => '﷼'
            ],
            [
                'dial_code' => 630,
                'name' => 'Puerto Rico',
                'country_code' => '630',
                'ISO_3166_2' => 'PR',
                'ISO_3166_3' => 'PRI',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 620,
                'name' => 'Portugal',
                'country_code' => '620',
                'ISO_3166_2' => 'PT',
                'ISO_3166_3' => 'PRT',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 616,
                'name' => 'Poland',
                'country_code' => '616',
                'ISO_3166_2' => 'PL',
                'ISO_3166_3' => 'POL',
                'currency_code' => 'PLN',
                'status' => 1,
                'symbol' => 'zł'
            ],
            [
                'dial_code' => 612,
                'name' => 'Pitcairn',
                'country_code' => '612',
                'ISO_3166_2' => 'PN',
                'ISO_3166_3' => 'PCN',
                'currency_code' => 'NZD',
                'status' => 1,
                'symbol' => 'NZD'
            ],
            [
                'dial_code' => 608,
                'name' => 'Philippines',
                'country_code' => '608',
                'ISO_3166_2' => 'PH',
                'ISO_3166_3' => 'PHL',
                'currency_code' => 'PHP',
                'status' => 1,
                'symbol' => '₱'
            ],
            [
                'dial_code' => 604,
                'name' => 'Peru',
                'country_code' => '604',
                'ISO_3166_2' => 'PE',
                'ISO_3166_3' => 'PER',
                'currency_code' => 'PEN',
                'status' => 1,
                'symbol' => 'S/.'
            ],
            [
                'dial_code' => 600,
                'name' => 'Paraguay',
                'country_code' => '600',
                'ISO_3166_2' => 'PY',
                'ISO_3166_3' => 'PRY',
                'currency_code' => 'PYG',
                'status' => 1,
                'symbol' => 'Gs'
            ],
            [
                'dial_code' => 598,
                'name' => 'Papua New Guinea',
                'country_code' => '598',
                'ISO_3166_2' => 'PG',
                'ISO_3166_3' => 'PNG',
                'currency_code' => 'PGK',
                'status' => 1,
                'symbol' => 'PGK'
            ],
            [
                'dial_code' => 591,
                'name' => 'Panama',
                'country_code' => '591',
                'ISO_3166_2' => 'PA',
                'ISO_3166_3' => 'PAN',
                'currency_code' => 'PAB',
                'status' => 1,
                'symbol' => 'B/.'
            ],
            [
                'dial_code' => 275,
                'name' => 'Palestinian Territory Occupied',
                'country_code' => '275',
                'ISO_3166_2' => 'PS',
                'ISO_3166_3' => 'PSE',
                'currency_code' => '',
                'status' => 1,
                'symbol' => ''
            ],
            [
                'dial_code' => 585,
                'name' => 'Palau',
                'country_code' => '585',
                'ISO_3166_2' => 'PW',
                'ISO_3166_3' => 'PLW',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 586,
                'name' => 'Pakistan',
                'country_code' => '586',
                'ISO_3166_2' => 'PK',
                'ISO_3166_3' => 'PAK',
                'currency_code' => 'PKR',
                'status' => 1,
                'symbol' => '₨'
            ],
            [
                'dial_code' => 512,
                'name' => 'Oman',
                'country_code' => '512',
                'ISO_3166_2' => 'OM',
                'ISO_3166_3' => 'OMN',
                'currency_code' => 'OMR',
                'status' => 1,
                'symbol' => '﷼'
            ],
            [
                'dial_code' => 578,
                'name' => 'Norway',
                'country_code' => '578',
                'ISO_3166_2' => 'NO',
                'ISO_3166_3' => 'NOR',
                'currency_code' => 'NOK',
                'status' => 1,
                'symbol' => 'kr'
            ],
            [
                'dial_code' => 580,
                'name' => 'Northern Mariana Islands',
                'country_code' => '580',
                'ISO_3166_2' => 'MP',
                'ISO_3166_3' => 'MNP',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 574,
                'name' => 'Norfolk Island',
                'country_code' => '574',
                'ISO_3166_2' => 'NF',
                'ISO_3166_3' => 'NFK',
                'currency_code' => 'AUD',
                'status' => 1,
                'symbol' => 'AUD'
            ],
            [
                'dial_code' => 570,
                'name' => 'Niue',
                'country_code' => '570',
                'ISO_3166_2' => 'NU',
                'ISO_3166_3' => 'NIU',
                'currency_code' => 'NZD',
                'status' => 1,
                'symbol' => 'NZD'
            ],
            [
                'dial_code' => 566,
                'name' => 'Nigeria',
                'country_code' => '566',
                'ISO_3166_2' => 'NG',
                'ISO_3166_3' => 'NGA',
                'currency_code' => 'NGN',
                'status' => 1,
                'symbol' => '₦'
            ],
            [
                'dial_code' => 562,
                'name' => 'Niger',
                'country_code' => '562',
                'ISO_3166_2' => 'NE',
                'ISO_3166_3' => 'NER',
                'currency_code' => 'XOF',
                'status' => 1,
                'symbol' => 'XOF'
            ],
            [
                'dial_code' => 558,
                'name' => 'Nicaragua',
                'country_code' => '558',
                'ISO_3166_2' => 'NI',
                'ISO_3166_3' => 'NIC',
                'currency_code' => 'NIO',
                'status' => 1,
                'symbol' => 'C$'
            ],
            [
                'dial_code' => 554,
                'name' => 'New Zealand',
                'country_code' => '554',
                'ISO_3166_2' => 'NZ',
                'ISO_3166_3' => 'NZL',
                'currency_code' => 'NZD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 540,
                'name' => 'New Caledonia',
                'country_code' => '540',
                'ISO_3166_2' => 'NC',
                'ISO_3166_3' => 'NCL',
                'currency_code' => 'XPF',
                'status' => 1,
                'symbol' => 'XPF'
            ],
            [
                'dial_code' => 528,
                'name' => 'Netherlands',
                'country_code' => '528',
                'ISO_3166_2' => 'NL',
                'ISO_3166_3' => 'NLD',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => 'ƒ'
            ],
            [
                'dial_code' => 524,
                'name' => 'Nepal',
                'country_code' => '524',
                'ISO_3166_2' => 'NP',
                'ISO_3166_3' => 'NPL',
                'currency_code' => 'NPR',
                'status' => 1,
                'symbol' => '₨'
            ],
            [
                'dial_code' => 520,
                'name' => 'Nauru',
                'country_code' => '520',
                'ISO_3166_2' => 'NR',
                'ISO_3166_3' => 'NRU',
                'currency_code' => 'AUD',
                'status' => 1,
                'symbol' => 'AUD'
            ],
            [
                'dial_code' => 516,
                'name' => 'Namibia',
                'country_code' => '516',
                'ISO_3166_2' => 'NA',
                'ISO_3166_3' => 'NAM',
                'currency_code' => 'NAD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 104,
                'name' => 'Myanmar',
                'country_code' => '104',
                'ISO_3166_2' => 'MM',
                'ISO_3166_3' => 'MMR',
                'currency_code' => 'MMK',
                'status' => 1,
                'symbol' => 'MMK'
            ],
            [
                'dial_code' => 508,
                'name' => 'Mozambique',
                'country_code' => '508',
                'ISO_3166_2' => 'MZ',
                'ISO_3166_3' => 'MOZ',
                'currency_code' => 'MZN',
                'status' => 1,
                'symbol' => 'MT'
            ],
            [
                'dial_code' => 504,
                'name' => 'Morocco',
                'country_code' => '504',
                'ISO_3166_2' => 'MA',
                'ISO_3166_3' => 'MAR',
                'currency_code' => 'MAD',
                'status' => 1,
                'symbol' => 'MAD'
            ],
            [
                'dial_code' => 500,
                'name' => 'Montserrat',
                'country_code' => '500',
                'ISO_3166_2' => 'MS',
                'ISO_3166_3' => 'MSR',
                'currency_code' => 'XCD',
                'status' => 1,
                'symbol' => 'XCD'
            ],
            [
                'dial_code' => 499,
                'name' => 'Montenegro',
                'country_code' => '499',
                'ISO_3166_2' => 'ME',
                'ISO_3166_3' => 'MNE',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 496,
                'name' => 'Mongolia',
                'country_code' => '496',
                'ISO_3166_2' => 'MN',
                'ISO_3166_3' => 'MNG',
                'currency_code' => 'MNT',
                'status' => 1,
                'symbol' => '₮'
            ],
            [
                'dial_code' => 492,
                'name' => 'Monaco',
                'country_code' => '492',
                'ISO_3166_2' => 'MC',
                'ISO_3166_3' => 'MCO',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 498,
                'name' => 'Moldova Republic of',
                'country_code' => '498',
                'ISO_3166_2' => 'MD',
                'ISO_3166_3' => 'MDA',
                'currency_code' => 'MDL',
                'status' => 1,
                'symbol' => 'MDL'
            ],
            [
                'dial_code' => 583,
                'name' => 'Micronesia Federated States of',
                'country_code' => '583',
                'ISO_3166_2' => 'FM',
                'ISO_3166_3' => 'FSM',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 484,
                'name' => 'Mexico',
                'country_code' => '484',
                'ISO_3166_2' => 'MX',
                'ISO_3166_3' => 'MEX',
                'currency_code' => 'MXN',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 175,
                'name' => 'Mayotte',
                'country_code' => '175',
                'ISO_3166_2' => 'YT',
                'ISO_3166_3' => 'MYT',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 480,
                'name' => 'Mauritius',
                'country_code' => '480',
                'ISO_3166_2' => 'MU',
                'ISO_3166_3' => 'MUS',
                'currency_code' => 'MUR',
                'status' => 1,
                'symbol' => '₨'
            ],
            [
                'dial_code' => 478,
                'name' => 'Mauritania',
                'country_code' => '478',
                'ISO_3166_2' => 'MR',
                'ISO_3166_3' => 'MRT',
                'currency_code' => 'MRO',
                'status' => 1,
                'symbol' => 'MRO'
            ],
            [
                'dial_code' => 474,
                'name' => 'Martinique',
                'country_code' => '474',
                'ISO_3166_2' => 'MQ',
                'ISO_3166_3' => 'MTQ',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 584,
                'name' => 'Marshall Islands',
                'country_code' => '584',
                'ISO_3166_2' => 'MH',
                'ISO_3166_3' => 'MHL',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 470,
                'name' => 'Malta',
                'country_code' => '470',
                'ISO_3166_2' => 'MT',
                'ISO_3166_3' => 'MLT',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 466,
                'name' => 'Mali',
                'country_code' => '466',
                'ISO_3166_2' => 'ML',
                'ISO_3166_3' => 'MLI',
                'currency_code' => 'XOF',
                'status' => 1,
                'symbol' => 'XOF'
            ],
            [
                'dial_code' => 462,
                'name' => 'Maldives',
                'country_code' => '462',
                'ISO_3166_2' => 'MV',
                'ISO_3166_3' => 'MDV',
                'currency_code' => 'MVR',
                'status' => 1,
                'symbol' => 'MVR'
            ],
            [
                'dial_code' => 458,
                'name' => 'Malaysia',
                'country_code' => '458',
                'ISO_3166_2' => 'MY',
                'ISO_3166_3' => 'MYS',
                'currency_code' => 'MYR',
                'status' => 1,
                'symbol' => 'RM'
            ],
            [
                'dial_code' => 454,
                'name' => 'Malawi',
                'country_code' => '454',
                'ISO_3166_2' => 'MW',
                'ISO_3166_3' => 'MWI',
                'currency_code' => 'MWK',
                'status' => 1,
                'symbol' => 'MWK'
            ],
            [
                'dial_code' => 450,
                'name' => 'Madagascar',
                'country_code' => '450',
                'ISO_3166_2' => 'MG',
                'ISO_3166_3' => 'MDG',
                'currency_code' => 'MGA',
                'status' => 1,
                'symbol' => 'MGA'
            ],
            [
                'dial_code' => 807,
                'name' => 'Macedonia the former Yugoslav Republic of',
                'country_code' => '807',
                'ISO_3166_2' => 'MK',
                'ISO_3166_3' => 'MKD',
                'currency_code' => 'MKD',
                'status' => 1,
                'symbol' => 'ден'
            ],
            [
                'dial_code' => 446,
                'name' => 'Macao',
                'country_code' => '446',
                'ISO_3166_2' => 'MO',
                'ISO_3166_3' => 'MAC',
                'currency_code' => 'MOP',
                'status' => 1,
                'symbol' => 'MOP'
            ],
            [
                'dial_code' => 442,
                'name' => 'Luxembourg',
                'country_code' => '442',
                'ISO_3166_2' => 'LU',
                'ISO_3166_3' => 'LUX',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 440,
                'name' => 'Lithuania',
                'country_code' => '440',
                'ISO_3166_2' => 'LT',
                'ISO_3166_3' => 'LTU',
                'currency_code' => 'LTL',
                'status' => 1,
                'symbol' => 'Lt'
            ],
            [
                'dial_code' => 438,
                'name' => 'Liechtenstein',
                'country_code' => '438',
                'ISO_3166_2' => 'LI',
                'ISO_3166_3' => 'LIE',
                'currency_code' => 'CHF',
                'status' => 1,
                'symbol' => 'CHF'
            ],
            [
                'dial_code' => 434,
                'name' => 'Libya',
                'country_code' => '434',
                'ISO_3166_2' => 'LY',
                'ISO_3166_3' => 'LBY',
                'currency_code' => 'LYD',
                'status' => 1,
                'symbol' => 'LYD'
            ],
            [
                'dial_code' => 430,
                'name' => 'Liberia',
                'country_code' => '430',
                'ISO_3166_2' => 'LR',
                'ISO_3166_3' => 'LBR',
                'currency_code' => 'LRD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 426,
                'name' => 'Lesotho',
                'country_code' => '426',
                'ISO_3166_2' => 'LS',
                'ISO_3166_3' => 'LSO',
                'currency_code' => 'LSL',
                'status' => 1,
                'symbol' => 'LSL'
            ],
            [
                'dial_code' => 422,
                'name' => 'Lebanon',
                'country_code' => '422',
                'ISO_3166_2' => 'LB',
                'ISO_3166_3' => 'LBN',
                'currency_code' => 'LBP',
                'status' => 1,
                'symbol' => '£'
            ],
            [
                'dial_code' => 428,
                'name' => 'Latvia',
                'country_code' => '428',
                'ISO_3166_2' => 'LV',
                'ISO_3166_3' => 'LVA',
                'currency_code' => 'LVL',
                'status' => 1,
                'symbol' => 'Ls'
            ],
            [
                'dial_code' => 418,
                'name' => 'Lao Peoples Democratic Republic',
                'country_code' => '418',
                'ISO_3166_2' => 'LA',
                'ISO_3166_3' => 'LAO',
                'currency_code' => 'LAK',
                'status' => 1,
                'symbol' => 'LAK'
            ],
            [
                'dial_code' => 417,
                'name' => 'Kyrgyzstan',
                'country_code' => '417',
                'ISO_3166_2' => 'KG',
                'ISO_3166_3' => 'KGZ',
                'currency_code' => 'KGS',
                'status' => 1,
                'symbol' => 'лв'
            ],
            [
                'dial_code' => 414,
                'name' => 'Kuwait',
                'country_code' => '414',
                'ISO_3166_2' => 'KW',
                'ISO_3166_3' => 'KWT',
                'currency_code' => 'KWD',
                'status' => 1,
                'symbol' => 'KWD'
            ],
            [
                'dial_code' => 410,
                'name' => 'Republic of',
                'country_code' => '410',
                'ISO_3166_2' => 'KR',
                'ISO_3166_3' => 'KOR',
                'currency_code' => 'KRW',
                'status' => 1,
                'symbol' => '₩'
            ],
            [
                'dial_code' => 408,
                'name' => 'Democratic Peoples Republic of',
                'country_code' => '408',
                'ISO_3166_2' => 'KP',
                'ISO_3166_3' => 'PRK',
                'currency_code' => 'KPW',
                'status' => 1,
                'symbol' => 'KPW'
            ],
            [
                'dial_code' => 296,
                'name' => 'Kiribati',
                'country_code' => '296',
                'ISO_3166_2' => 'KI',
                'ISO_3166_3' => 'KIR',
                'currency_code' => 'AUD',
                'status' => 1,
                'symbol' => 'AUD'
            ],
            [
                'dial_code' => 404,
                'name' => 'Kenya',
                'country_code' => '404',
                'ISO_3166_2' => 'KE',
                'ISO_3166_3' => 'KEN',
                'currency_code' => 'KES',
                'status' => 1,
                'symbol' => 'KES'
            ],
            [
                'dial_code' => 398,
                'name' => 'Kazakhstan',
                'country_code' => '398',
                'ISO_3166_2' => 'KZ',
                'ISO_3166_3' => 'KAZ',
                'currency_code' => 'KZT',
                'status' => 1,
                'symbol' => 'лв'
            ],
            [
                'dial_code' => 400,
                'name' => 'Jordan',
                'country_code' => '400',
                'ISO_3166_2' => 'JO',
                'ISO_3166_3' => 'JOR',
                'currency_code' => 'JOD',
                'status' => 1,
                'symbol' => 'JOD'
            ],
            [
                'dial_code' => 832,
                'name' => 'Jersey',
                'country_code' => '832',
                'ISO_3166_2' => 'JE',
                'ISO_3166_3' => 'JEY',
                'currency_code' => 'GBP',
                'status' => 1,
                'symbol' => '£'
            ],
            [
                'dial_code' => 392,
                'name' => 'Japan',
                'country_code' => '392',
                'ISO_3166_2' => 'JP',
                'ISO_3166_3' => 'JPN',
                'currency_code' => 'JPY',
                'status' => 1,
                'symbol' => '¥'
            ],
            [
                'dial_code' => 388,
                'name' => 'Jamaica',
                'country_code' => '388',
                'ISO_3166_2' => 'JM',
                'ISO_3166_3' => 'JAM',
                'currency_code' => 'JMD',
                'status' => 1,
                'symbol' => 'J$'
            ],
            [
                'dial_code' => 380,
                'name' => 'Italy',
                'country_code' => '380',
                'ISO_3166_2' => 'IT',
                'ISO_3166_3' => 'ITA',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 376,
                'name' => 'Israel',
                'country_code' => '376',
                'ISO_3166_2' => 'IL',
                'ISO_3166_3' => 'ISR',
                'currency_code' => 'ILS',
                'status' => 1,
                'symbol' => '₪'
            ],
            [
                'dial_code' => 833,
                'name' => 'Isle of Man',
                'country_code' => '833',
                'ISO_3166_2' => 'IM',
                'ISO_3166_3' => 'IMN',
                'currency_code' => 'GBP',
                'status' => 1,
                'symbol' => '£'
            ],
            [
                'dial_code' => 372,
                'name' => 'Ireland',
                'country_code' => '372',
                'ISO_3166_2' => 'IE',
                'ISO_3166_3' => 'IRL',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 368,
                'name' => 'Iraq',
                'country_code' => '368',
                'ISO_3166_2' => 'IQ',
                'ISO_3166_3' => 'IRQ',
                'currency_code' => 'IQD',
                'status' => 1,
                'symbol' => 'IQD'
            ],
            [
                'dial_code' => 364,
                'name' => 'Iran Islamic Republic of',
                'country_code' => ' 364',
                'ISO_3166_2' => 'IR',
                'ISO_3166_3' => 'IRN',
                'currency_code' => 'IRR',
                'status' => 1,
                'symbol' => '﷼'
            ],
            [
                'dial_code' => 360,
                'name' => 'Indonesia',
                'country_code' => '360',
                'ISO_3166_2' => 'ID',
                'ISO_3166_3' => 'IDN',
                'currency_code' => 'IDR',
                'status' => 1,
                'symbol' => 'Rp'
            ],
            [
                'dial_code' => 356,
                'name' => 'India',
                'country_code' => '356',
                'ISO_3166_2' => 'IN',
                'ISO_3166_3' => 'IND',
                'currency_code' => 'INR',
                'status' => 1,
                'symbol' => '₹'
            ],
            [
                'dial_code' => 352,
                'name' => 'Iceland',
                'country_code' => '352',
                'ISO_3166_2' => 'IS',
                'ISO_3166_3' => 'ISL',
                'currency_code' => 'ISK',
                'status' => 1,
                'symbol' => 'kr'
            ],
            [
                'dial_code' => 348,
                'name' => 'Hungary',
                'country_code' => '348',
                'ISO_3166_2' => 'HU',
                'ISO_3166_3' => 'HUN',
                'currency_code' => 'HUF',
                'status' => 1,
                'symbol' => 'Ft'
            ],
            [
                'dial_code' => 344,
                'name' => 'Hong Kong',
                'country_code' => '344',
                'ISO_3166_2' => 'HK',
                'ISO_3166_3' => 'HKG',
                'currency_code' => 'HKD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 340,
                'name' => 'Honduras',
                'country_code' => '340',
                'ISO_3166_2' => 'HN',
                'ISO_3166_3' => 'HND',
                'currency_code' => 'HNL',
                'status' => 1,
                'symbol' => 'L'
            ],
            [
                'dial_code' => 336,
                'name' => 'Holy See (Vatican City State)',
                'country_code' => '336',
                'ISO_3166_2' => 'VA',
                'ISO_3166_3' => 'VAT',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 334,
                'name' => 'Heard Island and McDonald Islands',
                'country_code' => '334',
                'ISO_3166_2' => 'HM',
                'ISO_3166_3' => 'HMD',
                'currency_code' => 'AUD',
                'status' => 1,
                'symbol' => 'AUD'
            ],
            [
                'dial_code' => 332,
                'name' => 'Haiti',
                'country_code' => '332',
                'ISO_3166_2' => 'HT',
                'ISO_3166_3' => 'HTI',
                'currency_code' => 'HTG',
                'status' => 1,
                'symbol' => 'HTG'
            ],
            [
                'dial_code' => 328,
                'name' => 'Guyana',
                'country_code' => '328',
                'ISO_3166_2' => 'GY',
                'ISO_3166_3' => 'GUY',
                'currency_code' => 'GYD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 624,
                'name' => 'Guinea-Bissau',
                'country_code' => '624',
                'ISO_3166_2' => 'GW',
                'ISO_3166_3' => 'GNB',
                'currency_code' => 'XOF',
                'status' => 1,
                'symbol' => 'XOF'
            ],
            [
                'dial_code' => 324,
                'name' => 'Guinea',
                'country_code' => '324',
                'ISO_3166_2' => 'GN',
                'ISO_3166_3' => 'GIN',
                'currency_code' => 'GNF',
                'status' => 1,
                'symbol' => 'GNF'
            ],
            [
                'dial_code' => 831,
                'name' => 'Guernsey',
                'country_code' => '831',
                'ISO_3166_2' => 'GG',
                'ISO_3166_3' => 'GGY',
                'currency_code' => 'GBP',
                'status' => 1,
                'symbol' => '£'
            ],
            [
                'dial_code' => 320,
                'name' => 'Guatemala',
                'country_code' => '320',
                'ISO_3166_2' => 'GT',
                'ISO_3166_3' => 'GTM',
                'currency_code' => 'GTQ',
                'status' => 1,
                'symbol' => 'Q'
            ],
            [
                'dial_code' => 316,
                'name' => 'Guam',
                'country_code' => '316',
                'ISO_3166_2' => 'GU',
                'ISO_3166_3' => 'GUM',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 312,
                'name' => 'Guadeloupe',
                'country_code' => '312',
                'ISO_3166_2' => 'GP',
                'ISO_3166_3' => 'GLP',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 308,
                'name' => 'Grenada',
                'country_code' => '308',
                'ISO_3166_2' => 'GD',
                'ISO_3166_3' => 'GRD',
                'currency_code' => 'XCD',
                'status' => 1,
                'symbol' => 'XCD'
            ],
            [
                'dial_code' => 304,
                'name' => 'Greenland',
                'country_code' => '304',
                'ISO_3166_2' => 'GL',
                'ISO_3166_3' => 'GRL',
                'currency_code' => 'DKK',
                'status' => 1,
                'symbol' => 'DKK'
            ],
            [
                'dial_code' => 300,
                'name' => 'Greece',
                'country_code' => '300',
                'ISO_3166_2' => 'GR',
                'ISO_3166_3' => 'GRC',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 292,
                'name' => 'Gibraltar',
                'country_code' => '292',
                'ISO_3166_2' => 'GI',
                'ISO_3166_3' => 'GIB',
                'currency_code' => 'GIP',
                'status' => 1,
                'symbol' => '£'
            ],
            [
                'dial_code' => 288,
                'name' => 'Ghana',
                'country_code' => '288',
                'ISO_3166_2' => 'GH',
                'ISO_3166_3' => 'GHA',
                'currency_code' => 'GHS',
                'status' => 1,
                'symbol' => '¢'
            ],
            [
                'dial_code' => 276,
                'name' => 'Germany',
                'country_code' => '276',
                'ISO_3166_2' => 'DE',
                'ISO_3166_3' => 'DEU',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 268,
                'name' => 'Georgia',
                'country_code' => '268',
                'ISO_3166_2' => 'GE',
                'ISO_3166_3' => 'GEO',
                'currency_code' => 'GEL',
                'status' => 1,
                'symbol' => '₾'
            ],
            [
                'dial_code' => 270,
                'name' => 'Gambia',
                'country_code' => '270',
                'ISO_3166_2' => 'GM',
                'ISO_3166_3' => 'GMB',
                'currency_code' => 'GMD',
                'status' => 1,
                'symbol' => 'GMD'
            ],
            [
                'dial_code' => 266,
                'name' => 'Gabon',
                'country_code' => '266',
                'ISO_3166_2' => 'GA',
                'ISO_3166_3' => 'GAB',
                'currency_code' => 'XAF',
                'status' => 1,
                'symbol' => 'XAF'
            ],
            [
                'dial_code' => 260,
                'name' => 'French Southern Territories',
                'country_code' => '260',
                'ISO_3166_2' => 'TF',
                'ISO_3166_3' => 'ATF',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 258,
                'name' => 'French Polynesia',
                'country_code' => '258',
                'ISO_3166_2' => 'PF',
                'ISO_3166_3' => 'PYF',
                'currency_code' => 'XPF',
                'status' => 1,
                'symbol' => 'XPF'
            ],
            [
                'dial_code' => 254,
                'name' => 'French Guiana',
                'country_code' => '254',
                'ISO_3166_2' => 'GF',
                'ISO_3166_3' => 'GUF',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 250,
                'name' => 'France',
                'country_code' => '250',
                'ISO_3166_2' => 'FR',
                'ISO_3166_3' => 'FRA',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 246,
                'name' => 'Finland',
                'country_code' => '246',
                'ISO_3166_2' => 'FI',
                'ISO_3166_3' => 'FIN',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 242,
                'name' => 'Fiji',
                'country_code' => '242',
                'ISO_3166_2' => 'FJ',
                'ISO_3166_3' => 'FJI',
                'currency_code' => 'FJD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 234,
                'name' => 'Faroe Islands',
                'country_code' => '234',
                'ISO_3166_2' => 'FO',
                'ISO_3166_3' => 'FRO',
                'currency_code' => 'DKK',
                'status' => 1,
                'symbol' => 'DKK'
            ],
            [
                'dial_code' => 238,
                'name' => 'Falkland Islands (Malvinas)',
                'country_code' => '238',
                'ISO_3166_2' => 'FK',
                'ISO_3166_3' => 'FLK',
                'currency_code' => 'FKP',
                'status' => 1,
                'symbol' => '£'
            ],
            [
                'dial_code' => 231,
                'name' => 'Ethiopia',
                'country_code' => '231',
                'ISO_3166_2' => 'ET',
                'ISO_3166_3' => 'ETH',
                'currency_code' => 'ETB',
                'status' => 1,
                'symbol' => 'ETB'
            ],
            [
                'dial_code' => 233,
                'name' => 'Estonia',
                'country_code' => '233',
                'ISO_3166_2' => 'EE',
                'ISO_3166_3' => 'EST',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => 'kr'
            ],
            [
                'dial_code' => 232,
                'name' => 'Eritrea',
                'country_code' => '232',
                'ISO_3166_2' => 'ER',
                'ISO_3166_3' => 'ERI',
                'currency_code' => 'ERN',
                'status' => 1,
                'symbol' => 'ERN'
            ],
            [
                'dial_code' => 226,
                'name' => 'Equatorial Guinea',
                'country_code' => '226',
                'ISO_3166_2' => 'GQ',
                'ISO_3166_3' => 'GNQ',
                'currency_code' => 'XAF',
                'status' => 1,
                'symbol' => 'XAF'
            ],
            [
                'dial_code' => 222,
                'name' => 'El Salvador',
                'country_code' => '222',
                'ISO_3166_2' => 'SV',
                'ISO_3166_3' => 'SLV',
                'currency_code' => 'SVC',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 818,
                'name' => 'Egypt',
                'country_code' => '818',
                'ISO_3166_2' => 'EG',
                'ISO_3166_3' => 'EGY',
                'currency_code' => 'EGP',
                'status' => 1,
                'symbol' => '£'
            ],
            [
                'dial_code' => 218,
                'name' => 'Ecuador',
                'country_code' => '218',
                'ISO_3166_2' => 'EC',
                'ISO_3166_3' => 'ECU',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 214,
                'name' => 'Dominican Republic',
                'country_code' => '214',
                'ISO_3166_2' => 'DO',
                'ISO_3166_3' => 'DOM',
                'currency_code' => 'DOP',
                'status' => 1,
                'symbol' => 'RD$'
            ],
            [
                'dial_code' => 212,
                'name' => 'Dominica',
                'country_code' => '212',
                'ISO_3166_2' => 'DM',
                'ISO_3166_3' => 'DMA',
                'currency_code' => 'XCD',
                'status' => 1,
                'symbol' => 'XCD'
            ],
            [
                'dial_code' => 262,
                'name' => 'Djibouti',
                'country_code' => '262',
                'ISO_3166_2' => 'DJ',
                'ISO_3166_3' => 'DJI',
                'currency_code' => 'DJF',
                'status' => 1,
                'symbol' => 'DJF'
            ],
            [
                'dial_code' => 208,
                'name' => 'Denmark',
                'country_code' => '208',
                'ISO_3166_2' => 'DK',
                'ISO_3166_3' => 'DNK',
                'currency_code' => 'DKK',
                'status' => 1,
                'symbol' => 'kr'
            ],
            [
                'dial_code' => 203,
                'name' => 'Czech Republic',
                'country_code' => '203',
                'ISO_3166_2' => 'CZ',
                'ISO_3166_3' => 'CZE',
                'currency_code' => 'CZK',
                'status' => 1,
                'symbol' => 'Kč'
            ],
            [
                'dial_code' => 196,
                'name' => 'Cyprus',
                'country_code' => '196',
                'ISO_3166_2' => 'CY',
                'ISO_3166_3' => 'CYP',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 531,
                'name' => 'Curacao',
                'country_code' => '531',
                'ISO_3166_2' => 'CW',
                'ISO_3166_3' => 'CUW',
                'currency_code' => 'ANG',
                'status' => 1,
                'symbol' => 'ANG'
            ],
            [
                'dial_code' => 192,
                'name' => 'Cuba',
                'country_code' => '192',
                'ISO_3166_2' => 'CU',
                'ISO_3166_3' => 'CUB',
                'currency_code' => 'CUP',
                'status' => 1,
                'symbol' => '₱'
            ],
            [
                'dial_code' => 191,
                'name' => 'Croatia',
                'country_code' => '191',
                'ISO_3166_2' => 'HR',
                'ISO_3166_3' => 'HRV',
                'currency_code' => 'HRK',
                'status' => 1,
                'symbol' => 'kn'
            ],
            [
                'dial_code' => 384,
                'name' => "Cote d'Ivoire",
                'country_code' => '384',
                'ISO_3166_2' => 'CI',
                'ISO_3166_3' => 'CIV',
                'currency_code' => 'XOF',
                'status' => 1,
                'symbol' => 'XOF'
            ],
            [
                'dial_code' => 188,
                'name' => 'Costa Rica',
                'country_code' => '188',
                'ISO_3166_2' => 'CR',
                'ISO_3166_3' => 'CRI',
                'currency_code' => 'CRC',
                'status' => 1,
                'symbol' => '₡'
            ],
            [
                'dial_code' => 184,
                'name' => 'Cook Islands',
                'country_code' => '184',
                'ISO_3166_2' => 'CK',
                'ISO_3166_3' => 'COK',
                'currency_code' => 'NZD',
                'status' => 1,
                'symbol' => 'NZD'
            ],
            [
                'dial_code' => 180,
                'name' => 'Congo the Democratic Republic of the',
                'country_code' => '180',
                'ISO_3166_2' => 'CD',
                'ISO_3166_3' => 'COD',
                'currency_code' => 'CDF',
                'status' => 1,
                'symbol' => 'CDF'
            ],
            [
                'dial_code' => 178,
                'name' => 'Congo',
                'country_code' => '178',
                'ISO_3166_2' => 'CG',
                'ISO_3166_3' => 'COG',
                'currency_code' => 'XAF',
                'status' => 1,
                'symbol' => 'XAF'
            ],
            [
                'dial_code' => 174,
                'name' => 'Comoros',
                'country_code' => '174',
                'ISO_3166_2' => 'KM',
                'ISO_3166_3' => 'COM',
                'currency_code' => 'KMF',
                'status' => 1,
                'symbol' => 'KMF'
            ],
            [
                'dial_code' => 170,
                'name' => 'Colombia',
                'country_code' => '170',
                'ISO_3166_2' => 'CO',
                'ISO_3166_3' => 'COL',
                'currency_code' => 'COP',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 166,
                'name' => 'Cocos(Keeling) Islands',
                'country_code' => '166',
                'ISO_3166_2' => 'CC',
                'ISO_3166_3' => 'CCK',
                'currency_code' => 'AUD',
                'status' => 1,
                'symbol' => 'AUD'
            ],
            [
                'dial_code' => 162,
                'name' => 'Christmas Island',
                'country_code' => '162',
                'ISO_3166_2' => 'CX',
                'ISO_3166_3' => 'CXR',
                'currency_code' => 'AUD',
                'status' => 1,
                'symbol' => 'AUD'
            ],
            [
                'dial_code' => 156,
                'name' => 'China',
                'country_code' => '156',
                'ISO_3166_2' => 'CN',
                'ISO_3166_3' => 'CHN',
                'currency_code' => 'CNY',
                'status' => 1,
                'symbol' => '¥'
            ],
            [
                'dial_code' => 152,
                'name' => 'Chile',
                'country_code' => '152',
                'ISO_3166_2' => 'CL',
                'ISO_3166_3' => 'CHL',
                'currency_code' => 'CLP',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 148,
                'name' => 'Chad',
                'country_code' => '148',
                'ISO_3166_2' => 'TD',
                'ISO_3166_3' => 'TCD',
                'currency_code' => 'XAF',
                'status' => 1,
                'symbol' => 'XAF'
            ],
            [
                'dial_code' => 140,
                'name' => 'Central African Republic',
                'country_code' => '140',
                'ISO_3166_2' => 'CF',
                'ISO_3166_3' => 'CAF',
                'currency_code' => 'XAF',
                'status' => 1,
                'symbol' => 'XAF'
            ],
            [
                'dial_code' => 136,
                'name' => 'Cayman Islands',
                'country_code' => '136',
                'ISO_3166_2' => 'KY',
                'ISO_3166_3' => 'CYM',
                'currency_code' => 'KYD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 132,
                'name' => 'Cape Verde',
                'country_code' => '132',
                'ISO_3166_2' => 'CV',
                'ISO_3166_3' => 'CPV',
                'currency_code' => 'CVE',
                'status' => 1,
                'symbol' => 'CVE'
            ],
            [
                'dial_code' => 124,
                'name' => 'Canada',
                'country_code' => '124',
                'ISO_3166_2' => 'CA',
                'ISO_3166_3' => 'CAN',
                'currency_code' => 'CAD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 120,
                'name' => 'Cameroon',
                'country_code' => '120',
                'ISO_3166_2' => 'CM',
                'ISO_3166_3' => 'CMR',
                'currency_code' => 'XAF',
                'status' => 1,
                'symbol' => 'XAF'
            ],
            [
                'dial_code' => 116,
                'name' => 'Cambodia',
                'country_code' => '116',
                'ISO_3166_2' => 'KH',
                'ISO_3166_3' => 'KHM',
                'currency_code' => 'KHR',
                'status' => 1,
                'symbol' => '៛'
            ],
            [
                'dial_code' => 108,
                'name' => 'Burundi',
                'country_code' => '108',
                'ISO_3166_2' => 'BI',
                'ISO_3166_3' => 'BDI',
                'currency_code' => 'BIF',
                'status' => 1,
                'symbol' => 'BIF'
            ],
            [
                'dial_code' => 854,
                'name' => 'Burkina Faso',
                'country_code' => '854',
                'ISO_3166_2' => 'BF',
                'ISO_3166_3' => 'BFA',
                'currency_code' => 'XOF',
                'status' => 1,
                'symbol' => 'XOF'
            ],
            [
                'dial_code' => 100,
                'name' => 'Bulgaria',
                'country_code' => '100',
                'ISO_3166_2' => 'BG',
                'ISO_3166_3' => 'BGR',
                'currency_code' => 'BGN',
                'status' => 1,
                'symbol' => 'лв'
            ],
            [
                'dial_code' => 96,
                'name' => 'Brunei Darussalam',
                'country_code' => '96',
                'ISO_3166_2' => 'BN',
                'ISO_3166_3' => 'BRN',
                'currency_code' => 'BND',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 86,
                'name' => 'British Indian Ocean Territory',
                'country_code' => '86',
                'ISO_3166_2' => 'IO',
                'ISO_3166_3' => 'IOT',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 76,
                'name' => 'Brazil',
                'country_code' => '76',
                'ISO_3166_2' => 'BR',
                'ISO_3166_3' => 'BRA',
                'currency_code' => 'BRL',
                'status' => 1,
                'symbol' => 'R$'
            ],
            [
                'dial_code' => 74,
                'name' => 'Bouvet Island',
                'country_code' => '74',
                'ISO_3166_2' => 'BV',
                'ISO_3166_3' => 'BVT',
                'currency_code' => 'NOK',
                'status' => 1,
                'symbol' => 'NOK'
            ],
            [
                'dial_code' => 72,
                'name' => 'Botswana',
                'country_code' => '72',
                'ISO_3166_2' => 'BW',
                'ISO_3166_3' => 'BWA',
                'currency_code' => 'BWP',
                'status' => 1,
                'symbol' => 'P'
            ],
            [
                'dial_code' => 70,
                'name' => 'Bosnia and Herzegovina',
                'country_code' => '70',
                'ISO_3166_2' => 'BA',
                'ISO_3166_3' => 'BIH',
                'currency_code' => 'BAM',
                'status' => 1,
                'symbol' => 'KM'
            ],
            [
                'dial_code' => 535,
                'name' => 'Bonaire Sint Eustatius and Saba',
                'country_code' => '535',
                'ISO_3166_2' => 'BQ',
                'ISO_3166_3' => 'BES',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 68,
                'name' => 'Bolivia Plurinational State of',
                'country_code' => '68',
                'ISO_3166_2' => 'BO',
                'ISO_3166_3' => 'BOL',
                'currency_code' => 'BOB',
                'status' => 1,
                'symbol' => '$b'
            ],
            [
                'dial_code' => 64,
                'name' => 'Bhutan',
                'country_code' => '64',
                'ISO_3166_2' => 'BT',
                'ISO_3166_3' => 'BTN',
                'currency_code' => 'INR',
                'status' => 1,
                'symbol' => 'INR'
            ],
            [
                'dial_code' => 60,
                'name' => 'Bermuda',
                'country_code' => '60',
                'ISO_3166_2' => 'BM',
                'ISO_3166_3' => 'BMU',
                'currency_code' => 'BMD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 204,
                'name' => 'Benin',
                'country_code' => '204',
                'ISO_3166_2' => 'BJ',
                'ISO_3166_3' => 'BEN',
                'currency_code' => 'XOF',
                'status' => 1,
                'symbol' => 'XOF'
            ],
            [
                'dial_code' => 84,
                'name' => 'Belize',
                'country_code' => '84',
                'ISO_3166_2' => 'BZ',
                'ISO_3166_3' => 'BLZ',
                'currency_code' => 'BZD',
                'status' => 1,
                'symbol' => 'BZ$'
            ],
            [
                'dial_code' => 56,
                'name' => 'Belgium',
                'country_code' => '56',
                'ISO_3166_2' => 'BE',
                'ISO_3166_3' => 'BEL',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 112,
                'name' => 'Belarus',
                'country_code' => '112',
                'ISO_3166_2' => 'BY',
                'ISO_3166_3' => 'BLR',
                'currency_code' => 'BYR',
                'status' => 1,
                'symbol' => 'p.'
            ],
            [
                'dial_code' => 52,
                'name' => 'Barbados',
                'country_code' => '52',
                'ISO_3166_2' => 'BB',
                'ISO_3166_3' => 'BRB',
                'currency_code' => 'BBD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 50,
                'name' => 'Bangladesh',
                'country_code' => '50',
                'ISO_3166_2' => 'BD',
                'ISO_3166_3' => 'BGD',
                'currency_code' => 'BDT',
                'status' => 1,
                'symbol' => 'BDT'
            ],
            [
                'dial_code' => 48,
                'name' => 'Bahrain',
                'country_code' => '48',
                'ISO_3166_2' => 'BH',
                'ISO_3166_3' => 'BHR',
                'currency_code' => 'BHD',
                'status' => 1,
                'symbol' => 'BHD'
            ],
            [
                'dial_code' => 44,
                'name' => 'Bahamas',
                'country_code' => '44',
                'ISO_3166_2' => 'BS',
                'ISO_3166_3' => 'BHS',
                'currency_code' => 'BSD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 31,
                'name' => 'Azerbaijan',
                'country_code' => '31',
                'ISO_3166_2' => 'AZ',
                'ISO_3166_3' => 'AZE',
                'currency_code' => 'AZN',
                'status' => 1,
                'symbol' => '₼'
            ],
            [
                'dial_code' => 40,
                'name' => 'Austria',
                'country_code' => '40',
                'ISO_3166_2' => 'AT',
                'ISO_3166_3' => 'AUT',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 36,
                'name' => 'Australia',
                'country_code' => '36',
                'ISO_3166_2' => 'AU',
                'ISO_3166_3' => 'AUS',
                'currency_code' => 'AUD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 533,
                'name' => 'Aruba',
                'country_code' => '533',
                'ISO_3166_2' => 'AW',
                'ISO_3166_3' => 'ABW',
                'currency_code' => 'AWG',
                'status' => 1,
                'symbol' => 'ƒ'
            ],
            [
                'dial_code' => 51,
                'name' => 'Armenia',
                'country_code' => '51',
                'ISO_3166_2' => 'AM',
                'ISO_3166_3' => 'ARM',
                'currency_code' => 'AMD',
                'status' => 1,
                'symbol' => 'AMD'
            ],
            [
                'dial_code' => 32,
                'name' => 'Argentina',
                'country_code' => '32',
                'ISO_3166_2' => 'AR',
                'ISO_3166_3' => 'ARG',
                'currency_code' => 'ARS',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 28,
                'name' => 'Antigua and Barbuda',
                'country_code' => '28',
                'ISO_3166_2' => 'AG',
                'ISO_3166_3' => 'ATG',
                'currency_code' => 'XCD',
                'status' => 1,
                'symbol' => 'XCD'
            ],
            [
                'dial_code' => 10,
                'name' => 'Antarctica',
                'country_code' => '10',
                'ISO_3166_2' => 'AQ',
                'ISO_3166_3' => 'ATA',
                'currency_code' => '',
                'status' => 1,
                'symbol' => ''
            ],
            [
                'dial_code' => 660,
                'name' => 'Anguilla',
                'country_code' => '660',
                'ISO_3166_2' => 'AI',
                'ISO_3166_3' => 'AIA',
                'currency_code' => 'XCD',
                'status' => 1,
                'symbol' => 'XCD'
            ],
            [
                'dial_code' => 24,
                'name' => 'Angola',
                'country_code' => '24',
                'ISO_3166_2' => 'AO',
                'ISO_3166_3' => 'AGO',
                'currency_code' => 'AOA',
                'status' => 1,
                'symbol' => 'AOA'
            ],
            [
                'dial_code' => 20,
                'name' => 'Andorra',
                'country_code' => '20',
                'ISO_3166_2' => 'AD',
                'ISO_3166_3' => ' and ',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 16,
                'name' => 'American Samoa',
                'country_code' => '16',
                'ISO_3166_2' => 'as',
                'ISO_3166_3' => 'ASM',
                'currency_code' => 'USD',
                'status' => 1,
                'symbol' => '$'
            ],
            [
                'dial_code' => 12,
                'name' => 'Algeria',
                'country_code' => '12',
                'ISO_3166_2' => 'DZ',
                'ISO_3166_3' => 'DZA',
                'currency_code' => 'DZD',
                'status' => 1,
                'symbol' => 'DZD'
            ],
            [
                'dial_code' => 8,
                'name' => 'Albania',
                'country_code' => '8',
                'ISO_3166_2' => 'AL',
                'ISO_3166_3' => 'ALB',
                'currency_code' => 'ALL',
                'status' => 1,
                'symbol' => 'Lek'
            ],
            [
                'dial_code' => 248,
                'name' => 'Aland Islands',
                'country_code' => '248',
                'ISO_3166_2' => 'AX',
                'ISO_3166_3' => 'ALA',
                'currency_code' => 'EUR',
                'status' => 1,
                'symbol' => '€'
            ],
            [
                'dial_code' => 4,
                'name' => 'Afghanistan',
                'country_code' => '4',
                'ISO_3166_2' => 'AF',
                'ISO_3166_3' => 'AFG',
                'currency_code' => 'AFN',
                'status' => 1,
                'symbol' => '؋'
            ],
        ];

        // Insert countries and get their IDs
        DB::table('countries')->insert($countries);

        // Retrieve the IDs of the recently inserted countries
        $countryIds = DB::table('countries')->whereIn('country_code', array_column($countries, 'country_code'))->pluck('id', 'country_code');

        $token_codes = [
            'BTC' => 'Bitcoin',
            'ETH' => 'Ethereum',
            'LTC' => 'Litecoin',
            'BCH' => 'Bitcoin Cash',
            'BNB' => 'Binance Coin',
            'XRP' => 'Ripple',
            'USDT' => 'Tether',
            'DOGE' => 'Dogecoin',
            'ADA' => 'Cardano',
            'DOT' => 'Polkadot',
            'LINK' => 'Chainlink',
            'XLM' => 'Stellar',
            'USDC' => 'USD Coin',
            'TRX' => 'Tron',
            'ATOM' => 'Cosmos',
            'XTZ' => 'Tezos',
            'EOS' => 'EOS',
            'XMR' => 'Monero',
            'ZEC' => 'Zcash',
            'DASH' => 'Dash',

            'UST' => 'TerraUSD',
            'UCC' => 'UCC',
            'USX' => 'USX',
            'UCX' => 'UCX',
            'USB' => 'USB',
            'MAT' => 'Matic',
            'USP' => 'USP',
            'UCP' => 'UCP',
            'DOG' => 'Dogecoin',
            'SOL' => 'Solana',
            'USS' => 'USS',
            'UCS' => 'UCS',
            'TON' => 'Toncoin',
            'UTT' => 'UTT'
        ];
        

        // Prepare rates data
        $rates = [];

        foreach ($countries as $country) {
            $rates[] = [
                'country_id' => $countryIds[$country['country_code']], // Use country_code to get the corresponding ID
                'currency_name' => $country['name'],
                'currency_code' => $country['currency_code'],
                'currency_symbol' => $country['symbol'],
                'rate' => 0.0, // Assuming a default rate; modify as needed
            ];
        }

        // Add rates for all currency options with null country_id
        foreach ($token_codes as $token_code => $token_name) {
            $rates[] = [
                'country_id' => null, // No specific country associated with these currencies
                'currency_code' => $token_code,  // The token code like BTC, ETH, etc.
                'currency_name' => $token_name,  // The token's full name like Bitcoin, Ethereum, etc.
                'currency_symbol' => $token_code,  
                'rate' => in_array($token_code, ['USDT', 'UCC', 'USX', 'UCX', 'USB', 'USP', 'UCP', 'USS', 'UCS','UTT']) ? 1 : 0.0, // Default rate for USDT is 1, others are 0.0
            ];
        }

        // Insert rates
        DB::table('rates')->insert($rates);
    }
}
