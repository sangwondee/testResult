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
    public function index()
    {
        $now = Carbon::now();

        // Days for query data (Current: last 7 days)
        for ($i = 0; $i <= 6; $i++) {
            $days[] = Carbon::now()->subDays($i);
        }

        foreach($days as $day){
            $data['days_label'][] = $day->format('j M');
        }

        // Subscribers
        for($i = 0; $i < count($days); $i++){
            $data['subscribers']['count'][] = User::whereDate('created_at', $days[$i])->count();
        }

        $data['subscribers']['total'] = User::whereDate('created_at', '<=', $now)->count();

        // Applicant: Season 4
        for ($i = 0; $i < count($days); $i++) {
            $data['applicant_season_4']['count'][] = ApplicantSeasonFour::whereDate('created_at', $days[$i])->count();
        }

        $data['applicant_season_4']['total'] = ApplicantSeasonFour::whereDate('created_at', '<=', $now)->count();

        // Applicant: season 5
        for ($i = 0; $i < count($days); $i++) {
            $data['applicant_season_5']['count'][] = ApplicantSeason5::whereDate('created_at', $days[$i])->count();
        }

        $data['applicant_season_5']['total'] = ApplicantSeason5::whereDate('created_at', '<=', $now)->count();

        // // Applicant season 6
        for ($i = 0; $i < count($days); $i++) {
            $data['applicant_season_6']['count'][] = ApplicantSeason6::whereDate('created_at', $days[$i])->count();
        }

        $data['applicant_season_6']['total'] = ApplicantSeason6::whereDate('created_at', '<=', $now)->count();

        // Applicant season 7 (King of gamer online cups)
        for ($i = 0; $i < count($days); $i++) {
            $data['applicant_season_7']['count'][] = ApplicantSeason7::whereDate('created_at', $days[$i])->count();
        }

        $data['applicant_season_7']['total'] = ApplicantSeason7::whereDate('created_at', '<=', $now)->count();


        // Campaign: Share And Subscribe
        for($i = 0; $i < count($days); $i++) {

            $users_share = CampaignShareAndSubscribe::whereDate('shared_at', $days[$i])
                ->groupBy('user_id')
                ->get(\DB::raw("max(id) as id"));

            $uid = [];

            foreach( $users_share as $user) {
                $uid[] = $user['id'];
            }

            $data['campaigns_subscriber_share']['count'][] = CampaignShareAndSubscribe::whereIn('id', $uid)
                ->orderBy('user_id')
                ->count();
        }

        $data['campaigns_subscriber_share']['total'] = CampaignShareAndSubscribe::whereDate('shared_at', '<=', $now)
            ->count();

        // VIEW LIKE SHARE
        for ($i=0; $i < count($days); $i++) {
            $data['views']['count'][] = Views::whereDate('viewed_at', $days[$i])->count();
            $data['likes']['count'][] = Like::whereDate('created_at', $days[$i])->count();
            $data['shares']['count'][] = SharePageUsers::whereDate('created_at', $days[$i])->count();
        }

        $views_total  = Views::count();
        $like_total  = Like::count();
        $share_page_total  = SharePageUsers::count();

        $data['views']['total'] = $views_total;
        $data['likes']['total'] = $like_total;
        $data['shares']['total'] = $share_page_total;

        // TOP NEWS
        // dd($now);

        // $v = Views::whereDate('viewed_at', $now)
        //     ->where('viewable_type', 'App\Models\NewsItem')
        //     ->get();
        // $v = Views::whereDate('viewed_at', $now)
        //     ->where('viewable_type', 'App\Models\NewsItem')
        //     ->toSql();

            // dd($v);

        // $v_id = $v->groupBy('viewable_id');
        // dd($v_id->);
        // $a = $v_id->toArray();

        // dd($a);

        // $b = array_combine($a, $a);
        // dd($b);
        // dd($a);
        // $keys=array_keys($a);
        // foreach ($a as $key => $value) {
        //     $b = [];
        //     $b = $key;



        // }

        // dd($b);

// $filledArray=array_fill_keys($keys,array());
// dd($filledArray);
        // foreach ($a as $key => $value) {
        //     $b = [];

        // }




        // foreach ($a as $key => $value) {
        //     $b = NewsItem::where('id', $key)->orderBy('view_count', 'asc')->limit(5)->get();
        // }

        // dd($b->get());


        // $ttd = $vtd->map(function ($item, $key) {
        //     // dd($item->viewable_id);
        //     $newsaa = NewsItem::where('id', $item->viewable_id)
        //         ->orderBy('view_count', 'desc')
        //         // ->orderBy('created_at', 'desc')
        //         ->limit(5)
        //         ->get();
        // });





        // $data['top_news_overall'] = NewsItem::orderBy('view_count', 'desc')->limit(5)->get();

        // $data['top_news_today'] = $b;

        // buu
        $data['top_news_overall'] = NewsItem::orderBy('view_count', 'desc')->limit(5)->get();
        $data['top_news_today'] = NewsItem::orderBy('created_at', 'desc')->limit(5)->get(); // ----DUMMY----

        // TOP POINTS
        $data['top_point_users'] = User::orderBy('point', 'desc')->limit(5)->get();

        $data['reward_amounts'] = Reward::orderBy('quantity', 'asc')
            ->limit(5)
            ->get();

        return View::make('admin.v2.dashboard')->with($data);
    }



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

        // dd($b);

        $c = $b->map(function ($e) use ($rank) {

            if ($e['score'] >= 90) {
                $e['rank'] = 'Gold';
            } elseif ($e['score'] >= 80)
                $e['rank'] = 'Silver';
            elseif ($e['score'] < 80)
                $e['rank'] = '-';
            return $e;
        });

        //     dd($c->toarray(), 'hi');

            // ทำเป็น object



            $obj = [
                "key1" => [
                    "key2" => "value1",
                    "key3" => "value2",
                    "key4" => [
                        "key5"=> "value5"
                    ]
                ]
            ];

            $obj = collect($obj);

            $b = $this->loopGetValue($obj);

            $result = array_search ('value5', $b);

            dd($c->toarray(), $result , $c->toJson());

            // foreach ($obj as $item => $value) {
            //     // dd($item, $value);

            //     if()
            // }










    }

    public function loopGetValue($obj)
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
