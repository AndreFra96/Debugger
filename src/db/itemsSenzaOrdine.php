<?php
function itemsSenzaOrdine($conn)
{
    $sql = "SELECT creation_date,t_order_item.order_id,article_id_trace
    FROM t_order_item
    LEFT JOIN t_order
    ON t_order_item.order_id = t_order.order_id
    WHERE t_order.order_id IS NULL";

    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        $itemSenzaOrdine = [];
        while ($line = mysqli_fetch_assoc($res)) {
            array_push($itemSenzaOrdine, $line);
        }
        return $itemSenzaOrdine;
    } else {
        return false;
    }
}
