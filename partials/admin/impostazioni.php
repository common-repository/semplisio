<div id="semplisio" class="wrap impostazioni">
    <?php include \Semplisio\Init::$PLUGIN_DIR.'/partials/admin/menu.php'?>
    <h2>Impostazioni Semplisio</h2>
    <form method="POST">
    <div class="frame">
        
        <input type="hidden" name="action" value="salva-gestionale">
            
        <label><?php \Semplisio\Init::_t('Seleziona il <b>gestionale</b> a cui vuoi connetterti');?></label><br>
        <select id="semplisio-gestionale" name="semplisio-gestionale" required>
            <option value=""></option>
        <?php 
        $hiddenverifica = ' hidden ';
        foreach($gestionali->erps as $val) {?>
            
            <option <?php if($dati['g']==$val->platform_id) echo 'selected'; ?> value="<?php echo esc_attr($val->platform_id);?>"><?php echo esc_html($val->label); ?></option>
        
        <?php 
            if($dati['g']==$val->platform_id){
                $hiddenverifica = '';
            }
        } ?>
        </select><br><br>
        <?php 
        foreach($gestionali->erps as $val) {
            $key = $val->platform_id;
            $hidden = ($dati['g']==$key ? '' : ' hidden ');
            $disabled = ($dati['g']==$key ? '' : ' disabled ');
            echo '<div class="form-semplisio-gestionale '.esc_attr($key.$hidden).'">';
            foreach($val->connector_fields as $cf){
                $value = ($dati['g']==$key && isset($dati[$cf->key]) ? $dati[$cf->key] : '');
                echo '<label>'.esc_html($cf->label).'</label><br>';
                echo '<input type="text" name="semplisio-'.esc_attr($cf->key).'" value="'.esc_attr($value).'" required '.$disabled.'><br/><br/>';
            }
            echo '</div>';
            
        } ?>
        <?php if(!empty($msg)){ ?>
        <h5><?php echo esc_html($msg); ?></h5>
        <?php } else {?>
        <br>
        <?php } ?>
        <button type="button" id="scegli-gestionale" class="button button-primary <?php echo esc_attr($hidden);?>"><?php \Semplisio\Init::_t('Scegli');?></button>
        <button type="button" id="verifica-gestionale" class="button button-primary <?php echo esc_attr($hiddenverifica);?>"><?php \Semplisio\Init::_t('Connetti');?></button>
        <p class="errormsg"><?php \Semplisio\Init::_t('La connessione non &egrave; andata a buon fine. Controlla i parametri inseriti e riprova'); ?></p>
        <button type="submit" id="salva-campi" class="button button-primary bottom procedi" disabled>
            <?php \Semplisio\Init::_t('Procedi');?>
        </button>
            <!--<button type="submit" id="salva-campi" class="button button-primary hidden submit"><?php \Semplisio\Init::_t('Salva');?></button>-->
    </div>
    
    </form>
</div>