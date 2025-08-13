<?php
Route::resource('penilaian', '\App\Modules\Master\Penilaian\Controller', [
    'names' => 'penilaian',
]);

Route::get('penilaian/download', '\App\Modules\Master\Penilaian\Controllers\Controller@download')->name('penilaian.download');
Route::get('penilaian/import', '\App\Modules\Master\Penilaian\Controllers\Controller@import')->name('penilaian.import');
Route::post('penilaian/import', '\App\Modules\Master\Penilaian\Controllers\Controller@importPost')->name('penilaian.import');

