<?php
Route::resource('parameter', '\App\Modules\Master\Parameter\Controller', [
    'names' => 'parameter',
]);

Route::get('parameter/download', '\App\Modules\Master\Parameter\Controllers\Controller@download')->name('parameter.download');
Route::get('parameter/import', '\App\Modules\Master\Parameter\Controllers\Controller@import')->name('parameter.import');
Route::post('parameter/import', '\App\Modules\Master\Parameter\Controllers\Controller@importPost')->name('parameter.import');

