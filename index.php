<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Home</title>
    <link rel="stylesheet" href="./style.css" />
    <link rel="icon" href="logo.png" type="image/png">
  </head>
  <body>
    <nav class="Navbar">
      <div class="container">
        <div class="Logo">Roverino.</div>

        <div id="menu" class="menu">
          <header class="inline">
            <div class="Logo">Roverino.</div>
            <button
              id="closeMenuBtn"
              title="Close Menu"
              aria-label="Close Menu"
              class="NavButton"
            >
              <i class="ri-close-line"></i>
            </button>
          </header>

          <a href="#" class="NavLink">Home</a>
          <a href="#plans" class="NavLink">Gestisci</a>
          <?php
          session_start();
          if(isset($_SESSION['username']) || isset($_SESSION['admin'])) {
                echo "<a href=logout.php class='NavLink'>Logout</a>";
            }
            else{
                echo "<a href=login.php class='NavLink'>Login</a>";
            }
          ?>
        </div>

        <button
          id="openMenuBtn"
          title="Open Menu"
          aria-label="Open Menu"
          class="NavButton"
        >
          <i class="ri-menu-line"></i>
        </button>
      </div>
    </nav>
    <main>
      <section id="home">
        <div class="hero">
          <div class="visual">
            <picture>
              <source
                srcset="
                  https://raw.githubusercontent.com/mobalti/open-props-interfaces/main/landing-page-with-scroll-driven/assets/images/hero.webp
                "
                type="image/avif"
                media="(width > 1024px)"
              />
              <source
                srcset="
                  https://raw.githubusercontent.com/mobalti/open-props-interfaces/main/landing-page-with-scroll-driven/assets/images/hero-mobile.avif
                "
                type="image/avif"
              />
              <source
                srcset="
                  https://raw.githubusercontent.com/mobalti/open-props-interfaces/main/landing-page-with-scroll-driven/assets/images/hero.webp
                "
                media="(width > 1024px)"
                type="image/webp"
              />
              <img
                src="https://raw.githubusercontent.com/mobalti/open-props-interfaces/main/landing-page-with-scroll-driven/assets/images/hero-mobile.webp"
                alt="fitness"
              />
            </picture>
          </div>

          <div class="content">
            <?php
            if(isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                echo "<h1>Benvenuto, $username</h1>";
            } else if(isset($_SESSION['admin'])) {
                echo "<h1>Benvenuto, Amministratore</h1>";
            } else{
                echo "<h1>Benvenuto</h1>";
            }
            ?>
            <div class="wrapper">
              <a href="#discover" class="LinkButton Primary"> SCOPRI DI PIÙ </a>
            </div>
          </div>
        </div>

        <div id="discover" class="fold">
          <div class="subject">
            <p>
              Il roverino è uno
              <span style="color: var(--brand-1)"> sport di squadra</span>
              , praticato prevalentemente in ambito scout, che si gioca con un
              apposito cerchio di corda chiamato roverino.
            </p>
          </div>
        </div>
      </section>

      <section id="plans" class="cards">
        <div class="subject">
          <div class="container">
            <a href="visualizza.php" class="Card">
              <i class="ri-trophy-line"></i>
              <div>
                <h2>Visualizzare GIOCATORI</h2>
              </div>
            </a>
            <a href="aggiungi.php" class="Card">
              <i class="ri-flag-2-line"></i>
              <div>
                <h2>Aggiungere GIOCATORI</h2>
              </div>
            </a>
            <a href="formazioni.php" class="Card">
              <i class="ri-team-line"></i>
              <div>
                <h2>Visualizzare FORMAZIONI</h2>
              </div>
            </a>
            <?php
            if(isset($_SESSION['admin'])) {
                echo "<a href='lineup.php' class='Card'><i class='ri-team-line'></i><div><h2>Aggiungere FORMAZIONI</h2></div></a>";
                echo "<a href='admin.php' class='Card'><i class='ri-team-line'></i><div><h2>Area amministratore</h2></div></a>";
            }
            if(isset($_SESSION['username']) || isset($_SESSION['admin'])) {
                echo "<a href=logout.php class='Card'><i class='ri-team-line'></i><div><h2>Logout</h2></div></a>";
            } else{
                echo "<a href=login.php class='Card'><i class='ri-team-line'></i><div><h2>Accedi</h2></div></a>";
            }
            ?>
          </div>
        </div>
      </section>

      <section id="about" class="about">
        <footer>
          <br>
          <p>
            Capolavoro 2024, built by
            <a
              href="https://github.com/DomeManca"
              style="color: var(--brand-1); text-decoration: none"
              >Manca Domenico</a
            >.
          </p>
        </footer>
      </section>
    </main>
  </body>
  <script src="./script.js"></script>
</html>
