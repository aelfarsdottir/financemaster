<div>
    <table class="table table-striped">
        <tr>
            <th> Symbol </th>
            <th> Name </th>
            <th> Shares </th>
            <th> Price </th>
            <th> TOTAL </th>
        </tr>
    <?php foreach ($rows as $row): ?> 
    <?php $stock = lookup($row["symbol"]); ?>
        <tr>
           <td><?= $row["symbol"]?></td>
           <td><?= $stock["name"]?></td>
           <td><?= $row["shares"]?></td>
           <td><?= "$" . $stock["price"]?></td>
           <td><?= "$" . number_format($stock["price"] * $row["shares"], 2, ".", ",")?></td>
        </tr>
    <?php endforeach ?>
    <tr>
           <td><?= "CASH" ?></td>
           <td><?= "" ?></td>
           <td><?= "" ?></td>
           <td><?= "" ?></td>
           <td><?= "$" . number_format($cash[0]["cash"], 2, ".", ",")?></td>
    </tr>
    </table>
</div>
