<nav class="white">
    <div class="nav-wrapper">
        <a href="<?php echo $this->_helpers->linkTo("")?>" class="brand-logo left center-align" style="display:inline-block;width:15.5%;">
            <img src="<?php echo $this->_helpers->linkTo("img/logo-dark.png", "Assets")?>" style="position:relative;top:9px;width:80%;">
        </a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="<?php echo $this->_helpers->linkTo("tienda/catalogo/".$id)?>">Mi tienda</a></li>
            <li><a href="<?php echo $this->_helpers->linkTo("ticket/create")?>">Crear nuevo</a></li>
            <li><a href="<?php echo $this->_helpers->linkTo('profile/logout');?>">Salir</a></li>
        </ul>
    </div>
</nav>