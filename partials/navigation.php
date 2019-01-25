<?php
// this is the menu navigation partial
?>

<nav id="menu" class="navbar navbar-expand-lg navbark-dark bg-dark">
  <a class="navbar-brand" href="/"><i class="fas fa-cannabis"></i></a>

  <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#menuBar" aria-controls="menuBar" aria-expanded="false" aria-label="Toggle menu bar">
    <i class="fas fa-bars"></i>
  </button>

  <div id="menuBar" class="collapse navbar-collapse">
    <ul class="navbar-nav ml-auto text-right">
      <!-- item > login -->
      <li class="nav-item">
        <a class="nav-link" href="?view=login">login</a>
      </li>

      <!-- item > register -->
      <li class="nav-item">
        <a class="nav-link" href="?view=register">register</a>
      </li>
    </ul>
  </div>
</nav>
