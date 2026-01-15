<?php
function is_member_of_group($conn, $group, $user)
{
    $stmt = $conn->prepare("SELECT count(*) AS 'count' FROM DataCat.`Groups` WHERE `owner` = ? AND id = ?");
    $stmt->bind_param("ii", $user, $group);
    $stmt->execute();

    $res = $stmt->get_result()->fetch_assoc()["count"];
    if ($res != 1) {
        $stmt = $conn->prepare("SELECT count(*) AS 'count' FROM DataCat.`Group_Members` WHERE `group` = ? AND `user` = ?");
        $stmt->bind_param("ii", $group, $user);
        $stmt->execute();

        $res = $stmt->get_result()->fetch_assoc()["count"];
        if ($res != 1) {
            return false;
        }
    }

    $stmt->close();

    return true;
}