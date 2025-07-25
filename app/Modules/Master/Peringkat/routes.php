<?php
Route::resource('peringkat', '\App\Modules\Master\Peringkat\Controller', [
    'names' => 'peringkat',
]);

Route::get('peringkat/download', '\App\Modules\Master\Peringkat\Controllers\Controller@download')->name('peringkat.download');
Route::get('peringkat/import', '\App\Modules\Master\Peringkat\Controllers\Controller@import')->name('peringkat.import');
Route::post('peringkat/import', '\App\Modules\Master\Peringkat\Controllers\Controller@importPost')->name('peringkat.import');
