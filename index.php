<!DOCTYPE html>
<html>
    <head>
        <title>CostCat</title>

        <link rel="stylesheet" href="https://nathcat.net/static/css/new-common.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    </head>

    <body>
        <div id="page-content" class="content">
            <?php include("header.php"); ?>
            <div class="main">
                <h1>Welcome to CostCat, <?php echo $_SESSION["user"]["username"] ?></h1>
            </div>
            <?php include("footer.php"); ?>
        </div>
    </body>
</html>