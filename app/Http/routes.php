<?php
Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '/api/v1', 'middleware' => 'auth.fgx'], function () {
    Route::post('/messages/email/send', function () {
        return (new \App\Http\Controllers\TransactEmailSend)->send();
    });

    Route::post('/messages/email/bulk/send', function () {
        return (new \App\Http\Controllers\BulkMailSend)->send();
    });

    Route::get(
        '/messages/email/report/{message_id}/{report_type?}/{start_date?}/{end_date?}',
        function ($message_id, $report_type = 'overView', $start_date = 0, $end_date = 0) {
            return (new \App\Http\Controllers\EmailReports($message_id))->showReport($report_type, $start_date, $end_date);
        }
    );
});

Route::post('/messages/email/events', function () {
    return (new \App\Http\Controllers\SendGridEventProcessor)->process();
});
