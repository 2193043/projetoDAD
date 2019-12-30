<?php


namespace App\Http\Controllers;
use App\Http\Resources\WalletsResource;
use Illuminate\Http\Request;
use App\Wallets;
use Illuminate\Support\Facades\DB;

class WalletsControllerAPI extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id'=> 'required',
            'email'=> 'required',
            'balance'=> 'required'
        ]);
        $wallet = Wallets::create($request->all());
        return new WalletsResource($wallet);
    }

    public function update(Wallets $wallet, Request $request)
    {
        $wallet->update($request->all());
        return new WalletsResource($wallet);
    }

    public function totalWallets()
    {
        $data = DB::table('wallets')->count();
        return $data;
    }

    public function getWalletByEmail($email)
    {
        $data = DB::table('wallets')
            ->where('email', 'LIKE', "{$email}")
            ->get();
        return $data;
    }
    public function verifyEmailWallet($email)
    {
        $data = DB::table('wallets')
            ->where('email', 'LIKE', "{$email}")
            ->count();
        return $data;
    }

    public function getCurrentBalance($id)
    {
        $data = DB::table('wallets')
            ->where('id', 'LIKE', "{$id}")
            ->get();
        return $data;
    }

}

