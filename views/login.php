<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once "partials/_head.php";?>
    <title>Login | KITEC</title>
</head>

<body style="overflow-x:hidden;">
    <main>
        <div class="row">
            <div class="container" style="position: relative;top:45px;">
                <div class="col s12 m6 offset-m3 z-depth-1" style="background: #FFF;padding:20px 35px;border-radius:7px;">
                    <div class="row s12 m6 center-align" style="padding:0;">
                        <img src="<?php echo $this->_helpers->linkTo("img/logo-dark.png", "Assets")?>" class="animated rubberBand" style="width:55%;">
                    </div>
                    <div class="row s12 m6" style="margin-bottom:0px;padding-left: 0;">
                        <input type="text" id="ussr_fld" name="ussrld" class="ussr_fld" placeholder="correo electronico"/>
                    </div>
                    <div class="row s12 m6" style="margin-bottom: 18px;padding-left: 0;">
                        <input type="password" id="pss_fld" name="pssld" class="pss_fld" placeholder="contrase&ntilde;a"/>
                    </div>
                    <div class="row s12 m6" style="padding:0;">
                        <a class="send btn btn-flat waves-effect waves-light white-text" onclick="login();" data-view="mobile-login" style="width:100%;background-color:#ff1d6e;text-transform: none">Login para entrar</a>
                    </div>
                    <div class="row s12 m6 center-align" style="padding:0;margin-top:10px;">
                        <span>&iquest;Qu&eacute; es kitec? </span><a class="text-lighten-3" style="font-weight:500;color:#000 !important;text-decoration:underline;">Conoce m&aacute;s</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require_once "partials/_footer.php";?>
    <!--  Scripts-->
    <?php require_once "partials/_outputJs.php";?>
    <script>
        function login() {
            var email = $('#ussr_fld').val();
            var password = $('#pss_fld').val();
            var query = JSON.stringify({ "query": "{ Login(email:\""+ email +"\",password:\""+ password +"\"){ id_user name lastname } }" });
            $.ajax({
                type: 'POST',
                url: "<?php echo $this->_helpers->linkToApi("")?>graphql/",
                data:   query,
                contentType: 'application/graphql'
            }).done(function (response) {
                if(!(response.hasOwnProperty('errors'))){
                    var data = { id_user: response.data.Login.id_user }
                    redirect(data);
                }else{
                    logMessages('send','shake','#ff005e','<i class="material-icons left">error_outline</i>Correo o contrase&ntilde;a incorrecta','Entrar');
                }
            });
        }
        function redirect(user) {
            $.ajax({
                url: '<?php echo $this->_helpers->linkTo('profile/login/');?>',
                dataType: 'json',
                type: 'post',
                data:   user,
                success: function (dt) {
                    if (dt.message == 'success') {
                        $(location).attr('href', '<?php echo $this->_helpers->linkTo('');?>');
                    }
                }
            });
        }
    </script>
</body>

</html>