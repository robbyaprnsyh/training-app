<?php

Route::get('laporan/daftarunitkerja',
'\App\Modules\Laporan\Daftarunitkerja\Controller@index')->name('laporan.daftarunitkerja');

Route::get('laporan/daftarunitkerja/data',
'\App\Modules\Laporan\Daftarunitkerja\Controller@data')->name('laporan.daftarunitkerja.data');
