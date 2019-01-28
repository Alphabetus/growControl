<nav id="menu" class="navbar navbar-expand-md navbark-dark bg-dark">
  <a class="navbar-brand" href="?view=dashboard"><i class="fas fa-cannabis"></i>&nbsp;<?php print strtolower($_SESSION["user_name"]);?> - grow</a>

  <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#menuBar" aria-controls="menuBar" aria-expanded="false" aria-label="Toggle menu bar">
    <i class="fas fa-bars"></i>
  </button>

  <div id="menuBar" class="collapse navbar-collapse">
    <ul class="navbar-nav ml-auto text-right">
      <!-- item > login -->
      <li class="nav-item">
        <a class="nav-link" href="?logout" title="logout from your account">logout <i class="fas fa-sign-out-alt"></i></a>
      </li>
    </ul>
  </div>
</nav>
