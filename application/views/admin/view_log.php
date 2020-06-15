<?php
$calendar = new PN_Calendar();
echo $calendar->draw();
?>
<main role="main">
    <div class="jumbotron">
        <div class="container">
            <center>
                <h2 class="display-3">Log</h2>
            </center>
        </div>
    </div>
    <div class="container">
        <?php
        if (isset($message_display)) {
            echo "<div class='alert alert-success' role='alert'>";
            echo $message_display . '</div>';
        }
        ?>
        <nav aria-label="Checklist navigation">
            <?php if (isset($links)) {
                echo $links;
            } ?>
        </nav>
        <?php if (isset($results)) { 
            $days_count = date('t');
            $current_day = date('d');
            $week_day_first = date('N', mktime(0, 0, 0, date('m'), 1, date('Y')));
            ?>
                <table>
        <tr>
            <th>MO</th>
            <th>TU</th>
            <th>WE</th>
            <th>TH</th>
            <th>FR</th>
            <th style="color: red;">SU</th>
            <th style="color: red;">SA</th>
        </tr>
        <?php for ($w = 1 - $week_day_first + 1; $w <= $days_count; $w = $w + 7) : ?>
            <tr>
                <?php $counter = 0; ?>
                <?php for ($d = $w; $d <= $w + 6; $d++) : ?>
                    <td class="td_current" style="float: initial;<?php if ($counter > 4){ echo "color: red;";} if ($current_day == $d) { echo "background-color:yellow; color:green; font-weight:bold;";}?>">
                        <?php echo ($d > 0 ? ($d > $days_count ? '' : $d) : '') ?>
                    </td>
                    <?php $counter++; ?>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>
        <?php } else { ?>
            <div>No trashed checklist(s) found.</div>
        <?php } ?>
    </div>
    <div id='show-log' style='display:none;'>
        <div id="show-log-header">
            <div id="serial-header"></div>Click here to move<button type="button" class="close" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        </div>
        <ul class="list-group list-group-flush">
        </ul>
    </div>

</main>
<script>
    function restoreChecklist(id, project) {
        var r = confirm("Restore checklist with id: " + id + "?");
        if (r == true) {
            $.post("/admin/restoreChecklist", {
                id: id,
                project: project
            }).done(function(o) {
                //$('[id^=' + id + ']').remove();
                location.reload();
            });
        }
    }
</script>