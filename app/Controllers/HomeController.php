<?php

namespace App\Controllers;


class HomeController
{
    public static function index()
    {
        $data = ['name' => 'Joe', 'surname' => 'Bloggs'];
        // \Response::view('blade', $data = []);
        // \Response::json($data);
        // \Response::abort($code, $name = '', $message = '');
        return \Response::view('home', $data);
    }

    public function test()
    {
        echo "test method";
    }

    public function home()
    {
    	echo "home method";
    }
}
