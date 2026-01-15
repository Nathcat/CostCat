//const get_params = new URLSearchParams(window.location.search);
//const group = get_params.get("group");

function get_balance(group, success, fail) {
    fetch("/api/determine-balance.php?group=" + group, {
        method: "GET",
        credentials: "include"
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") success(r);
        else fail(r.message);
    });
}

function log_transaction(group, amount, payees, success, fail) {
    fetch("/api/log-transaction.php", {
        method: "POST",
        credentials: "include",
        body: JSON.stringify({
            "group": group,
            "amount": amount,
            "payees": payees
        })
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") success();
        else fail(r.message);
    });
}

function get_groups(success, fail) {
    fetch("https://data.nathcat.net/data/get-groups.php", {
        method: "GET",
        credentials: "include"
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") success(r.owned.concat(r.memberOf));
        else fail(r.message);
    });
}