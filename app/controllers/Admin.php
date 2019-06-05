<?php

namespace App\Controllers;

use Bellona\Http\Controller;

use Bellona\Support\Facades\DB;
use Bellona\Support\Facades\Session;
use App\Models\Game;
use Bellona\Http\Request;
use Bellona\Uploads\FileUpload;
use App\Lib\Pagination\Paginator;

class Admin extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $data['title'] = 'Admin';
        $data['scripts'] = ['admin'];

        render('admin/dashboard', $data);
    }

    public function showGames($page = '1')
    {
        $limit = 50;
        $total = DB::table('games')->count();
        $page = (int)$page;

        $paginator = new Paginator(url('/admin/games/page/__page__'), (int)$page, $limit, $total);

        $totalPages = $paginator->getTotalPages();

        if ($page > $totalPages || $page < 1) {
            redirect('/admin/games/page/' . $totalPages);
        }

        $offset = $paginator->getOffset();

        $games = DB::table('games')->limit($limit)->offset($offset)->get();

        $data['title'] = 'Admin - Games';
        $data['games'] = $games;
        $data['paginator'] = $paginator;
        $data['scripts'] = ['adminGames'];

        render('admin/games', $data);
    }

    public function newGame()
    {
        $platforms = DB::table('platforms')->get();

        $data['title'] = 'Admin - Add New Games';
        $data['platforms'] = $platforms;
        $data['scripts'] = ['adminNewGames'];

        render('admin/newGame', $data);
    }

    public function createGame(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100|unique:games',
            'blurb' => 'required|max:300',
            'description' => 'required',
            'price' => 'required|format:numeric|max:999.99',
            'platform_id' => 'required|format:int',
            'release_date' => 'required|format:date',
            'rating' => 'required|format:int|min:1|max:5',
            'case_img' => 'required|types:jpg,jpeg,gif,png,svg,webp',
            'cover_img' => 'required|types:jpg,jpeg,gif,png,svg,webp',
            'trailer' => 'required|format:url|max:255'
        ]);

        $game = new Game;
        $game->assign($_POST);

        $success = true;

        try {
            DB::beginTransaction();
            // Save new game (without uploaded image names).
            if (!$game->save()) {
                throw new \Exception('Failed to save new game to database.');
            }
            // Get image destination using game id.
            $imgDestination = PUBLIC_ROOT . '/uploads/product_images/product_' . $game->id;
            // Upload images.
            $case_img = $request->upload('case_img');
            $cover_img = $request->upload('cover_img');
            $case_img->setOptions(['name' => 'case_img']);
            $cover_img->setOptions(['name' => 'cover_img']);
            $result = FileUpload::upload(['case_img', 'cover_img'], $imgDestination);
            if (!$result) {
                Session::set('errors', FileUpload::getUploadedErrors());
                throw new \Exception('Failed to upload files.');
            }
            // Save names of uploaded images to game record in database.
            $game->case_img = FileUpload::getUploadedName('case_img');
            $game->cover_img = FileUpload::getUploadedName('cover_img');
            if (!$game->save()) {
                throw new \Exception('Failed to save new game to database.');
            }
            DB::commit();
        } catch (Exception $e) {
            // Rollback transaction (don't save new game record).
            DB::rollBack();
            // Delete uploaded images.
            FileUpload::deleteUploaded();
            // Set success flag to false.
            $success = false;
        }

        if ($success) {
            Session::flash('alert', 'New game added to database.');
            redirect('/admin/games');
        }

        $request->save();
        Session::flash('alert', 'Failed to save new game to database.');
        back();
    }


    public function viewGame($id)
    {
        $game = DB::table('games')->select('games.*', 'platforms.name as platform')->join('platforms', 'platforms.id', '=', 'games.platform_id')->where('games.id', $id)->first();

        $data['title'] = 'Admin - View Game';
        $data['game'] = $game;
        $data['scripts'] = ['adminViewGame'];

        render('admin/viewGame', $data);
    }


    public function deleteGame($id)
    {
        $result = DB::table('games')->where('id', $id)->limit(1)->delete();

        if (!$result) {
            Session::flash('alert', 'Problem deleting game from database.');
            back();
        }

        Session::flash('alert', 'Game deleted.');
        redirect('admin/games');
    }


    public function editGame(Game $game)
    {
        $platforms = DB::table('platforms')->get();

        $data['title'] = 'Admin - Edit Game';
        $data['game'] = $game;
        $data['platforms'] = $platforms;
        $data['scripts'] = ['adminEditGame'];

        render('admin/editGame', $data);
    }


    public function updateGame(Request $request, Game $game)
    {
        $request->validate([
            'title' => 'required|max:100|unique:games,' . $game->getPrimaryKey() . ',' . $game->id,
            'blurb' => 'required|max:300',
            'description' => 'required',
            'price' => 'required|format:numeric|max:999.99',
            'platform_id' => 'required|format:int',
            'release_date' => 'required|format:date',
            'rating' => 'required|format:int|min:1|max:5',
            'case_img' => 'types:jpg,jpeg,gif,png,svg,webp',
            'cover_img' => 'types:jpg,jpeg,gif,png,svg,webp',
            'trailer' => 'required|format:url|max:255'
        ]);

        $game->assign($_POST);

        $fileNames = [];

        $case_img = $request->upload('case_img');
        $cover_img = $request->upload('cover_img');

        if ($case_img->wasUploaded()) {
            $fileNames[] = 'case_img';
            $case_img->setOptions(['name' => 'case_img']);
        }
        if ($cover_img->wasUploaded()) {
            $fileNames[] = 'cover_img';
            $cover_img->setOptions(['name' => 'cover_img']);
        }

        // If new game images were uploaded, attempt to store them.
        if ($fileNames) {
            $imgDestination = PUBLIC_ROOT . '/uploads/product_images/product_' . $game->id;

            if (!FileUpload::upload($fileNames, $imgDestination, ['overwrite' => true])) {
                Session::set('errors', FileUpload::getUploadedErrors());
                $request->save();
                back();
            }

            foreach ($fileNames as $name) {
                $game->$name = FileUpload::getUploadedName($name);
            }
        }

        if ($game->save()) {
            Session::flash('alert', 'Game updated.');
            redirect('admin/games/' . $game->id);
        }

        Session::flash('alert', 'Problem updating game.');
        back();
    }

    public function editFeatured()
    {
        $games = DB::table('games')->get();

        $data['title'] = 'Admin - Edit Featured';
        $data['games'] = $games;
        $data['scripts'] = ['adminEditGame'];

        render('admin/editFeatured', $data);
    }

    public function updateFeatured(Request $request)
    {
        $request->validate([
            'game_id' => 'format:int'
        ]);

        $game_id = $request->data('game_id');

        $sql = 'UPDATE games SET featured = CASE WHEN id = ? THEN 1 ELSE 0 END';
        $result = DB::query($sql, [$game_id])->execute();

        if ($result) {
            Session::flash('alert', 'Updated featured game.');
            redirect('admin');
        }

        Session::flash('alert', 'Failed to update featured game.');
        back();
    }
}
