<?php
// app/Http/Controllers/LandingPageController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    public function index()
    {

        
        // jika nanti butuh pass data dinamis, tambahkan di sini
        return view('landing.index');
    }

    public function showLP()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            return view('welcome');
        }
    }
}
