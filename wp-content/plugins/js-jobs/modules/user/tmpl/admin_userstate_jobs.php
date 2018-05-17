<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<span class="js-admin-title"><?php echo __('User Stats Jobs', 'js-jobs') ?></span>
<?php
if (!empty(jsjobs::$_data[0])) {
    ?>  		
    <table id="js-table">
        <thead>
            <tr>
                <th class="left-row"><?php echo __('Name', 'js-jobs'); ?></th>
                <th><?php echo __('Company', 'js-jobs'); ?></th>
                <th><?php echo __('Category', 'js-jobs'); ?></th>
                <th><?php echo __('Start publishing', 'js-jobs'); ?></th>
                <th><?php echo __('Stop publishing', 'js-jobs'); ?></th>
                <th><?php echo __('Created', 'js-jobs'); ?></th>
                <th><?php echo __('Status', 'js-jobs'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $k = 0;
            for ($i = 0, $n = count(jsjobs::$_data[0]); $i < $n; $i++) {
                $row = jsjobs::$_data[0][$i];
                ?>
                <tr valign="top">
                    <td class="left-row"><?php echo $row->title; ?></td>
                    <td><?php echo $row->companyname; ?></td>
                    <td><?php echo $row->cat_title; ?></td>
                    <td><?php echo $row->startpublishing; ?></td>
                    <td><?php echo $row->stoppublishing; ?></td>
                    <td><?php echo date_i18n($this->config['date_format'], strtotime($row->created)); ?></td>
                    <td style="text-align: center;">
                        <?php
                        if ($row->status == 1)
                            echo "<font color='green'>" . $status[$row->status] . "</font>";
                        elseif ($row->status == -1)
                            echo "<font color='red'>" . $status[$row->status] . "</font>";
                        else
                            echo "<font color='blue'>" . $status[$row->status] . "</font>";
                        ?>
                    </td>
                </tr>
                <?php
                $k = 1 - $k;
            }
            ?>
        </tbody>
    </table>
    <?php
    if (jsjobs::$_data[1]) {
        echo '<div class="tablenav"><div class="tablenav-pages">' . jsjobs::$_data[1] . '</div></div>';
    }
} else {
    $msg = __('No record found','js-jobs');
    echo JSJOBSlayout::getNoRecordFound($msg);
}
?>