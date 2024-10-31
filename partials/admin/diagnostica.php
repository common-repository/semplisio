<?php
$semplisio_token = get_option('semplisio-token');
?>

<div id="semplisio" class="wrap diagnostica">
    <?php include \Semplisio\Init::$PLUGIN_DIR.'/partials/admin/menu.php'?>
    <h2><?php \Semplisio\Init::_t('Diagnostica Semplisio');?></h2>
    <div class="frame">
        <p>
            <?php \Semplisio\Init::_t('Controlla che i parametri di WooCommerce siano impostati correttamente.<br><br>'); ?>
           
        </p>
        
        <div class="check">
            <div class="permalink">
                <img class="wait" src="<?php echo \Semplisio\Init::$STATIC_URL.'wait.gif' ?>">
                &nbsp;
                <h3><?php \Semplisio\Init::_t('Permalink');?></h3>&nbsp;
                <h3 class="esitook"><?php \Semplisio\Init::_t('OK!'); ?></h3>
                <h3 class="esitoko"><?php \Semplisio\Init::_t('Fallito!'); ?></h3>
                <h5 class="esitook"><?php \Semplisio\Init::_t('I permalink del sito sono impostati correttamente');?></h5>
                <h5 class="esitoko"><?php \Semplisio\Init::_t('I permalink del sito non sono impostati correttamente'); ?></h5>
            </div>
            <div class="https">
                <img class="wait" src="<?php echo \Semplisio\Init::$STATIC_URL.'wait.gif' ?>">
                &nbsp;
                <h3><?php \Semplisio\Init::_t('Https');?></h3>&nbsp;
                <h3 class="esitook"><?php \Semplisio\Init::_t('OK!'); ?></h3>
                <h3 class="esitoko"><?php \Semplisio\Init::_t('Fallito!'); ?></h3>
                <h5 class="esitook"><?php \Semplisio\Init::_t('Sul sito è presente un certificato SSL valido');?></h5>
                <h5 class="esitoko"><?php \Semplisio\Init::_t('Sul sito non è presente un certificato SSL valido');?></h5>
            </div>
            <div class="api">
                <img class="wait" src="<?php echo \Semplisio\Init::$STATIC_URL.'wait.gif' ?>">
                &nbsp;
                <h3><?php \Semplisio\Init::_t('Api abilitate');?></h3>&nbsp;
                <h3 class="esitook"><?php \Semplisio\Init::_t('OK!');?></h3>
                <h3 class="esitoko"><?php \Semplisio\Init::_t('Fallito!');?></h3>
                <h5 class="esitook"><?php \Semplisio\Init::_t('La comunicazione con i server Semplisio è avvenuta correttamente');?></h5>
                <h5 class="esitoko"><?php \Semplisio\Init::_t('La comunicazione con i server Semplisio non è avvenuta correttamente');?></h5>
            </div>
            <div style="height:auto">
                    <a href="#" class="button button-primary procedi" disabled><?php \Semplisio\Init::_t('Procedi');?></a>
            </div>  
            <div class="clear"></div>
        </div>
        <div>
 <button type="button" id="check" class="button button-primary"><?php \Semplisio\Init::_t('Avvia Check');?></button>
       
        </div>
    </div>

    <p style="margin-top: 100px"><?php \Semplisio\Init::_t('Se i parametri non risultano impostati correttamente, consulta la guida online');?></p>            
    <p>
        <a target="_blank" class="button button-primary" href="<?php echo \Semplisio\Init::$STATIC_URL ?>guida-alla-connessione-con-woocommerce.pdf"><?php \Semplisio\Init::_t('Guida'); ?></a>
    </p>
</div>