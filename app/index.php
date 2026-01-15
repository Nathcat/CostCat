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

                <div id="debts" class="content-card align-center">
                    <h1>Individual debts</h1>
                </div>

                <div style="grid-areas: overall" class="content-card">
                    <h1>Your total balance</h1>
                    <h2 id="total-balance" style="color: var(--quad-color);">£--.--</h2>
                </div>

                <div id="transactions" class="column align-center">
                    <h1>Transactions</h1>
                </div>

                <!-- Initialise the page -->
                <script>
                    const get_params = new URLSearchParams(window.location.search);
                    const group = get_params.get("group");

                    function debt_as_html(debt, debtor) {
                        return "<div row justify-center><p>User " + debtor + "</p><span class='spacer'></span><p>£" + debt + "</p></div>";
                    }

                    get_balance(group, (b) => {
                        $("#total-balance").text("£" + b.overall);
                        Object.keys(b.individual).forEach((debt, debtor) => {
                            $("#debts").append(debt_as_html(debt, debtor));
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