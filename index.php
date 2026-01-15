<!DOCTYPE html>
<html>
    <head>
        <title>CostCat</title>

        <link rel="stylesheet" href="https://nathcat.net/static/css/new-common.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="/static/js/api.js"></script>
        <link rel="stylesheet" href="/static/css/main.css">
    </head>

    <body>
        <div id="page-content" class="content">
            <?php include("header.php"); ?>
            <div class="main align-center">
                <h1>Welcome to CostCat, <?php echo $_SESSION["user"]["username"] ?></h1>

                <h2>Your groups</h2>
                <div id="groups" class="column align-center"></div>

                <!-- Populate the user's group list -->
                <script>
                    function group_as_html(g) {
                        return "<div class='content-card row align-center justify-center'><h3>" + g.name + "</h3></div>";
                    }

                    get_groups((G) => {
                        G.forEach((g) => {
                            $("#groups").append(group_as_html)
                        });
                    }, (m) => console.log(m));
                </script>
                <!--------------------------------------->
            </div>
            <?php include("footer.php"); ?>
        </div>
    </body>
</html>