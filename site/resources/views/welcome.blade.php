<?php

use Illuminate\Support\ViewErrorBag;

/**
 * @var array $last30DaysWeather
 * @var float $weather
 * @var ViewErrorBag $errors
 * @var array $apiErrors
 */

?>

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
</head>

<body>
<div class="container mt-5">

    <!-- Success message -->
    @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
    @endif

    <form action="" method="post" action="/">
        @csrf
        <div class="form-group">
            <label>Дата</label>
            <!-- Error -->
            @if ($errors->has('date'))
                <div class="error">
                    {{ $errors->first('date') }}
                </div>
            @endif
            @if ($apiErrors['getByDate'] ?? null)
                <div class="error">
                    {{ $apiErrors['getByDate'] }}
                </div>
            @endif
            <input type="date" class="form-control" name="date" id="date" value="{{ $weather['date_at'] ?? null }}">
        </div>
        @if ($weather['temp'] ?? null)
            Температура {{ $weather['temp'] }} градусов.
        @endif
        <input type="submit" name="send" value="Отправить" class="btn btn-dark btn-block">
    </form>

    <br/>
    <br/>
    <h2>Погода за последние 30 дней</h2>
    <table data-toggle="bootstrap-table" class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Дата</th>
            <th>Погода</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($last30DaysWeather as $day)
            <tr>
                <td>{{ $day['date_at'] }}</td>
                <td>{{ $day['temp'] }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
</body>
</html>
