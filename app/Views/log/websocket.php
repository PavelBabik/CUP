<?php
/**
 * @var $logsList array
 * @var $pages array
 *
 * @var $Templates \App\Classes\Templates
 */
?>
<?=$Templates->render('common/head')?>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <?=$Templates->render('common/header')?>
    <div class="app-main">
        <?=$Templates->render('common/nav')?>
        <div class="app-main__outer">
            <div class="app-main__inner">

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="card widget-content">
                            <nav class="" aria-label="Page navigation example">
                                <ul class="pagination">
                                    <?php foreach ($pages as $key => $value): ?>
                                        <li class="page-item">
                                            <a href="/admin/log/websocket/<?=$key-1?>" class="page-link"><?=$key-1?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </nav>
                        </div>
                        <br>
                        <?php foreach ($logsList as $log): if ($log != null): ?>
                            <div class="card widget-content">
                                <div>
                                    <div class="datetime"><?=$log->datetime?></div>
                                    <br>
                                    <span class="<?=$log->color?>"><?=$log->message?></span>

                                    <div class="buttons">
                                        <?php if (isset($log->data)): ?>
                                            <button class="btn btn-primary toggle-body">DATA</button>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (isset($log->data)): ?>
                                        <div class="body"><pre><?=json_encode(json_decode($log->data), JSON_PRETTY_PRINT)?></pre></div>
                                    <?php endif; ?>

                                </div>
                            </div>
                            <br>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="app-wrapper-footer">
                <div class="app-footer">
                    <div class="app-footer__inner">
                        <div class="app-footer-left">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link">
                                        Footer Link 1
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link">
                                        Footer Link 2
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="app-footer-right">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link">
                                        Footer Link 3
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link">
                                        <div class="badge badge-success mr-1 ml-0">
                                            <small>NEW</small>
                                        </div>
                                        Footer Link 4
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>

<style>
    .datetime {
        font-size: 14px;
        color: black;
        font-weight: bold;
        opacity: 0.7;
    }

    .success {
        color: green;
    }
</style>
<script>
    $(document).ready(function () {
        $('.info').toggle();
        $('.header').toggle();
        $('.body').toggle();

        $('.toggle-info').click(function () {
            // if($('.header').is(':hidden'))
            // {
            //     $('#header').slideDown();
            // }
            $(this).parent().parent().find('.info').toggle();
            $(this).parent().parent().find('.header').toggle(false);
            $(this).parent().parent().find('.body').toggle(false);
        })

        $('.toggle-header').click(function () {
            $(this).parent().parent().find('.info').toggle(false);
            $(this).parent().parent().find('.header').toggle();
            $(this).parent().parent().find('.body').toggle(false);
        })

        $('.toggle-body').click(function () {
            $(this).parent().parent().find('.info').toggle(false);
            $(this).parent().parent().find('.header').toggle(false);
            $(this).parent().parent().find('.body').toggle();
        })

        $('#clear').click(function () {
            $.ajax('/log/' + window.location.pathname.split('/')[2] + '/clear').done(function () {
                location.reload();
            })
        })
    })
</script>

<script type="text/javascript" src="/assets/scripts/main.js"></script></body>
</html>
