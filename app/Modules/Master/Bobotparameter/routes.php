<?php
Route::resource('bobotparameter', '\App\Modules\Master\Bobotparameter\Controller', [
    'names' => 'bobotparameter',
]);

Route::get('bobotparameter/download', '\App\Modules\Master\Bobotparameter\Controllers\Controller@download')->name('bobotparameter.download');
Route::get('bobotparameter/import', '\App\Modules\Master\Bobotparameter\Controllers\Controller@import')->name('bobotparameter.import');
Route::post('bobotparameter/import', '\App\Modules\Master\Bobotparameter\Controllers\Controller@importPost')->name('bobotparameter.import');
    