<?php
$semplisio_token = get_option('semplisio-token');
?>

<div id="semplisio" class="wrap attivazione">
    <?php include \Semplisio\Init::$PLUGIN_DIR.'/partials/admin/menu.php'?>
    <h2><?php \Semplisio\Init::_t('Attivazione Semplisio'); ?></h2>
    <div class="frame">
        <form method="POST">
            <p><?php \Semplisio\Init::_t('Inserisci qui il <b>Token</b> ricevuto via email all\'attivazione di Semplisio'); ?></p>
            <input type="hidden" name="action" value="salva-attivazione">
            <input type="text" class="bordered" placeholder="Token" name="semplisio-token" onchange="jQuery('#check_procedi').prop('disabled', true)" value="<?php $semplisio_token; ?>"><br/>
        
            <?php if(!empty($msg)){ ?>
            <h5><?php echo esc_html($msg); ?></h5>
            <?php } else {?>
            <br>
            <?php } ?>
            <div>
            <button type="submit" class="button button-primary salva"><?php \Semplisio\Init::_t('Salva');?></button>
            <button type="button" id="check_procedi" onclick="location.href='/wp-admin/admin.php?page=semplisio&tab=diagnostica'" 
                class="button button-primary  procedi" <?php if(empty($semplisio_token)) echo 'disabled';?>>
                <?php \Semplisio\Init::_t('Procedi');?>
            </button>
            </div>
        </form>
    </div>

</div>