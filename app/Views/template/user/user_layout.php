<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Bookshelf User" />
        <meta name="author" content="" />
        <title>Bookshelf</title>
        <link href="<?= base_url(); ?>/css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <?= $this->include('template/topbar') ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <!-- Sidebar -->
               <?= $this->include('template/user/user_sidebar') ?>
                <!-- Sidebar End -->
            </div>
            <div id="layoutSidenav_content">
                <!-- Main Content -->
                <?= $this->renderSection('page-content') ?>
                <!-- Main Content End -->
                
                <!-- Footer -->
                <?= $this->include('template/footer') ?>
                <!-- Footer End -->
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="/js/scripts.js"></script>
    </body>
</html>
