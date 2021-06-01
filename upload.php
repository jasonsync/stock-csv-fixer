<?php
// if file has been uploaded
if (isset($_FILES) && isset($_FILES['fileupload'])&&isset($_FILES['fileupload']['name'])) {
    $output_filename = pathinfo($_FILES['fileupload']['name'], PATHINFO_FILENAME).'_fixed.csv';
    $handle = fopen($_FILES['fileupload']['tmp_name'], "r");
    $headers = fgetcsv($handle, 0, ",");
    // var_dump($headers);

    // count number of item columns, index of first item column, and find index of important columns
    $idx_first_item_column = null;
    $num_item_columns = 0;
    $idx_order_number = null;
    $idx_order_date = null;
    $idx_billing_first_name = null;
    $idx_billing_last_name = null;
    for ($i=0; $i < count($headers); $i++) {
        if (strpos($headers[$i], 'line_item_') !== false) {
            $num_item_columns++;
            if (!isset($idx_first_item_column)) {
                $idx_first_item_column = $i;
            }
        }
        if (strpos($headers[$i], 'order_number') !== false) {
            $idx_order_number = $i;
        }
        if (strpos($headers[$i], 'order_date') !== false) {
            $idx_order_date = $i;
        }
        if (strpos($headers[$i], 'billing_first_name') !== false) {
            $idx_billing_first_name = $i;
        }
        if (strpos($headers[$i], 'billing_last_name') !== false) {
            $idx_billing_last_name = $i;
        }
    }

    // make array of all records (but only important fields)
    $arr_orders_onlyimportantfields = array();
    // for each order...
    while (($row = fgetcsv($handle, 0, ",")) !== false) {
        $order_onlyimportantfields = array();
        // get common fields...
        $order_onlyimportantfields['order_number'] = $row[$idx_order_number];
        $order_onlyimportantfields['order_date'] = $row[$idx_order_date];
        $order_onlyimportantfields['billing_first_name'] = $row[$idx_billing_first_name];
        $order_onlyimportantfields['billing_last_name'] = $row[$idx_billing_last_name];
        // and each item in the order
        for ($i=0; $i < $num_item_columns; $i++) {
            if (isset($row[$idx_first_item_column+$i])) {
                $item = $row[$idx_first_item_column+$i];
                if (!empty($item)) {
                    // split item into sub fields...
                    $item_parts = explode("|", $item);
                    // var_dump($item_parts);
                    // die();
                    $item_name = explode(":", $item_parts[0])[1];
                    // $item_product_id = explode(":", $item_parts[1])[1];
                    $item_sku = explode(":", $item_parts[2])[1];
                    $item_quantity = explode(":", $item_parts[3])[1];
                    $item_total = explode(":", $item_parts[4])[1];
                    // $item_name =

                    $order_onlyimportantfields['item_name'] =$item_name;
                    // $order_onlyimportantfields['item_product_id'] =$item_product_id;
                    $order_onlyimportantfields['item_sku'] =$item_sku;
                    $order_onlyimportantfields['item_quantity'] =$item_quantity;
                    $order_onlyimportantfields['item_total'] =$item_total;
                    $arr_orders_onlyimportantfields[] = $order_onlyimportantfields;
                }
            }
        }
    }
    fclose($handle);
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$output_filename.'";');

    $f = fopen('php://output', 'w');
    $columns = array('Order Number','Order Date','First Name','Last Name','Item Name','Item SKU','Item Quantity','Item Total (R)');

    fputcsv($f, $columns);
    foreach ($arr_orders_onlyimportantfields as $line) {
        fputcsv($f, $line);
    }
    // print_r($arr_orders_onlyimportantfields);
}
exit();
 ?>
