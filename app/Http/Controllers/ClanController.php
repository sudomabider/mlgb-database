<?php
/**
 * Created by PhpStorm.
 * User: veoc
 * Date: 6/01/17
 * Time: 2:44 PM
 */

namespace App\Http\Controllers;


use App\Models\ClanRepository;

class ClanController extends Controller
{
    /**
     * @var ClanRepository
     */
    private $clanRepository;

    /**
     * ClanController constructor.
     * @param ClanRepository $clanRepository
     */
    public function __construct(ClanRepository $clanRepository)
    {
        $this->clanRepository = $clanRepository;
    }

    public function index()
    {
        $clan = $this->clanRepository->orderBy('id', 'desc')->limit(1)->findAll()->first();

        return response()->json(compact('clan'));
    }
}