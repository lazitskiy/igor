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
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/app.css')); ?>">
</head>

<body>
<div class="container mt-5">

    <!-- Success message -->
    <?php if(Session::has('success')): ?>
        <div class="alert alert-success">
            <?php echo e(Session::get('success')); ?>

        </div>
    <?php endif; ?>

    <form action="" method="post" action="/">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label>Дата</label>
            <!-- Error -->
            <?php if($errors->has('date')): ?>
                <div class="error">
                    <?php echo e($errors->first('date')); ?>

                </div>
            <?php endif; ?>
            <?php if($apiErrors['getByDate'] ?? null): ?>
                <div class="error">
                    <?php echo e($apiErrors['getByDate']); ?>

                </div>
            <?php endif; ?>
            <input type="date" class="form-control" name="date" id="date" value="<?php echo e($weather['date_at'] ?? null); ?>">
        </div>
        <?php if($weather['temp'] ?? null): ?>
            Температура <?php echo e($weather['temp']); ?> градусов.
        <?php endif; ?>
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
        <?php $__currentLoopData = $last30DaysWeather; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($day['date_at']); ?></td>
                <td><?php echo e($day['temp']); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </tbody>
    </table>
</div>
</body>
</html>
<?php /**PATH /Users/vaso/htdocs/test_igor/site/resources/views/welcome.blade.php ENDPATH**/ ?>