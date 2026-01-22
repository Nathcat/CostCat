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
            <?php include("../../header.php"); ?>
            <div class="main align-center">
                <h1>Log a new transaction</h1>

                <div class="content-card">
                    <p>
                        Please first enter the amount which you spent in this transaction.
                    </p>

                    <input id="amount" type="text" placeholder="Amount">
                </div>

                <div class="content-card">
                    <p>
                        Select all the users who are to be debted by this transaction. i.e. who are you paying to.
                    </p>

                    <table id="users" style="width: 100%; box-sizing: border-box">

                    </table>
                </div>

                <button onclick="do_log()">Log transaction</button>

                <!-- Populating page -->
                <script>
                    const get_params = new URLSearchParams(window.location.search);
                    const group = get_params.get("group");

                    if (group === null) {
                        window.location = "/";
                    }
                    
                    function user_as_html(u) {
                        return "<tr><td class='row align-center'><div style='max-width: 50px; max-height: 50px;' class='small-profile-picture'><img style='max-width: 50px; max-height: 50px;' src='https://cdn.nathcat.net/pfps/" + u.pfpPath + "'></div><h4 style='padding-left: 5px;'>" + u.username + "</h4></td><td><input type='radio' value='" + u.id + "' ></td></tr>";
                    }

                    get_users(group, (m) => {
                        m.forEach((u) => {
                            $("#users").append(user_as_html(u));
                        });
                    }, (m) => console.log(m));


                    function do_log() {
                        let payees = [];
                        $("input:radio:checked").each(function (index) {
                            payees.push(parseInt($(this).val()));
                        });

                        log_transaction(group, parseFloat($("#amount").val()), payees, () => {
                            alert("Transaction was logged.");
                            window.location = "/app/?group=" + group;
                        }, alert);
                    }
                </script>
                <!----------------------->
            </div>
            <?php include("../../footer.php"); ?>
        </div>
    </body>
</html>