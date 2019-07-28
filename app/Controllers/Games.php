<?php

namespace App\Controllers;

use Bellona\Http\Controller;

use Bellona\Support\Facades\DB;
use App\Lib\Pagination\Paginator;
use App\Models\Game;
use Bellona\Support\Facades\CSRF;
use Bellona\Http\Request;

class Games extends Controller
{
    public function index($page = '1')
    {
        $csrf_token = CSRF::token();

        // Get all platforms for filter form.
        $platforms = DB::table('platforms')->get();

        // Get all categories for filter form.
        $categories = DB::table('categories')->orderBy('name', 'asc')->get();

        // Pagination.
        $count = DB::table('games')->count();
        $page = (int)$page;
        $gamesPerPage = 20;
        $paginator = new Paginator(url('games/page/__page__'), $page, $gamesPerPage, $count);
        $paginationLinks = $paginator->numberedLinks();

        // Games.
        $games = Game::orderBy('rating', 'desc')->get();

        $data['title'] = 'Products';
        $data['csrf_token'] = $csrf_token;
        $data['platforms'] = $platforms;
        $data['categories'] = $categories;
        $data['games'] = $games;
        $data['paginationLinks'] = $paginationLinks;
        $data['scripts'] = ['products'];

        render('games/index', $data);
    }

    public function show($id = 1)
    {
        $game = Game::where('games.id', $id)->select('games.*', 'platforms.name as platform')->join('platforms', 'games.platform_id', '=', 'platforms.id')->first();

        $data['csrf_token'] = CSRF::token();
        $data['title'] = 'Show';
        $data['game'] = $game;
        $data['scripts'] = ['show'];

        render('games/show', $data);
    }

    public function search(Request $request)
    {
        $post = $request->data();

        $allowedOrderCols = ['price', 'rating', 'release_date'];
        $allowedOrderDirs = ['DESC', 'ASC'];

        $sql = "SELECT * FROM games WHERE ";
        $params = [];

        $platformSql = '';
        $categorySql = '';
        $minPriceSql = '';
        $maxPriceSql = '';
        $minRatingSql = '';
        $maxRatingSql = '';
        $releaseDataSql = '';

        if (!empty($post['platforms'])) {
            $platformSql .= '(';
            foreach ($post['platforms'] as $platform) {
                $platformSql .= "platform_id = ? OR ";
                $params[] = $platform;
            }
            $platformSql = rtrim($platformSql, 'OR ');
            $platformSql .= ") ";
        } else {
            echo json_encode([]);
            exit;
        }

        if (!empty($post['categories'])) {
            $categorySql .= '(';
            foreach ($post['categories'] as $category) {
                $categorySql .= "category_id = ? OR ";
                $params[] = $category;
            }
            $categorySql = rtrim($categorySql, 'OR ');
            $categorySql .= ") ";
        } else {
            echo json_encode([]);
            exit;
        }

        if (!empty($post['min_price'])) {
            $minPriceSql .= '(';
            $minPriceSql .= 'price >= ?';
            $params[] = $post['min_price'];
            $minPriceSql .= ')';
        }

        if (!empty($post['max_price'])) {
            $maxPriceSql .= '(';
            $maxPriceSql .= 'price <= ?';
            $params[] = $post['max_price'];
            $maxPriceSql .= ')';
        }

        if (!empty($post['min_rating'])) {
            $minRatingSql .= '(';
            $minRatingSql .= 'rating >= ?';
            $params[] = $post['min_rating'];
            $minRatingSql .= ')';
        }

        if (!empty($post['max_rating'])) {
            $maxRatingSql .= '(';
            $maxRatingSql .= 'rating <= ?';
            $params[] = $post['max_rating'];
            $maxRatingSql .= ')';
        }

        if (!empty($post['release_date'])) {
            $releaseDataSql .= "(";
            if (in_array('soon', $post['release_date'])) {
                $releaseDataSql .= 'release_date >= ? OR ';
                $params[] = date('Y-m-d');
            }
            if (in_array('now', $post['release_date'])) {
                $releaseDataSql .= 'release_date <= ?';
                $params[] = date('Y-m-d');
            }
            $releaseDataSql = trim($releaseDataSql, 'OR ');
            $releaseDataSql .= ")";
        } else {
            echo json_encode([]);
            exit;
        }

        $sql .= join(' AND ', array_filter([
            $platformSql,
            $categorySql,
            $minPriceSql,
            $maxPriceSql,
            $minRatingSql,
            $maxRatingSql,
            $releaseDataSql
        ], function ($sql) {
            return trim($sql);
        }));

        if (!empty($post['order_col'])) {
            $orderCol = $post['order_col'];
            if ($orderCol === 'release') $orderCol = 'release_date';
            if (in_array($orderCol, $allowedOrderCols)) {
                $sql .= " ORDER BY $orderCol";
                if (!empty($post['order_dir'])) {
                    $orderDir = $post['order_dir'];
                    if (in_array(strtoupper($orderDir), $allowedOrderDirs)) {
                        $sql .= " $orderDir";
                    } else {
                        $sql .= " DESC";
                    }
                }
            }
        }

        $result = $sql;
        foreach ($params as $param) {
            $result = substr_replace($result, $param, strpos($result, '?'), 1);
        }

        $sth = DB::query($sql, $params);
        $sth->execute();

        echo json_encode($sth->fetchAll());
    }
}
