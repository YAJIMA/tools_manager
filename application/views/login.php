<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Login</title>
    <!-- Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <h1>ログインしてください</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-12 offset-lg-3">
            <div class="jumbotron">
                <?php echo validation_errors('<div class="alert alert-danger" role="alert">', '</div>'); ?>
                <?php echo form_open('login/in','role="form"'); ?>
                    <div class="form-group">
                        <label for="username" class="control-label">ユーザー名</label>
                        <input type="text" name="inputUserName" id="inputUserName" class="form-control" placeholder="ユーザ名" value="<?php echo set_value('inputUserName'); ?>" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">パスワード</label>
                        <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="パスワード" value="<?php echo set_value('inputPassword'); ?>" required>
                    </div>
                    <div class="form-group">
                        <?php if (isset($return_uri)) : ?>
                            <input type="hidden" name="return_uri" value="{$return_uri}">
                        <?php endif; ?>
                        <button type="submit" name="mode" value="login" class="btn btn-primary">ログイン</button>
                    </div>
                </form>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<pre><?php echo var_dump($_SESSION); ?></pre>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js" integrity="sha384-u/bQvRA/1bobcXlcEYpsEdFVK/vJs3+T+nXLsBYJthmdBuavHvAW6UsmqO2Gd/F9" crossorigin="anonymous"></script>
</body>
</html>