<?php
function ordiniSenzaItems($conn)
{
    $sql = "SELECT order_id,order_date,order_status
    FROM t_order
    LEFT JOIN t_order_item
    ON t_order.order_id = t_order_item.order_id
    WHERE t_order_item.order_id IS NULL";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        $ordiniVuoti = [];
        while ($line = mysqli_fetch_assoc($res)) {
            array_push($ordiniVuoti, $line);
        }
        return $ordiniVuoti;
    } else {
        return false;
    }
}
