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
    ],

    // Mapping: Role → Course Key (1-to-1, karena tiap ketua pegang 1 matkul)
    'course_mapping' => [
        'ketua_struktur_data' => 'struktur_data',
        'ketua_algo'          => 'algoritma_pemrograman',
        'ketua_jarkom'        => 'jaringan_komputer',
        'ketua_matdis'        => 'matematika_diskrit',
        'ketua_basda'         => 'sistem_basis_data',
        'ketua_statistika'    => 'statistika',
        'ketua_tbo'           => 'teori_bahasa_otomata',
        'ketua_teori_graf'    => 'teori_graf',
    ],

    // 🔹 Daftar nama mata kuliah (untuk display di UI)
    'courses' => [
        'struktur_data'         => 'Struktur Data',
        'algoritma_pemrograman' => 'Algoritma dan Pemrograman',
        'jaringan_komputer'     => 'Jaringan Komputer',
        'matematika_diskrit'    => 'Matematika Diskrit',
        'sistem_basis_data'     => 'Sistem Basis Data',
        'statistika'            => 'Statistika',
        'teori_bahasa_otomata'  => 'Teori Bahasa dan Otomata',
        'teori_graf'            => 'Teori Graf',
    ]
];