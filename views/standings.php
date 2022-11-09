<?php

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path . '/documentelements.php');

start_content_full(1, 'standings');

?>

<div class="profile-tabs"></div>

<div class="row">
    <div class="box" style="overflow-y: hidden !important; height:380px; max-height:380px;">
        <div class="sort-by">
            <span>Sort by:</span>
            <select name="div" id="div">
                <option value="1" selected>Division 1</option>
                <option value="2">Division 2</option>
            </select>
        </div>

        <div class="table">
            <table cellspacing="0">
                <thead class="stnd-thead"></thead>
                <tbody class="stnd-tbody"></tbody>
            </table>
        </div>

    </div>
</div>

<?php end_content_full(1); ?>