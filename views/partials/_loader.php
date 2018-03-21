<div class="loader center-align white-text valign-wrapper" style="position:fixed;background-color: rgba(84, 65, 213, 1);">
    <div style="display:inline-block;width:40%;margin-left:auto;margin-right:auto;">
        <img src="<?php echo $this->_helpers->linkTo("img/logo.svg", "Assets")?>" class="animated swing infinite" style="width:30%;">
        <h5 style="font-size: 1.8rem;font-weight: 600;">Haciendo y deshaciendo se va aprendiendo</h5>
        <h5 style="font-size: 1.3rem;">- El equipo de kitec</h5>
    </div>
</div>
<script>
    $(window).ready(function() {
        setTimeout(function(){ $(".loader").fadeOut("slow"); }, 3000);
    });
</script>