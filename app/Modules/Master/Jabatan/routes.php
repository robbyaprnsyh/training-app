<?php
Route::resource('jabatan', '\App\Modules\Master\Jabatan\Controller', [
    'names' => 'jabatan'
]);

Route::get('jabatan/download', '\App\Modules\Master\Jabatan\Controllers\Controller@download')->name('jabatan.download');
Route::get('jabatan/import', '\App\Modules\Master\Jabatan\Controllers\Controller@import')->name('jabatan.import');
Route::post('jabatan/import', '\App\Modules\Master\Jabatan\Controllers\Controller@importPost')->name('jabatan.import');
