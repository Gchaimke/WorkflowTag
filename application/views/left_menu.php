<?php
if (isset($this->session->userdata['logged_in'])) {
    $user_id = $this->session->userdata['logged_in']['id'];
}
$this->load->model('Users_model');
$user_log = $this->Users_model->get_user_log($user_id);


?>
<div>
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="left_menu" aria-labelledby="offcanvasScrollingLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Workflow Center</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="last_checklists">
                <h2>Last 10 checklists</h2>
                <?php
                if ($user_log['log'] != "") {
                    $checklists = json_decode($user_log['log'], true);
                    $checklists['checklists'] = array_reverse($checklists['checklists']);
                    foreach ($checklists['checklists'] as $key => $checklist) {
                        echo "<p><a href='$checklist'>$key</a></p>";
                    }
                } else {
                    echo "No last items";
                }
                ?>
            </div>
            <div class="last_checklists">
                <h2>Messages</h2>
            </div>
        </div>
    </div>
    <button class="btn btn-outline-secondary open_menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#left_menu" aria-controls="left_menu">
        <i class="fas fa-bars"></i></button>
</div>
<script>
    var myOffcanvas = document.getElementById('left_menu')
    myOffcanvas.addEventListener('show.bs.offcanvas', function() {
        $('.open_menu').css('left', 395)
    })
    myOffcanvas.addEventListener('hide.bs.offcanvas', function() {
        $('.open_menu').css('left', -5)
    })
</script>