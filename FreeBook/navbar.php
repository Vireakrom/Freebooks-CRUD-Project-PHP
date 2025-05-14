<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a class="navbar-brand font-weight-bold" href="index.php">
      <i class="fa fa-book"></i> FreeBooks
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
          <a class="nav-link" href="index.php"><i class="fa fa-home"></i> Home</a>
        </li>
        <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">
          <a class="nav-link" href="about.php"><i class="fa fa-info-circle"></i> About</a>
        </li>
        <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'Create.php' ? 'active' : '' ?>">
          <a class="nav-link" href="Create.php"><i class="fa fa-upload"></i> Upload Book</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
