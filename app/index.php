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
            <div class="main-grid align-center">

                <div style="grid-area: side; box-sizing: border-box; width: 100%; height: 100%; margin: 0; padding: 10px;">
                    <div id="debts" class="content-card align-center">
                        <h1>Individual debts</h1>
                    </div>
                </div>

                <div style="grid-area: overall; box-sizing: border-box; width: 100%; height: 100%; margin: 0; padding: 10px;">
                    <div id="overall" class="content-card">
                        <h1>Your total balance</h1>
                        <h2 id="total-balance" style="color: var(--quad-color);">£--.--</h2>
                    </div>
                </div>

                <div style="grid-area: transactions; box-sizing: border-box; width: 100%; height: 100%; margin: 0; padding: 10px;">
                    <div id="transactions" class="column align-center">
                        <h1>Transactions</h1>
                    </div>
                </div>

                <!-- Initialise the page -->
                <script>
                    const get_params = new URLSearchParams(window.location.search);
                    const group = get_params.get("group");

                    function debt_as_html(debt, debtor) {
                        return "<div class='row justify-center'><p id='user-" + debtor + "'>User " + debtor + "</p><span class='half-spacer'></span><p>£" + debt + "</p></div>";
                    }

                    get_balance(group, (b) => {
                        $("#total-balance").text("£" + b.overall);
                        Object.keys(b.individual).forEach((debtor) => {
                            $("#debts").append(debt_as_html(b.individual[debtor], debtor));

                            // Request the user data
                            get_user(debtor, (u) => {
                                console.log(u);
                                $("#user-" + u.id).text(u.username);
                            }, (m) => console.log(m));
                        });
                    }, (m) => console.log(m));

                    function transaction_as_html(t) {
                        return "";  
                    }

                    const transactions = get_transactions(group, (T) => {
                        T.forEach((t) => {
                            $("#transactions").append(transaction_as_html(t));
                        });

                        if (T.length === 0) {
                            $("#transactions").append("<p>No transactions!</p>");
                        }
                    }, (m) => console.log(m));
                </script>
                <!--------------------------->
            </div>
            <?php include("../footer.php"); ?>
        </div>
    </body>
</html>