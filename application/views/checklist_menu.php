<nav class="navbar navbar-light fixed-top bg-light">
        <b id="project" class="navbar-text" href="#">Project: </b>
        <b id="sn" class="navbar-text" href="#">SN: </b>
        <b id="date" class="navbar-text" href="#">Date: </b>
        <ul class="nav navbar-nav navbar-right">
            <li lass="nav-item">
                <button id="snap" class="btn btn-info">Snap Photo</button>
                <button id="save" class="btn btn-success navbar-btn" onclick="saveData()">Save</button>
            </li>
        </ul>
        <div class="progress fixed-bottom">
            <div id="progress-bar" class="progress-bar progress-bar-striped bg-warning" role="progressbar"
                style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </nav>