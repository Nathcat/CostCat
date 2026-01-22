//const get_params = new URLSearchParams(window.location.search);
//const group = get_params.get("group");
const user_cache = {};

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
            "amount": amount * 100.0,
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

function get_transactions(group, success, fail) {
    fetch("/api/get-transactions.php?group=" + group, {
        method: "GET",
        credentials: "include"
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") success(r.transactions.map((t) => {t.amount /= 100.0; return t;}));
        else fail(r.message);
    });
}

function get_user(id, success, fail) {
    if (user_cache[id] !== undefined) success(user_cache[id]);
    else { 
        fetch("https://data.nathcat.net/sso/user-search.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                "id": id
            })
        }).then((r) => r.json()).then((r) => {
            if (r.status === "success") {
                user_cache[id] = r.results[id];
                success(r.results[id]);
            }
            else fail(r.message);
        });
    }
}

function get_users(group, success, fail) {
    fetch("https://data.nathcat.net/data/get-group-members.php", {
        method: "POST",
        credentials: "include",
        body: JSON.stringify({
            "group": group
        })
    }).then((r) => r.json()).then((r) => {
        if (r.status === "success") success([r.owner].concat(r.members));
        else fail(r.message);
    });
}