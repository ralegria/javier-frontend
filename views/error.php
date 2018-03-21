<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Error page</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <link type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500" rel="stylesheet">
        <link href="assets/fonts/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet">
        <link type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--<link href="<?php echo self::$BASEDIR . self::$APPDIR['assets'] ?>/css/styles.css" type="text/css" rel="stylesheet">-->
    </head>
    <body>
        <div class = "container-fluid">
            <?php
            if(isset($notify)){
            ?>
            <div class="alert alert-dismissable <?php echo $notify['class'] ?>">
                <i class="fa <?php echo $notify['icon'] ?>"></i>&nbsp; <strong><?php echo $notify['notify_title'] ?></strong>
                <?php echo $notify['msg'] ?>
                <p><a class="btn btn-raised btn-info" href="<?php echo self::$BASEDIR ?>">Send me back!<div class="ripple-container"></div></a></p>
            </div>
            <?php } ?>
        </div>
        <?php echo $this->renderPartial('outputJs') ?>
    </body>
</html>