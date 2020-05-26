<?php

namespace App\Http\Controllers\admin;

use App\Models\Like;
use App\Models\User;
use App\Models\Views;
use App\Models\Reward;

use App\Models\NewsItem;
use App\Models\SharePageUsers;
use Illuminate\Support\Carbon;
use App\Models\ApplicantSeason5;
use App\Models\ApplicantSeason6;
use App\Models\ApplicantSeason7;
use Illuminate\Support\Facades\DB;
use App\Models\ApplicantSeasonFour;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Models\CampaignShareAndSubscribe;

use Debugbar;

class DashboardController extends Controller
{
    public function test()
    {
        $player = [
            ['Name' => 'A', 'score' => 50],
            ['Name'=>'B', 'score'=> 60],
            ['Name'=>'C', 'score'=> 85],
            ['Name'=>'D', 'score'=> 80],
            ['Name'=>'E', 'score'=> 90]
        ];

        $b = collect($player);

        $rank = ['Gold', 'Silver', 'Bronze'];


        $c = $b->map(function ($e) use ($rank) {

            if ($e['score'] >= 90) {
                $e['rank'] = 'Gold';
            } elseif ($e['score'] >= 80)
                $e['rank'] = 'Silver';
            elseif ($e['score'] < 80)
                $e['rank'] = '-';
            return $e;
        });

            $obj = [
                "key1" => [
                    "key2" => "value1",
                    "key3" => "value2",
                    "key4" => [
                        "key5"=> "value5"
                    ]
                ]
            ];


        // ข้อ 2
        $obj = collect($obj);

        $b = $this->loopGetValue($obj);

        $result = array_search ('value5', $b);


        dd($c->toarray(), $result , $c->toJson());

    }

    private function loopGetValue($obj)
    {
        foreach ($obj as $someObj) {
            foreach ($someObj as $key => $value) {
                if (is_array($value)) {
                    return $value;
                }
            }
        }
    }

}
