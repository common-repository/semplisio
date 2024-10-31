<?php
    $diagnostica = esc_attr((!empty(get_option( 'semplisio-token', '' )) ? '/wp-admin/admin.php?page=semplisio&tab=diagnostica' : '#'));
    $impostazioni = esc_attr(($diagnostica!='#' && !empty(get_option( 'semplisio-connector', '' )) ? '/wp-admin/admin.php?page=semplisio&tab=impostazioni' : '#'));
    $workflows = esc_attr(($impostazioni!='#' && !empty(get_option( 'semplisio-gestionale', '' )) ? '/wp-admin/admin.php?page=semplisio&tab=workflows' : '#'));
    
?>


<div>
    <ul class="menu">
        <li><a href="/wp-admin/admin.php?page=semplisio&tab=attivazione"><?php \Semplisio\Init::_t('Attivazione'); ?></a></li>
        <li><a href="<?php echo $diagnostica; ?>"><?php \Semplisio\Init::_t('Diagnostica'); ?></a></li>
        <li><a href="<?php echo $impostazioni; ?>"><?php \Semplisio\Init::_t('Impostazioni'); ?></a></li>
        <li><a href="<?php echo $workflows; ?>"><?php \Semplisio\Init::_t('Workflows'); ?></a></li>
    </ul>
</div>