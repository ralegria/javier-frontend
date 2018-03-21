<?php 
$id_user = (isset($this->request['id_user'])) ? $this->request['id_user'] : NULL ;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once "partials/_head.php";?>
    <link href="<?php echo $this->_helpers->linkTo("css/quill.css", "Assets")?>" type="text/css" rel="stylesheet" media="screen,projection"/>
    <title>Kitec - Crecer en la web es f&aacute;cil</title>
    <style>
        .StripeElement {
            background-color: white;
            height: 40px;
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid transparent;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>
</head>

<body style="overflow-x:hidden;">
    <?php require_once "partials/_menu_sign.php";?>
    <main>
        <div class="row">
            <div class="col s12 m12">
                <div class="row">
                    <div class="container" style="position: relative;top:45px;width: 95%;">
                        <div class="col s12 m6 offset-m3 z-depth-1" style="background: #FFF;padding:20px 35px;border-radius:7px;">
                            <div class="bill-fields">
                                <div class="col s12 m12" style="margin-bottom:0px;padding-left: 0;">
                                    <label for="bill-title">Cantidad ($)</label>
                                    <input type="text" id="amount" class="name_user" placeholder="0.00" />
                                </div>
                                <div class="col s12 m12" style="margin-bottom:0px;padding-left: 0;">
                                    <label for="bill-title">Titulo</label>
                                    <input type="text" id="bill-title" class="name_user" placeholder="Mi producto" />
                                </div>
                                <div class="col s12 m12" style="margin-bottom:20px;padding: 0;">
                                    <label for="bill-details">Detalles</label>
                                    <div id="bill-details" class="col s12" style="color: #7a69ec;"/></div>
                                </div>
                                <div class="col s12 m12" style="padding:0;">
                                    <a class="send btn btn-flat waves-effect waves-light white-text right" onclick="createCoupon();" style="padding: 0px 25px;background-color:#5441d5;">Crear ticket</a>
                                    <a class="send btn btn-flat waves-effect waves-dark white grey-text right" style="padding: 0px 25px;margin-right:10px;">Cancelar</a>
                                </div>
                                <div class="col s12 m12" style="padding:0;margin:10px 0;">
                                    <hr/>
                                </div>
                                <div class="col s12 m12 center-align" style="padding:0;margin-top:10px;">
                                    <span>&iquest;Necesitas ayuda? </span><a class="text-lighten-3" style="font-weight:500;color:#000 !important;text-decoration:underline;">Dej&aacute;nos un mensaje</a>
                                </div>
                            </div>
                            <div class="bill-link-fields" style="display:none;">
                                <div class="col s12 m12 center-align" style="margin-bottom:0px;padding-left: 0;">
                                    <i class="material-icons green-text" style="font-size:5rem;">done_all</i><br>
                                    <h5>Producto creado</h5>
                                </div>
                                <div class="col s12 m12 center-align" style="padding:0;margin-top:20px;">
                                    <a href="<?php echo $this->_helpers->linkTo("tienda/catalogo/".$id)?>" class="btn btn-flat green white-text waves-effect waves-light" style="padding: 0px 25px;">Ir a mi tienda</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--  Scripts-->
    <?php require_once "partials/_outputJs.php";?>
    <script src="<?php echo $this->_helpers->linkTo("js/quill.js", "Assets")?>"></script>
    <script>
        $(document).ready(function() {
            var quill = new Quill('#bill-details', {
                modules: {
                    toolbar: false
                },
                placeholder: 'Al pagar este ticket, recibes:',
                theme: 'bubble' // or 'bubble'
            });
            $('#bill-details .ql-editor').attr('style', 'padding: 15px 0 !important;outline:none;');
        });
        function isNumberKey(evt, element) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode != 46 && charCode > 31 &&
                (charCode < 48 || charCode > 57))
                return false;
            else {
                var len = $(element).val().length;
                var index = $(element).val().indexOf('.');
                if (index > 0 && charCode == 46) {
                    return false;
                }
                if (index > 0) {
                    var CharAfterdot = (len + 1) - index;
                    if (CharAfterdot > 3) {
                        return false;
                    }
                }

            }
            return true;
        }
        function createCoupon() {
            var title = $('#bill-title').val();
            var details = $('#bill-details .ql-editor').html();
            var amount = parseInt($('#amount').val() * 100);
            console.log(details);
            var id_paiduser = "<?php echo $id;?>";
            var query = JSON.stringify({ "query": "{CreateCoupon(name:\""+ title +"\",descrip:\""+ details +"\",price:"+ amount +", id_paiduser:\"" +id_paiduser + "\") { id_coupon name descrip price}}" });
            $.ajax({
                type: 'POST',
                url: "<?php echo $this->_helpers->linkToApi("")?>graphql/",
                data:   query,
                contentType: 'application/graphql'
            }).done(function (response) {
                var link = '<?php echo $this->_helpers->linkTo("")?>bill/pay/'+response.data.CreateCoupon.id_coupon;
                $('.bill-link-fields').removeAttr('style');
                $('.bill-fields').attr('style','display:none');
                $('#bill-link').val(link);
                console.log("ticket creado por:" + id_paiduser );
                console.log(response);
            });
        }
        </script>
</body>

</html>