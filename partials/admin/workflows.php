<div id="semplisio" class="wrap workflows">
    <?php include \Semplisio\Init::$PLUGIN_DIR.'/partials/admin/menu.php'?>
    <h2><?php \Semplisio\Init::_t('Workflows Semplisio');?></h2>
    <div class="frame">
        <p><?php \Semplisio\Init::_t('Attiva o disattiva i <b>workflows</b>'); ?></p>
        <form method="POST">         
            <input type="hidden" name="action" value="salva-workflows">
            <div class="step1">
                <p><b><?php \Semplisio\Init::_t('Prodotti'); ?></b></p>
               
                
                <label class="toggle">
                    <input type="checkbox" <?php if(!$options['allowed_services']->products_to_wp_all) echo 'disabled';?> id="import-all" name="import-all" class="semplisio-import toggle-checkbox" value="1" <?php if(isset($dati['all']) && $dati['all']) echo 'checked';?>> 
                    <div class="toggle-switch"></div>
                    <span class="toggle-label"><?php \Semplisio\Init::_t('Importare tutti i prodotti dal gestionale'); ?></span>
                </label><br><br>

                <label class="toggle">
                    <input data-s="<?php echo json_encode($options['allowed_services']);?>" type="checkbox" <?php if(!$options['allowed_services']->products_to_wp_filter) echo 'disabled';?> id="import-partial" name="import-partial" class="semplisio-import toggle-checkbox" value="2" <?php if(isset($dati['partial']) && $dati['partial']) echo 'checked';?>> 
                    <div class="toggle-switch"></div>
                    <span class="toggle-label"><?php \Semplisio\Init::_t('Importare una selezione di prodotti'); ?></span>
                </label><br><br>

                
                <!--<label class="filtro <?php if(!$dati['partial']) echo 'hidden' ?>"><?php \Semplisio\Init::_t('Filtro prodotti'); ?></label><br>-->
                <input type="hidden" id="semplisio-filtro-prodotti" class="filtro <?php if(!$dati['partial']) echo 'hidden' ?>" name="semplisio-filtro-prodotti" value="<?php echo $dati['filtro-prodotti']; ?>">
                
                <p><b><?php \Semplisio\Init::_t('Ordini');?></b></p>
                <label class="toggle">
                    <input type="checkbox" <?php if(!$options['allowed_services']->orders_to_erp->store && !$options['allowed_services']->orders_to_erp->update) echo 'disabled';?> class="toggle-checkbox" id="import-ordini" name="import-ordini" <?php if(isset($dati['ordini']) && $dati['ordini']) echo 'checked';?>> 
                    <div class="toggle-switch"></div>
                    <span class="toggle-label"><?php \Semplisio\Init::_t('Trasmetti ordini da ecommerce a gestionale (in tempo reale)');?></span>
                </label><br><br>
                <p class="nota-all <?php if(!$dati['all']) echo 'hidden';?>">
                    <?php \Semplisio\Init::_t('Nota Bene: Prima di attivare <i>Importa tutti i prodotti dal gestionale</i>, assicurati che i prodotti presenti sul gestionale siano scritti correttamente. Ricorda che questa opzione importa TUTTI i prodotti e le informazioni ad essi collegati. Se vuoi importare solo alcuni prodotti, seleziona <i>Importa una selezione di prodotti</i>');?>
                </p>
                <p class="nota-partial <?php if(!$dati['partial']) echo 'hidden';?>">
                <?php \Semplisio\Init::_t('Nota Bene: Per attivare questa opzione, è necessario settare sul tuo gestionale la seguente stringa del campo Note: <b>Woocommerce</b>');?>

                </p>

                <div class="prodotti-ora <?php if(!$dati['all'] && !$dati['partial']) echo 'hidden'; ?>" <?php if(!$dati['all'] && !$dati['partial']) echo 'hidden' ?>>
                    <label><?php \Semplisio\Init::_t('Orario di avvio sincronizzazione');?></label><br><br>
                    <select id="import-prodotti-ora" name="import-prodotti-ora" class="" required <?php if(!$dati['all'] && !$dati['partial']) echo 'disabled'; ?>>
                        <option value=""></option>
                        <?php 
                        if($options['sync_type_options']=='giornaliera'){
                            $list = $options['sync_time_options'];
                        }
                        if($options['sync_type_options']=='oraria'){
                            $list = $options['sync_frequency_options'];
                        }
                        foreach($list as $opt) { ?>
                        <option value="<?php echo esc_attr($opt); ?>" <?php if($opt==$dati['ora']) echo 'selected'; ?>><?php echo esc_attr((intval($opt)>60 ? $opt/60 : $opt)); ?></option>
                        <?php } ?>
                    </select>
                </div>
                
                <br><br>
                <button type="submit" class="button button-primary"><?php \Semplisio\Init::_t('Salva');?></button>                
                <?php if(!empty($msg)){ ?>
                <h5><?php echo wp_kses($msg, wp_kses_allowed_html(  )); ?></h5>
                <?php } else {?>
                <br>
                <?php } ?>
                
            </div>
            
        </form>
    </div>
    
</div>