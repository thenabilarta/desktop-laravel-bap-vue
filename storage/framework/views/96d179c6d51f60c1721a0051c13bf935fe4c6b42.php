<?php $__env->startSection('content'); ?>

    <div class="block-header">
        <h2><?php echo app('translator')->getFromJson('dashboard::dashboard.module'); ?></h2>
    </div>

    <div class="row dashboard-row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div id="app">
                    <index></index>
                </div>
            </div>
        </div>

    </div>
    
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css-up'); ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
    <link
    rel="stylesheet"
    href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css"
    />

<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>

    <script src="<?php echo Module::asset('media:js/media.js'); ?>"></script>

<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>

    <script src="<?php echo Module::asset('dashboard:js/BAP_Dashboard.js'); ?>"></script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>