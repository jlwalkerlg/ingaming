<?php

namespace App\Controllers;

use Bellona\Http\Controller;
use Bellona\Support\Facades\DB;
use App\Models\Game;
use Bellona\Support\Facades\CSRF;

class Home extends Controller
{
    public function index()
    {
        $csrf_token = CSRF::token();

        $featured = Game::where('featured', 1)->select('games.*', 'platforms.name as platform')->join('platforms', 'games.platform_id', '=', 'platforms.id')->first();

        $featuredPlatforms = ['PS4', 'Xbox One', 'PC'];

        $wherePlatforms = array_map(function ($shortName) {
            return ['platforms.short_name', $shortName];
        }, $featuredPlatforms);

        $platforms = DB::table('platforms')->orWhere($wherePlatforms)->get();

        $games = DB::table('games')->select('games.*', 'platforms.name as platform_name')->join('platforms', 'games.platform_id', '=', 'platforms.id')->orWhere($wherePlatforms)->get();

        $data['title'] = 'Welcome';
        $data['csrf_token'] = $csrf_token;
        $data['featured'] = $featured;
        $data['platforms'] = $platforms;
        $data['games'] = $games;
        $data['scripts'] = ['index'];

        render('home/index', $data);
    }
}
