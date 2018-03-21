<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<!--<script src="<?php /*echo self::$BASEDIR . self::$APPDIR['assets'] */?>js/bootstrap.min.js"></script>-->
<script src="<?php echo $this->_helpers->linkTo("js/materialize.js", "Assets")?>"></script>
<script src="<?php echo $this->_helpers->linkTo("js/init.js", "Assets")?>"></script>
<script>
    /*if (window.location.protocol == 'http:'){
        window.location.href = "https://" + window.location.host+window.location.pathname;
    }*/
    var rootLocation = $(location).attr('protocol')+"//"+$(location).attr('host')+"/";
    $(document).ready(function(){
        $('.tooltipped').tooltip({delay: 50});
        $('.parallax').parallax();
        $('.dropdown-button').dropdown();
        $('#amount').bind('copy paste', function (e) {
            e.preventDefault();
        });
      });
</script>
