<?php 
$id_user = (isset($this->request['id_user'])) ? $this->request['id_user'] : NULL ;
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <?php require_once "partials/_head.php";?>
        <?php require_once "partials/_outputJs.php";?>
        <script src="<?php echo $this->_helpers->linkTo("js/quill.js", "Assets")?>"></script>
        <script src="//www.jsviews.com/download/jsviews.min.js"></script>
        <link href="<?php echo $this->_helpers->linkTo("css/quill.css", "Assets")?>" type="text/css" rel="stylesheet" media="screen,projection"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/locale/es.js"></script>
        <title>Tienda - Kitec</title>
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
                        
                        <div class="row container" style="position: relative;top:45px;width: 128%;">
                            <p>Click sobre los productos para comprar</p>
                            <div class="col s12 m12 white z-depth-1" style="padding:20px;min-height: 36em;margin:0 auto;">
                                <ul id="result" class="collapsible" data-collapsible="accordion">
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!--  Scripts materialize-->
            
        <script id="theTmpl" type="text/x-jsrender">
            <li class="col s12 m3">
                <div onclick="pagarAhora('{{:name}}','{{:price}}');" class="white z-depth-1 tooltipped" data-position="bottom" data-delay="50" data-tooltip="I am a tooltip" style="width:100%;display:inline-block;min-height:247px;padding:10px 0.55rem;cursor:pointer;">
                    <div style="background:url(<?php echo $this->_helpers->linkTo("img/icons/cart.svg", "Assets")?>) 50% 50% rgb(222, 222, 222);background-size:55%;background-repeat:no-repeat;width:100%;height:10em;margin:0 0 20px;"></div>
                    <div style="float:left;line-height:1.3;width:100%;color:#5f5f5f;">
                        <span class="green-text" style="font-weight:500;">$ {{fix:price}}</span>
                        <span style="font-weight:500;">{{:name}}</span>
                        <div>{{:descrip}}</div>
                    </div>
                </div>
            </li>
        </script>
        <script src="https://checkout.stripe.com/checkout.js"></script>
        <script>
            var handler = StripeCheckout.configure({
                key: 'pk_test_0rznJAzBTzTQPfL8u4zcKyCr',
                image: '<?php echo $this->_helpers->linkTo("img/logo-mini.png", "Assets")?>',
                locale: 'auto',
                    token: function(token) {
                        // You can access the token ID with `token.id`.
                        // Get the token ID to your server-side code for use.
                    }
            });
            function pagarAhora(descrip, amount){
                // Open Checkout with further options:
                handler.open({
                    name: 'Kitec App',
                    description: descrip,
                    zipCode: true,
                    amount: amount
                });
                e.preventDefault();
            }
            // Close Checkout on page navigation:
            window.addEventListener('popstate', function() {
                handler.close();
            });
        </script>
        <script>
            //to fix the amount data
            
            $.views.converters("fix", function(val) {
                return (val/100).toFixed(2);
            });

            $.views.converters("time", function(val) {
                return moment(val).startOf('hour').fromNow();;
            });

            var id_user ="<?php echo $iduser;?>";
            var query = JSON.stringify({ "query": "{ paidUser(id_user:\""+ id_user +"\"){ coupon { id_coupon name descrip price } } }" });
            
            $.ajax({
                type: 'POST',
                url: "<?php echo $this->_helpers->linkToApi("")?>graphql/",
                data:   query,
                contentType: 'application/graphql'
            }).done(function (response) {
                console.log(response.data.paidUser);
                var data = response.data.paidUser.coupon;
                var template = $.templates("#theTmpl");
            
                template.link("#result", data); 
                $('.collapsible').collapsible();
            });
        </script>
    </body>

</html>