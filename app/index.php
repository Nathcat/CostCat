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

                <div class="column" style="grid-area: side; box-sizing: border-box; width: 100%; height: 100%; margin: 0; padding: 10px;">
                    <h1>Individual debts</h1>
                    <p>
                        Here you can see how much you owe each member of the group, a negative value indicates that you owe the user, a positive
                        value indicates that they owe you.
                    </p>
                    <table id="debts" class="content-card">
                        <tr><th><h3>User</h3></th><th><h3>Balance</h3></th></tr>
                    </table>
                </div>

                <div style="grid-area: overall; box-sizing: border-box; width: 100%; height: 100%; margin: 0; padding: 10px;">
                    <div id="overall" class="content-card">
                        <h1>Your total balance</h1>
                        <p>
                            This is your total balance over the entire group, negative values indicate debt, positive indicate credit.
                        </p>
                        <h2 id="total-balance" style="color: var(--quad-color);">£--.--</h2>
                    </div>
                </div>

                <div class="column" style="grid-area: transactions; box-sizing: border-box; width: 100%; height: 100%; margin: 0; padding: 10px;">
                    <h1>Transactions</h1>    
                    <table id="transactions" class="content-card">
                        <tr><th><h3>Payer</h3></th><th><h3>Amount</h3></th><th><h3># of payees</h3></th><th><h3>Time</h3></th></tr>
                    </table>
                </div>

                <!-- Initialise the page -->
                <script>
                    const get_params = new URLSearchParams(window.location.search);
                    const group = get_params.get("group");

                    function debt_as_html(debt, debtor) {
                        return "<tr><td class='row justify-center'><div class='small-profile-picture'><img id='user-pfp-" + debtor + "' src='https://cdn.nathcat.net/pfps/default.png'></div><p id='user-" + debtor + "'>User " + debtor + "</p></td><td><p>£" + debt + "</p></td></tr>";
                    }

                    function transaction_as_html(t) {
                        return "<tr><td class='row justify-center'><div class='small-profile-picture'><img id='transaction-user-pfp-" + t.payer + "' src='https://cdn.nathcat.net/pfps/default.png'></div><p id='transaction-user-" + t.payer + "'></p></td><td><p>" + t.amount + "</p></td><td><p>" + t.payeeCount + "</p></td><td><p>" + t.timestamp + "</p></td></tr>";
                    }

                    get_balance(group, (b) => {
                        $("#total-balance").text("£" + b.overall);
                        Object.keys(b.individual).forEach((debtor) => {
                            $("#debts").append(debt_as_html(b.individual[debtor], debtor));

                            // Request the user data
                            get_user(debtor, (u) => {
                                console.log(u);
                                $("#user-" + u.id).text(u.username);
                                $("#user-pfp-" + u.id).attr("src", "https://cdn.nathcat.net/pfps/" + u.pfpPath);
                            }, (m) => console.log(m));
                        });
                    }, (m) => console.log(m));

                    const transactions = get_transactions(group, (T) => {
                        T.forEach((t) => {
                            $("#transactions").append(transaction_as_html(t));
                            get_user(t.payer, (u) => {
                                console.log(u);
                                $("#transaction-user-" + u.id).text(u.username);
                                $("#transaction-user-pfp-" + u.id).attr("src", "https://cdn.nathcat.net/pfps/" + u.pfpPath);
                            }, (m) => console.log(m));
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