<div id="closeNavBg" class="menuMob none">
        <div class="itemMenuMob"><a href="index.php">Accueil</a></div>
        <div class="itemMenuMob"><a href="index.php?page=services">Services</a></div>
        <div class="itemMenuMob"><a href="index.php?page=events-list">Evenements</a></div>
        <div class="itemMenuMob"><a href="index.php?page=contact">Nous contacter</a></div>

    <div class="user">
        <?php if (isset($_SESSION['user'])): ?>
            <p class="userName"><?= $_SESSION['user']['name'] ?></p>
            <a href="index.php?logout">
                <button class="DeconnectBtn">Déconnexion</button>
            </a>
            <a href="admin">
                <button class="ConnectBtn">Admin</button>
            </a>
        <?php else: ?>
            <a href="index.php?page=connexion">
                <button class="ConnectBtn">Connexion</button>
            </a>
        <?php endif; ?>
    </div>
    <div id="closeNavMobile"><a><i class=" closeMobileMenu far fa-times"></i></a></div>
</div>

<header class="bannerWhite">
    <nav class="navBlock">
        <ul>
            <li class="navItem noneMobile"><a href="index.php">Accueil</a></li>
            <li class="navItem noneMobile"><a href="index.php?page=services">Services</a></li>
            <li><a><img class="logo" src="assets/img/Logo-NLS-v3.png" alt="Logo Noisy"/></a></li>
            <li><a><i class="menuLogo fas fa-bars blockMobile none" id="openNav"></i></a></li>
            <li class="navItem noneMobile"><a href="index.php?page=events-list">Evenements</a></li>
            <li class="navItem noneMobile"><a href="index.php?page=contact">Nous contacter</a></li>
        </ul>
        <div class="user noneMobile">
            <?php if (isset($_SESSION['user'])): ?>
                <p class="userName"><?= $_SESSION['user']['name'] ?></p>
                <a href="index.php?logout">
                    <button class="DeconnectBtn">Déconnexion</button>
                </a>
                <a href="admin">
                    <button class="ConnectBtn">Admin</button>
                </a>
            <?php else: ?>
                <a href="index.php?page=connexion">
                    <button class="ConnectBtn">Connexion</button>
                </a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<script>
    let navMob = document.querySelector('.menuMob')

    document.querySelector('#openNav').addEventListener('click', function () {
        navMob.style.display = 'flex'
        navMob.style.height = '101%'
        navMob.style.width = '100vw'
    })
    document.querySelector('#closeNavMobile').addEventListener('click', function () {
        navMob.style.display = 'none'
        navMob.style.height = '0'
        navMob.style.width = '0'
    })
    document.querySelector('#closeNavBg').addEventListener('click', function () {
        navMob.style.display = 'none'
        navMob.style.height = '0'
        navMob.style.width = '0'
    })
</script>