<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Community;
use App\Models\CommunityUser;
use App\Models\Favorite;
use App\Models\Follower;
use App\Models\Ingredient;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\Recipe;
use App\Models\Review;
use App\Models\Step;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    private $users = [
        [
            'name' => 'Alifa Salsabila',
            'email' => 'alifa1@example.com',
            'username' => 'alifabee',
            'no_hp' => '081234567890',
            'tanggal_lahir' => '2002-05-15',
            'password' => '$2a$12$eyAmZQThBEOz/BBBn/H18O8alngb/XXBdppaigsHL3HhMplyRXx3m', // nanti diisi hash di seeder
            'gender' => 'P',
            'foto' => 'avatars/alifa.jpg',
            // 'community_id' => 1,
        ],
        [
            'name' => 'Klara Oliviera',
            'email' => 'klara1@example.com',
            'username' => 'klarakeren',
            'no_hp' => '081298765432',
            'tanggal_lahir' => '2001-11-25',
            'password' => '$2a$12$qylmw4WQ9ZatnqB4AMq7X.MmVe3xzpOhe8f2V/QaQ63YDybg1sCCi',
            'gender' => 'P',
            'foto' => 'avatars/klara.jpg',
            // 'community_id' => 2,
        ],
        [
            'name' => 'Naeya Adeani',
            'email' => 'naeya1@example.com',
            'username' => 'notnaex',
            'no_hp' => '082112345678',
            'tanggal_lahir' => '2003-02-10',
            'password' => '$2a$12$qHZ9801mAm3YGnntPCGemeofmeOgGwohvRVhV1FbM1gpzH18HgdWm',
            'gender' => 'P',
            'foto' => 'avatars/naeya.jpg',
            // 'community_id' => 1,
        ],
        [
            'name' => 'Jihan Aqilah',
            'email' => 'jihan1@example.com',
            'username' => 'jianjeyn',
            'no_hp' => '082234567890',
            'tanggal_lahir' => '2004-02-10',
            'password' => '$2a$12$5UpKopDFqU35RCMFoPNTueszM5V.HEFi8iiBJPH52k0Ii/abhtCFe',
            'gender' => 'P',
            'foto' => 'avatars/jihan.jpg',
            // 'community_id' => 1,
        ],
        [
            'name' => 'Ririn Marcelina',
            'email' => 'ririn1@example.com',
            'username' => 'marchrin',
            'no_hp' => '082345678901',
            'tanggal_lahir' => '2002-03-15',
            'password' => '$2a$12$ZZkLk9Lnnzt.nL8UvFOuE.3BxzU5dapalpx3lYbZaTNWTNKK1Hbzy',
            'gender' => 'P',
            'foto' => 'avatars/ririn.jpg',
            // 'community_id' => 1,
        ],
        [
            'name' => 'Yahyo Abdulozoda',
            'email' => 'yahyo1@example.com',
            'username' => 'yahyocoolguy',
            'no_hp' => '081234567890',
            'tanggal_lahir' => '2000-01-01',
            'password' => '$2a$12$YbO2u6Cgo0dSXTP.Dez1tOHzrm00OgaM4EdQ3f4GLVt1oLtwI/.86',
            'gender' => 'L',
            'foto' => 'avatars/yahyo.jpg',
            // 'community_id' => 1,
        ]
    ];

    private $Recipes = [
        [
            "user_id" => 1,
            "nama" => "Nasi Goreng Telur",
            "foto" => null,
            "detail" => "Menu cepat dan mudah untuk sarapan atau makan malam.",
            "durasi" => "15 Menit",
            "kategori" => "Breakfast",
            "jenis_hidangan" => "Makanan Sederhana",
            "estimasi_waktu" => "<15 Min",
            "tingkat_kesulitan" => "Mudah"
        ],
        [
            "user_id" => 2,
            "nama" => "Sayur Bening Bayam",
            "foto" => null,
            "detail" => "Sayur sehat untuk makan siang keluarga.",
            "durasi" => "20 Menit",
            "kategori" => "Lunch",
            "jenis_hidangan" => "Makanan Sehat",
            "estimasi_waktu" => "<30 Min",
            "tingkat_kesulitan" => "Mudah"
        ],
        [
            "user_id" => 3,
            "nama" => "Tempe Goreng Tepung",
            "foto" => null,
            "detail" => "Lauk favorit semua kalangan.",
            "durasi" => "15 Menit",
            "kategori" => "Lunch",
            "jenis_hidangan" => "Makanan Sederhana",
            "estimasi_waktu" => "<15 Min",
            "tingkat_kesulitan" => "Mudah"
        ],
        [
            "user_id" => 4,
            "nama" => "Tumis Kangkung",
            "foto" => null,
            "detail" => "Sayuran praktis dan cepat disajikan.",
            "durasi" => "10 Menit",
            "kategori" => "Dinner",
            "jenis_hidangan" => "Makanan Sederhana",
            "estimasi_waktu" => "<15 Min",
            "tingkat_kesulitan" => "Mudah"
        ],
        [
            "user_id" => 5,
            "nama" => "Telur Dadar",
            "foto" => null,
            "detail" => "Lauk serba guna dan favorit semua orang.",
            "durasi" => "10 Menit",
            "kategori" => "Dinner",
            "jenis_hidangan" => "Makanan Sederhana",
            "estimasi_waktu" => "<15 Min",
            "tingkat_kesulitan" => "Mudah"
        ]
    ];

    private $Ingredients = [
        // Nasi Goreng Telur (recipe_id = 1)
        [ "recipe_id" => 1, "bahan" => "Nasi" ],
        [ "recipe_id" => 1, "bahan" => "Telur" ],
        [ "recipe_id" => 1, "bahan" => "Bawang Merah" ],
        [ "recipe_id" => 1, "bahan" => "Bawang Putih" ],
        [ "recipe_id" => 1, "bahan" => "Kecap Manis" ],

        // Sayur Bening Bayam (recipe_id = 2)
        [ "recipe_id" => 2, "bahan" => "Bayam" ],
        [ "recipe_id" => 2, "bahan" => "Wortel" ],
        [ "recipe_id" => 2, "bahan" => "Bawang Putih" ],
        [ "recipe_id" => 2, "bahan" => "Garam" ],
        [ "recipe_id" => 2, "bahan" => "Air" ],

        // Tempe Goreng Tepung (recipe_id = 3)
        [ "recipe_id" => 3, "bahan" => "Tempe" ],
        [ "recipe_id" => 3, "bahan" => "Tepung Terigu" ],
        [ "recipe_id" => 3, "bahan" => "Bawang Putih" ],
        [ "recipe_id" => 3, "bahan" => "Garam" ],

        // Tumis Kangkung (recipe_id = 4)
        [ "recipe_id" => 4, "bahan" => "Kangkung" ],
        [ "recipe_id" => 4, "bahan" => "Bawang Putih" ],
        [ "recipe_id" => 4, "bahan" => "Cabe Merah" ],
        [ "recipe_id" => 4, "bahan" => "Terasi" ],

        // Telur Dadar (recipe_id = 5)
        [ "recipe_id" => 5, "bahan" => "Telur" ],
        [ "recipe_id" => 5, "bahan" => "Daun Bawang" ],
        [ "recipe_id" => 5, "bahan" => "Bawang Merah" ],
        [ "recipe_id" => 5, "bahan" => "Garam" ]
    ];

    private $Steps = [
        // Nasi Goreng Telur (resep_id = 1)
        [ "resep_id" => 1, "no" => 1, "deskripsi" => "Panaskan minyak dan tumis bawang merah dan bawang putih hingga harum." ],
        [ "resep_id" => 1, "no" => 2, "deskripsi" => "Masukkan telur, orak-arik hingga matang." ],
        [ "resep_id" => 1, "no" => 3, "deskripsi" => "Tambahkan nasi, aduk rata." ],
        [ "resep_id" => 1, "no" => 4, "deskripsi" => "Tuangkan kecap manis, aduk rata, dan masak hingga matang." ],

        // Sayur Bening Bayam (resep_id = 2)
        [ "resep_id" => 2, "no" => 1, "deskripsi" => "Didihkan air dalam panci." ],
        [ "resep_id" => 2, "no" => 2, "deskripsi" => "Masukkan bawang putih dan wortel, rebus hingga setengah matang." ],
        [ "resep_id" => 2, "no" => 3, "deskripsi" => "Tambahkan bayam, masak sebentar hingga layu." ],
        [ "resep_id" => 2, "no" => 4, "deskripsi" => "Tambahkan garam, aduk, dan sajikan." ],

        // Tempe Goreng Tepung (resep_id = 3)
        [ "resep_id" => 3, "no" => 1, "deskripsi" => "Iris tempe sesuai selera." ],
        [ "resep_id" => 3, "no" => 2, "deskripsi" => "Campur tepung, bawang putih halus, dan garam dengan sedikit air hingga menjadi adonan." ],
        [ "resep_id" => 3, "no" => 3, "deskripsi" => "Celupkan tempe ke adonan, lalu goreng hingga keemasan." ],

        // Tumis Kangkung (resep_id = 4)
        [ "resep_id" => 4, "no" => 1, "deskripsi" => "Tumis bawang putih dan cabe hingga harum." ],
        [ "resep_id" => 4, "no" => 2, "deskripsi" => "Tambahkan terasi, aduk rata." ],
        [ "resep_id" => 4, "no" => 3, "deskripsi" => "Masukkan kangkung, aduk cepat dan masak hingga layu." ],

        // Telur Dadar (resep_id = 5)
        [ "resep_id" => 5, "no" => 1, "deskripsi" => "Kocok telur bersama daun bawang, bawang merah, dan garam." ],
        [ "resep_id" => 5, "no" => 2, "deskripsi" => "Panaskan minyak, tuang adonan telur, dan goreng hingga matang kedua sisi." ]
    ];

    private $reviews = [
        [
            'resep_id' => 1,
            'user_id' => 1,
            'deskripsi' => 'Ayam gorengnya krispi dan juicy. Favorit banget!',
            'bintang' => 5,
            'foto' => null,
        ],
        [
            'resep_id' => 1,
            'user_id' => 2,
            'deskripsi' => 'Gorengannya renyah, bumbunya pas.',
            'bintang' => 4,
            'foto' => null,
        ],
        [
            'resep_id' => 2,
            'user_id' => 3,
            'deskripsi' => 'Rendang daging empuk dan gurih, cocok buat makan siang.',
            'bintang' => 5,
            'foto' => null,
        ],
        [
            'resep_id' => 2,
            'user_id' => 4,
            'deskripsi' => 'Bumbunya kuat banget, enak tapi agak pedas.',
            'bintang' => 4,
            'foto' => null,
        ],
        [
            'resep_id' => 3,
            'user_id' => 5,
            'deskripsi' => 'Nasi goreng sederhana tapi nikmat!',
            'bintang' => 5,
            'foto' => null,
        ],
        [
            'resep_id' => 3,
            'user_id' => 6,
            'deskripsi' => 'Kurang asin menurutku, tapi teksturnya oke.',
            'bintang' => 3,
            'foto' => null,
        ]
    ];

    private $community = [
        [
            'nama' => 'Komunitas Masak Sehat',
        ],
        [
            'nama' => 'Komunitas Pecinta Makanan Pedas',
        ],
        [
            'nama' => 'Komunitas Vegetarian',
        ],
        [
            'nama' => 'Komunitas Makanan Tradisional',
        ],
        [
            'nama' => 'Komunitas Kue dan Roti',
        ]
    ];

    private $communityUsers = [
        [
            "community_id" => 1,
            "user_id" => 1
        ],
        [
            "community_id" => 1,
            "user_id" => 2
        ],
        [
            "community_id" => 1,
            "user_id" => 3
        ],
        [
            "community_id" => 2,
            "user_id" => 1
        ],
        [
            "community_id" => 2,
            "user_id" => 4
        ],
        [
            "community_id" => 3,
            "user_id" => 2
        ],
        [
            "community_id" => 3,
            "user_id" => 3
        ],
        [
            "community_id" => 3,
            "user_id" => 5
        ],
        [
            "community_id" => 4,
            "user_id" => 1
        ],
        [
            "community_id" => 4,
            "user_id" => 4
        ],
        [
            "community_id" => 5,
            "user_id" => 2
        ],
        [
            "community_id" => 5,
            "user_id" => 5
        ]   
    ];

    private $favorites = [
        [
            'user_id' => 1,
            'recipe_id' => 1
        ],
        [
            'user_id' => 2,
            'recipe_id' => 2
        ],
        [
            'user_id' => 3,
            'recipe_id' => 3
        ],
        [
            'user_id' => 4,
            'recipe_id' => 4
        ],
        [
            'user_id' => 5,
            'recipe_id' => 5
        ]
    ];
    
    private $followers = [
        [
            'from_user_id' => 1,
            'to_user_id' => 2,
        ],
        [
            'from_user_id' => 1,
            'to_user_id' => 3,
        ],
        [
            'from_user_id' => 2,
            'to_user_id' => 1,
        ],
        [
            'from_user_id' => 2,
            'to_user_id' => 4,
        ],
        [
            'from_user_id' => 3,
            'to_user_id' => 1,
        ],
        [
            'from_user_id' => 4,
            'to_user_id' => 2,
        ],
        [
            'from_user_id' => 5,
            'to_user_id' => 1,
        ]
    ];

    public function run(): void
    {
        // Create communities first (they are referenced by users)
        foreach ($this->community as $item) {
            Community::create($item);
        }

        // Create users
        foreach ($this->users as $item) {
            User::create($item);
        }

        // Create recipes
        foreach ($this->Recipes as $item) {
            Recipe::create($item);
        }

        // Create ingredients
        foreach ($this->Ingredients as $item) {
            Ingredient::create($item);
        }

        // Create steps
        foreach ($this->Steps as $item) {
            Step::create($item);
        }

        // Create reviews
        foreach ($this->reviews as $item) {
            Review::create($item);
        }

        // Create community users (pivot table)
        foreach ($this->communityUsers as $item) {
            CommunityUser::create($item);
        }

        // Create favorites
        foreach ($this->favorites as $item) {
            Favorite::create($item);
        }

        // Create followers
        foreach ($this->followers as $item) {
            Follower::create($item);
        }

        // // Create test user (optional - you can remove this if not needed)
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
