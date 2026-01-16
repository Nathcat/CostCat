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
            <?php include("../header.php"); ?>
            <div class="main align-center">
                <h1>Log a new transaction</h1>

                <div class="content-card">
                    <p>
                        Please first enter the amount which you spent in this transaction.
                    </p>

                    <input id="amount" type="number" placeholder="Amount">
                </div>

                <div class="content-card">
                    <p>
                        Select all the users who are to be debted by this transaction. i.e. who are you paying to.
                    </p>

                    <table id="users">

                    </table>
                </div>

                <!-- Populating page -->
                <script>
                    const get_params = new URLSearchParams(window.location.search);
                    const group = get_params.get("group");

                    if (group === undefined) {
                        alert("You must specify a group.");
                        window.location = "/";
                    }
                    
                    function user_as_html(u) {
                        return "<tr><td><div style='max-width: 50px; max-height: 50px;' class='small-profile-picture'><img style='max-width: 50px; max-height: 50px;' src='https://cdn.nathcat.net/pfps/" + u.pfpPath + "'></div><h4 style='padding-left: 5px;'>" + u.username + "</h4></td><td><input type='radio' value='" + u.id + "' ></td></tr>";
                    }

                    get_users(group, (m) => {
                        m.forEach((u) => {
                            if (u !== <?php echo $_SESSION["user"]["id"]; ?>) {
                                $("#users").append(user_as_html(u));
                            }
                        });
                    }, (m) => console.log(m));
                </script>
                <!----------------------->
            </div>
            <?php include("../footer.php"); ?>
        </div>
    </body>
</html>