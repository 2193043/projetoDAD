<?php


namespace App\Http\Controllers;
use App\Http\Resources\MovementsResource;
use Illuminate\Http\Request;
use App\Movements;
use Illuminate\Support\Facades\DB;

class MovementsControllerAPI extends Controller
{
    public function update(Movements $movement, Request $request)
    {
        $movement->timestamps = false;
        $movement->save();

        $movement->update($request->all());
        return new MovementsResource($movement);
    }

    public function getMovementByWalletId($id)
    {
        $data = DB::table('movements')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->leftJoin('categories', 'categories.id', '=', 'movements.category_id')
            ->where('movements.wallet_id', 'LIKE', "{$id}")
            ->orderBy('movements.date', 'desc')//mais recente
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
            //->get();
        return $data;
    }

    public function incomeMovement(Request $request)
    {
        $request->validate([
            'wallet_id'=> 'required',
            'transfer'=> 'required',
            'type'=> 'required',
            'type_payment'=> 'required',
            //'iban'=> 'digits:23',
            'date'=> 'required',
            'start_balance'=> 'required',
            'end_balance'=> 'required',
            'value'=> 'required'
        ]);
        $movement = new Movements();
        $movement->fill($request->all());
        $movement->timestamps = false;
        $movement->save();
        return new MovementsResource($movement);
    }

    public function incomeMovementTransfer(Request $request)
    {
        $request->validate([
            'wallet_id'=> 'required',
            'transfer'=> 'required',
            'type'=> 'required',
            'transfer_movement_id'=> 'required',
	        'transfer_wallet_id' => 'required',
            'date'=> 'required',
            'start_balance'=> 'required',
            'end_balance'=> 'required',
            'value'=> 'required'
        ]);
        $movement = new Movements();
        $movement->fill($request->all());
        $movement->timestamps = false;
        $movement->save();
        return new MovementsResource($movement);
    }


    public function externalPaymentTypeMB(Request $request)
    {
        $request->validate([
            'wallet_id'=> 'required',
            'type'=> 'required',
            'transfer'=> 'required',
            'type_payment'=> 'required',
            'mb_entity_code'=> 'digits:5',
            'mb_payment_reference'=> 'digits:9',
            'date'=> 'required',
            'start_balance'=> 'required',
            'end_balance'=> 'required',
            'value'=> 'required'
        ]);
        $movement = new Movements();
        $movement->fill($request->all());
        $movement->timestamps = false;
        $movement->save();
        return new MovementsResource($movement);
    }

    public function externalPaymentTypeBT(Request $request)
    {
        $request->validate([
            'wallet_id'=> 'required',
            'type'=> 'required',
            'transfer'=> 'required',
            'type_payment'=> 'required',
            'iban'=> 'required',
            'date'=> 'required',
            'start_balance'=> 'required',
            'end_balance'=> 'required',
            'value'=> 'required'
        ]);
        $movement = new Movements();
        $movement->fill($request->all());
        $movement->timestamps = false;
        $movement->save();
        return new MovementsResource($movement);
    }

    public function transferPayment(Request $request)
    {
        $request->validate([
            'wallet_id'=> 'required',
            'type'=> 'required',
            'transfer'=> 'required',
            //'transfer_movement_id'=> 'required',
            'transfer_wallet_id'=> 'required',
            'date'=> 'required',
            'start_balance'=> 'required',
            'end_balance'=> 'required',
            'value'=> 'required'
        ]);
        $movement = new Movements();
        $movement->fill($request->all());
        $movement->timestamps = false;
        $movement->save();
        return new MovementsResource($movement);
    }
    public function totalExpensesMovements($id)
    {
        $data = DB::table('movements')
            ->where([
                ['wallet_id', 'LIKE', "{$id}"],
                ['type', '=', "e"],
            ])
            ->count();
        return $data;
    }
    public function totalExpenses($id)
    {
        $data = DB::table('movements')
            ->where([
                ['wallet_id', 'LIKE', "{$id}"],
                ['type', '=', "e"],
            ])
            ->sum('value');
        return $data;
    }
    public function totalIncomeMovements($id)
    {
        $data = DB::table('movements')
            ->where([
                ['wallet_id', 'LIKE', "{$id}"],
                ['type', '=', "i"],
            ])
            ->count();
        return $data;
    }
    public function totalIncome($id)
    {
        $data = DB::table('movements')
            ->where([
                ['wallet_id', 'LIKE', "{$id}"],
                ['type', '=', "i"],
            ])
            ->sum('value');
        return $data;
    }

