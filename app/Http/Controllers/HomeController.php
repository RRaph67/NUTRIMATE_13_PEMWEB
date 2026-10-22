<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home / landing page.
     */
    public function index(): View
    {
        $features = [
            [
                'icon' => 'food',
                'color' => 'yellow',
                'title' => 'Food Database',
                'desc' => 'Katalog 1.500+ menu makanan lokal & warteg lengkap dengan estimasi harga riil per porsi.',
            ],
            [
                'icon' => 'budget',
                'color' => 'green',
                'title' => 'Budget Recommendation',
                'desc' => 'Rekomendasi paket makan sehat 3x sehari otomatis disesuaikan dengan sisa uang saku.',
            ],
            [
                'icon' => 'plan',
                'color' => 'orange',
                'title' => 'Meal Planner',
                'desc' => 'Atur jadwal makan mingguan secara rapi dan sinkronkan dengan sisa saldo bulanan kost.',
            ],
            [
                'icon' => 'calc',
                'color' => 'red',
                'title' => 'Nutrition Calculator',
                'desc' => 'Hitung kebutuhan kalori harian dan pantau makronutrisi agar badan tetap bugar sepanjang kuliah.',
            ],
        ];

        return view('home', [
            'features' => $features,
        ]);
    }
}
