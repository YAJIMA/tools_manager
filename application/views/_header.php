<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $title; ?></title>
    <!-- Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <!-- Bootswatch 4 https://www.bootstrapcdn.com/bootswatch/ -->
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.1.1/flatly/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        function dropsort() {
            var browser = document.sort_form.sort.value;
            location.href = browser
        }
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo base_url('home');?>">Tools</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav mr-auto">
                <?php if (isset($menues['tool_item'])) : ?>
                <?php foreach ($menues['tool_item'] as $item) : ?>
                <li class="nav-item <?php if ($item['active'] == "active") : ?>active<?php endif; ?>">
                    <a class="nav-link" href="<?php echo $item['href']; ?>">
                        <?php echo $item['text']; ?>
                        <?php if ($item['active'] == "active") : ?><span class="sr-only">(current)</span><?php endif; ?>
                    </a>
                </li>
                <?php endforeach; ?>
                <?php endif; ?>
                <!-- TODO: サイトの切り替えメニューを作成 -->
                <?php if (isset($site_menues['site_item'])) : ?>
                    <li class="nav-item">
                        <?php echo form_open('',array('class'=>'form-inline','name'=>'sort_form')); ?>
                        <select name="sort" class="form-control" onchange="dropsort();">
                            <option value="0">サイトを選択</option>
                            <?php foreach ($site_menues['site_item'] as $site) : ?>
                                <option value="<?php echo $site['href']; ?>" <?php if ($site['active'] == "active") : ?>selected<?php endif; ?>><?php echo $site['text']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo form_close(); ?>
                    </li>
                <?php endif; ?>
            </ul>
            <?php if (isset($this->session->username)) : ?>
                <span class="navbar-text">
                    <?php echo $this->session->username; ?>
                </span>
            <?php endif; ?>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?php echo base_url('login/out'); ?>">ログアウト</a>
                </li>
            </ul>
            <!--
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="text" placeholder="Search">
                <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
            </form>
            -->
        </div>
    </div>
</nav>
<div class="container-fluid">