    public function searchByCategory($idWallet, $idCategory)
    {
        $data = DB::table('movements')
            ->Join('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.category_id', 'LIKE', "{$idCategory}"],
                ])
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
            //->get();
        return $data;
    }

    public function searchByTypeMovement($idWallet, $typeMovement)
    {
        $data = DB::table('movements')
            ->leftJoin('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.type', 'LIKE', "{$typeMovement}"],
                ])
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
            //->get();
        return $data;
    }

    public function searchByTypeMovementAndCategory($idWallet, $typeMovement, $idCategory)
    {
        $data = DB::table('movements')
            ->Join('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.type', 'LIKE', "{$typeMovement}"],
                ['movements.category_id', 'LIKE', "{$idCategory}"],
                ])
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByTypePayment($idWallet, $typePayment)
    {
        $data = DB::table('movements')
            ->leftJoin('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.type_payment', 'LIKE', "{$typePayment}"],
                ])
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByTypePaymentAndCategory($idWallet, $typePayment, $idCategory)
    {
        $data = DB::table('movements')
            ->Join('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.type_payment', 'LIKE', "{$typePayment}"],
                ['movements.category_id', 'LIKE', "{$idCategory}"],
                ])
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByTypePaymentAndTypeMovement($idWallet, $typePayment, $typeMovement)
    {
        $data = DB::table('movements')
            ->leftJoin('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.type_payment', 'LIKE', "{$typePayment}"],
                ['movements.type', 'LIKE', "{$typeMovement}"],
                ])
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByTypePaymentAndCategoryAndTypeMovement($idWallet, $typePayment, $idCategory, $typeMovement)
    {
        $data = DB::table('movements')
            ->Join('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.type_payment', 'LIKE', "{$typePayment}"],
                ['movements.category_id', 'LIKE', "{$idCategory}"],
                ['movements.type', 'LIKE', "{$typeMovement}"],
                ])
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByEmailTransfer($idWallet, $emailTransfer)
    {
        $data = DB::table('movements')
            ->Join('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->leftJoin('categories', 'categories.id', '=', 'movements.category_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['wallets.email', 'LIKE', "%{$emailTransfer}%"],
                ])
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'wallets.email', 'categories.name')
            ->paginate(10);
        return $data;
    }

    public function searchByEmailTransferAndCategory($idWallet, $emailTransfer, $idCategory)
    {
        $data = DB::table('movements')
            ->Join('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->Join('categories', 'categories.id', '=', 'movements.category_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['wallets.email', 'LIKE', "%{$emailTransfer}%"],
                ['movements.category_id', 'LIKE', "{$idCategory}"],
                ])
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'wallets.email', 'categories.name')
            ->paginate(10);
        return $data;
    }

    public function searchByEmailTransferAndTypeMovement($idWallet, $emailTransfer, $typeMovement)
    {
        $data = DB::table('movements')
            ->Join('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->leftJoin('categories', 'categories.id', '=', 'movements.category_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['wallets.email', 'LIKE', "%{$emailTransfer}%"],
                ['movements.type', 'LIKE', "{$typeMovement}"],
                ])
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'wallets.email', 'categories.name')
            ->paginate(10);
        return $data;
    }

    public function searchByEmailTransferAndCategoryAndTypeMovement($idWallet, $emailTransfer, $idCategory, $typeMovement)
    {
        $data = DB::table('movements')
            ->Join('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->Join('categories', 'categories.id', '=', 'movements.category_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['wallets.email', 'LIKE', "%{$emailTransfer}%"],
                ['movements.category_id', 'LIKE', "{$idCategory}"],
                ['movements.type', 'LIKE', "{$typeMovement}"],
                ])
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'wallets.email', 'categories.name')
            ->paginate(10);
        return $data;
    }

    public function searchByDate($idWallet, $fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay)
    {
        $from = date("{$fromYear}-{$fromMonth}-{$fromDay}" . ' 00:00:00', time());
        $to = date("{$toYear}-{$toMonth}-{$toDay}" . ' 00:00:00', time());
        $data = DB::table('movements')
            ->leftJoin('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ])
            ->whereBetween('movements.date', array($from, $to))
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByDateAndCategory($idWallet, $fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $idCategory)
    {
        $from = date("{$fromYear}-{$fromMonth}-{$fromDay}" . ' 00:00:00', time());
        $to = date("{$toYear}-{$toMonth}-{$toDay}" . ' 00:00:00', time());
        $data = DB::table('movements')
            ->Join('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.category_id', 'LIKE', "{$idCategory}"],
                ])
            ->whereBetween('movements.date', array($from, $to))
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByDateAndTypeMovement($idWallet, $fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $typeMovement)
    {
        $from = date("{$fromYear}-{$fromMonth}-{$fromDay}" . ' 00:00:00', time());
        $to = date("{$toYear}-{$toMonth}-{$toDay}" . ' 00:00:00', time());
        $data = DB::table('movements')
            ->leftJoin('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.type', 'LIKE', "{$typeMovement}"],
                ])
            ->whereBetween('movements.date', array($from, $to))
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByDateAndTypePayment($idWallet, $fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $typePayment)
    {
        $from = date("{$fromYear}-{$fromMonth}-{$fromDay}" . ' 00:00:00', time());
        $to = date("{$toYear}-{$toMonth}-{$toDay}" . ' 00:00:00', time());
        $data = DB::table('movements')
            ->leftJoin('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.type_payment', 'LIKE', "{$typePayment}"],
                ])
            ->whereBetween('movements.date', array($from, $to))
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByDateAndEmailTransfer($idWallet, $fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $emailTransfer)
    {
        $from = date("{$fromYear}-{$fromMonth}-{$fromDay}" . ' 00:00:00', time());
        $to = date("{$toYear}-{$toMonth}-{$toDay}" . ' 00:00:00', time());
        $data = DB::table('movements')
            ->Join('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->leftJoin('categories', 'categories.id', '=', 'movements.category_id')
            ->where([
            ['movements.wallet_id', 'LIKE', "{$idWallet}"],
            ['wallets.email', 'LIKE', "%{$emailTransfer}%"],
            ])
            ->whereBetween('movements.date', array($from, $to))
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'wallets.email', 'categories.name')
            ->paginate(10);
        return $data;
    }

    public function searchByDateAndCategoryAndTypeMovement($idWallet, $fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $idCategory, $typeMovement)
    {
        $from = date("{$fromYear}-{$fromMonth}-{$fromDay}" . ' 00:00:00', time());
        $to = date("{$toYear}-{$toMonth}-{$toDay}" . ' 00:00:00', time());
        $data = DB::table('movements')
            ->Join('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.category_id', 'LIKE', "{$idCategory}"],
                ['movements.type', 'LIKE', "{$typeMovement}"],
                ])
            ->whereBetween('movements.date', array($from, $to))
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByDateAndCategoryAndTypePayment($idWallet, $fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $idCategory, $typePayment)
    {
        $from = date("{$fromYear}-{$fromMonth}-{$fromDay}" . ' 00:00:00', time());
        $to = date("{$toYear}-{$toMonth}-{$toDay}" . ' 00:00:00', time());
        $data = DB::table('movements')
            ->Join('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.category_id', 'LIKE', "{$idCategory}"],
                ['movements.type_payment', 'LIKE', "{$typePayment}"],
                ])
            ->whereBetween('movements.date', array($from, $to))
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByDateAndCategoryAndEmailTransfer($idWallet, $fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $idCategory, $emailTransfer)
    {
        $from = date("{$fromYear}-{$fromMonth}-{$fromDay}" . ' 00:00:00', time());
        $to = date("{$toYear}-{$toMonth}-{$toDay}" . ' 00:00:00', time());
        $data = DB::table('movements')
            ->Join('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->Join('categories', 'categories.id', '=', 'movements.category_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.category_id', 'LIKE', "{$idCategory}"],
                ['wallets.email', 'LIKE', "%{$emailTransfer}%"],
                ])
            ->whereBetween('movements.date', array($from, $to))
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByDateAndCategoryAndTypeMovementAndTypePayment($idWallet, $fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $idCategory, $typeMovement, $typePayment)
    {
        $from = date("{$fromYear}-{$fromMonth}-{$fromDay}" . ' 00:00:00', time());
        $to = date("{$toYear}-{$toMonth}-{$toDay}" . ' 00:00:00', time());
        $data = DB::table('movements')
            ->Join('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.category_id', 'LIKE', "{$idCategory}"],
                ['movements.type', 'LIKE', "{$typeMovement}"],
                ['movements.type_payment', 'LIKE', "{$typePayment}"],
                ])
            ->whereBetween('movements.date', array($from, $to))
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByDateAndCategoryAndTypeMovementAndEmailTransfer($idWallet, $fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $idCategory, $typeMovement, $emailTransfer)
    {
        $from = date("{$fromYear}-{$fromMonth}-{$fromDay}" . ' 00:00:00', time());
        $to = date("{$toYear}-{$toMonth}-{$toDay}" . ' 00:00:00', time());
        $data = DB::table('movements')
            ->Join('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->Join('categories', 'categories.id', '=', 'movements.category_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.category_id', 'LIKE', "{$idCategory}"],
                ['movements.type', 'LIKE', "{$typeMovement}"],
                ['wallets.email', 'LIKE', "%{$emailTransfer}%"],
                ])
            ->whereBetween('movements.date', array($from, $to))
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByDateAndTypeMovementAndTypePayment($idWallet, $fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $typeMovement, $typePayment)
    {
        $from = date("{$fromYear}-{$fromMonth}-{$fromDay}" . ' 00:00:00', time());
        $to = date("{$toYear}-{$toMonth}-{$toDay}" . ' 00:00:00', time());
        $data = DB::table('movements')
            ->leftJoin('categories', 'categories.id', '=', 'movements.category_id')
            ->leftJoin('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->where([
                ['movements.wallet_id', 'LIKE', "{$idWallet}"],
                ['movements.type', 'LIKE', "{$typeMovement}"],
                ['movements.type_payment', 'LIKE', "{$typePayment}"],
                ])
            ->whereBetween('movements.date', array($from, $to))
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'categories.name', 'wallets.email')
            ->paginate(10);
        return $data;
    }

    public function searchByDateAndTypeMovementAndEmailTransfer($idWallet, $fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay, $typeMovement, $emailTransfer)
    {
        $from = date("{$fromYear}-{$fromMonth}-{$fromDay}" . ' 00:00:00', time());
        $to = date("{$toYear}-{$toMonth}-{$toDay}" . ' 00:00:00', time());
        $data = DB::table('movements')
            ->Join('wallets', 'wallets.id', '=', 'movements.transfer_wallet_id')
            ->leftJoin('categories', 'categories.id', '=', 'movements.category_id')
            ->where([
            ['movements.wallet_id', 'LIKE', "{$idWallet}"],
            ['movements.type', 'LIKE', "{$typeMovement}"],
            ['wallets.email', 'LIKE', "%{$emailTransfer}%"],
            ])
            ->whereBetween('movements.date', array($from, $to))
            ->orderBy('movements.date', 'desc')
            ->select('movements.*', 'wallets.email', 'categories.name')
            ->paginate(10);
        return $data;
    }
    
    
}

