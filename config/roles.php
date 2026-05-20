<?php

return [
    // Key (disimpan di DB) => Value (ditampilkan di UI)
    'list' => [
        'ketua_struktur_data'        => 'Ketua Struktur Data',
        'ketua_algo'                 => 'Ketua Algoritma dan Pemrograman',
        'ketua_jarkom'               => 'Ketua Jaringan Komputer',
        'ketua_matdis'               => 'Ketua Matematika Diskrit',
        'ketua_basda'                => 'Ketua Sistem Basis Data',
        'ketua_statistika'           => 'Ketua Statistika',
        'ketua_tbo'                  => 'Ketua Teori Bahasa dan Otomata',
        'ketua_teori_graf'           => 'Ketua Teori Graf',
    ],

    // Role yang dianggap "basic user" untuk permission
    'basic_roles' => [
        'ketua_struktur_data', 'ketua_algo', 'ketua_jarkom', 'ketua_matdis',
        'ketua_basda', 'ketua_statistika', 'ketua_tbo', 'ketua_teori_graf'
    ]
];