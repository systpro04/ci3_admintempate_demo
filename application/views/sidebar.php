<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index3.html" class="brand-link">
        <img src="<?php echo base_url('assets/img/ebm.png') ?>" alt="AdminLTE Logo" class="brand-image3"
            style="margin-left: 20px">
    </a>
    <div class="sidebar mt-2">
        <div id="snow-container"></div>
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a class="nav-link <?= (current_url() == base_url('dashboard')) ? 'active' : '' ?>"
                        href="<?= base_url('dashboard') ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (current_url() == base_url('data_view')) ? 'active' : '' ?>"
                        href="<?= base_url('data_view') ?>">
                        <i class="nav-icon fas fa-align-right"></i>
                        <p>Adjustment</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<style>
main {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    padding: 20px;
    text-align: center;
}


#snow-container {
    height: 100vh;
    overflow: hidden;
    position: absolute;
    top: 0;
    transition: opacity 500ms;
    width: 100%;
}

.snow {
    animation: fall ease-in infinite, sway ease-in-out infinite;
    color: skyblue;
    position: absolute;
}

@keyframes fall {
    0% {
        opacity: 0;
    }

    50% {
        opacity: 1;
    }

    100% {
        top: 100vh;
        opacity: 0;
    }
}

@keyframes sway {
    0% {
        margin-left: 0;
    }

    25% {
        margin-left: 50px;
    }

    50% {
        margin-left: -50px;
    }

    75% {
        margin-left: 50px;
    }

    100% {
        margin-left: 0;
    }
}
</style>

<script>
const snowContainer = document.getElementById("snow-container");
const snowContent = ['&#10052', '&#10053', '&#10054'];

const random = (num) => {
    return Math.floor(Math.random() * num);
}

const getRandomStyles = () => {
    const top = random(100);
    const left = random(100);
    const dur = random(10) + 10;
    const size = random(30) + 20;
    return `
    top: -${top}%;
    left: ${left}%;
    font-size: ${size}px;
    animation-duration: ${dur}s;
  `;
}

const createSnow = (num) => {
    for (var i = num; i > 0; i--) {
        var snow = document.createElement("div");
        snow.className = "snow";
        snow.style.cssText = getRandomStyles();
        snow.innerHTML = snowContent[random(3)];
        snowContainer.append(snow);
    }
}

window.addEventListener("load", () => {
    createSnow(30);
});
</script>