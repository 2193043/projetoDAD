<?php


namespace App\Http\Controllers;
use App\Http\Resources\CategoriesResource;
use Illuminate\Http\Request;
use App\Categories;
use Illuminate\Support\Facades\DB;

class CategoriesControllerAPI extends Controller
{

    public function getExpenseCategory()
    {
        $data = DB::table('categories')
            ->where('type', 'LIKE', "e")
            ->get();
        return $data;
    }
    public function getIncomeCategory()
    {
        $data = DB::table('categories')
            ->where('type', 'LIKE', "i")
            ->get();
        return $data;
    }

    public function getAllCategories()
    {
        $data = DB::table('categories')->get();
        return $data;
    }

}

