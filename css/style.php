<?php
    ob_start();
    header('Content-type: text/css'); 
?>

.random-article-wrapper .column {
    width: <?php echo $columnWidth; ?>%;
    margin-right: <?php echo $columnMargin; ?>px;
    float: left;
}
        
.random-article-wrapper .column:last-child { margin-right: auto; }
    
.random-article-wrapper .clearfix { clear: both; }
        
<?php if($params->get('customCss')): ?>
    .random-article .title h<?php echo $params->get('styleTitle'); ?> <?php if($params->get('linktitle')) echo "a"; ?> {
        color: <?php echo $params->get('styleTitleColor'); ?>;
    }
    
    <?php if($params->get('linktitle')): ?>
        .random-article .title h<?php echo $params->get('styleTitle'); ?> a:hover {
            color: <?php echo $params->get('styleTitleColorOver'); ?>;
        }
    <?php endif; ?>
    
    <?php if($params->get('readmore')): ?>
        .random-article .readmore a {
            color: <?php echo $params->get('styleReadmoreColor'); ?>;
        }
    
        .random-article .readmore a:hover {
            color: <?php echo $params->get('styleReadmoreColorOver'); ?>;
        }
    <?php endif; ?>        
<?php endif; ?>

<?php
    $output = ob_get_contents();
    ob_end_clean();
    $fp = fopen(JPATH_ROOT.DS.'modules'.DS.'mod_random-article'.DS.'css'.DS.'style.css','w');
    fwrite($fp,$output);
    fclose($fp);
?>