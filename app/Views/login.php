<?php
/**
 * @var $error_msg
 *
 * @var $Templates \App\Classes\Templates
 */
?>

<?=$Templates->render('common/head')?>
<div class="app-container app-theme-white body-tabs-shadow">
    <div class="app-container">
        <div class="h-100 bg-plum-plate bg-animation">
            <div class="d-flex h-100 justify-content-center align-items-center">
                <div class="mx-auto app-login-box col-md-8">
                    <div class="app-logo-inverse mx-auto mb-3"></div>
                    <div class="modal-dialog w-100 mx-auto">
                        <form action="/admin/login" method="post" class="modal-content">
                            <div class="modal-body">
                                <div class="h5 modal-title text-center">
                                    <h4 class="mt-2">
                                        <div>Welcome back!</div>
                                        <span>Please sign in to your account below.</span>

                                        <?php if (isset($error_msg)): ?>
                                            <h2 style="color:red;"><?=$error_msg?></h2>
                                        <?php endif; ?>
                                    </h4>
                                </div>
                                <div class="">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <input name="login" id="exampleEmail" placeholder="Email here..." type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <input name="password" id="examplePassword" placeholder="Password here..." type="password" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer clearfix">
                                <div class="float-right">
                                    <button class="btn btn-primary btn-lg">Login to Dashboard</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="text-center text-white opacity-8 mt-3">Copyright © PVA Team</div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/assets/scripts/main.js"></script></body>
</html>