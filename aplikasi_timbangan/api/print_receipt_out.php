<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$id = $_GET['id'] ?? '';

$query = "
   SELECT 
       wo.receipt_id,
       wo.created_at,
       b.name as buyer_name,
       wo.weight,
       wo.price,
       p.name as product_name
   FROM weighing_out wo
   LEFT JOIN buyers b ON wo.buyer_id = b.id 
   LEFT JOIN products p ON wo.product_id = p.id
   WHERE wo.receipt_id = ?
   ORDER BY p.name";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

$first_row = $result->fetch_assoc();
if(!$first_row) {
   die("Data tidak ditemukan");
}

$created_at = $first_row['created_at'] ?? date('Y-m-d H:i:s');
$buyer_name = $first_row['buyer_name'] ?? '-';

$result->data_seek(0); // Reset pointer

$grouped = [];
while($row = $result->fetch_assoc()) {
   $product = $row['product_name'];
   if(!isset($grouped[$product])) {
       $grouped[$product] = [
           'items' => [],
           'subtotal_weight' => 0,
           'subtotal' => 0
       ];
   }
   $grouped[$product]['items'][] = $row;
   $grouped[$product]['subtotal_weight'] += $row['weight'];
   $grouped[$product]['subtotal'] += $row['weight'] * $row['price'];
}
?>

<!DOCTYPE html>
<html>
<head>
   <title>Kwitansi</title>
   <style>
       body { 
           font-family: Arial; 
           font-size: 12px;
           padding: 20px;
       }
       .container { 
           width: 210mm;
           margin: 0 auto;
       }
       h2 { 
           text-align: center;
           margin-bottom: 20px;
       }
       table { 
           width: 100%;
           border-collapse: collapse;
           margin: 20px 0;
       }
       th, td { 
           border: 1px solid black;
           padding: 5px 8px;
       }
       th { 
           background: #f0f0f0;
           font-weight: bold;
           text-align: left;
       }
       .text-right { 
           text-align: right;
       }
       .subtotal { 
           background: #f0f0f0;
           font-weight: bold;
       }
       @media print {
           .no-print { display: none; }
           body { padding: 0; }
           @page { 
               size: A4;
               margin: 10mm;
           }
       }
   </style>
</head>
<body>
   <div class="container">
       <h2>Kwitansi</h2>

       <div>
           <p>ID Kwitansi: <?= $id ?></p>
           <p>Tanggal: <?= date('d/m/Y H:i', strtotime($created_at)) ?></p>  
           <p>Nama: <?= htmlspecialchars($buyer_name) ?></p>
       </div>

       <table>
           <tr>
               <th>No</th>
               <th>Produk</th>
               <th>Berat (kg)</th>
               <th>Harga</th>
               <th>Total</th>
           </tr>
           <?php
           $no = 1;
           $grand_total = 0;
           foreach($grouped as $product => $group):
               foreach($group['items'] as $item):
                   $total = $item['weight'] * $item['price'];
           ?>
           <tr>
               <td><?= $no++ ?></td>
               <td><?= htmlspecialchars($product) ?></td>
               <td class="text-right"><?= number_format($item['weight'], 2) ?></td>
               <td class="text-right">Rp <?= number_format($item['price']) ?></td>
               <td class="text-right">Rp <?= number_format($total) ?></td>
           </tr>
           <?php
               endforeach;
               $grand_total += $group['subtotal'];
           ?>
           <tr class="subtotal">
               <td colspan="2">Subtotal <?= htmlspecialchars($product) ?></td>
               <td class="text-right"><?= number_format($group['subtotal_weight'], 2) ?> kg</td>
               <td></td>  
               <td class="text-right">Rp <?= number_format($group['subtotal']) ?></td>
           </tr>
           <?php endforeach; ?>

           <tr>
               <td colspan="4" class="text-right"><strong>Total Harga:</strong></td>
               <td class="text-right"><strong>Rp <?= number_format($grand_total) ?></strong></td>
           </tr>
       </table>

       <div class="no-print" style="margin-top:20px;">
           <button onclick="window.print()">Print</button>
           <button onclick="window.close()">Close</button>
       </div>
   </div>

   <script>
       window.onload = function() {
           // Auto print
           window.print();
       }
   </script>
</body>
</html>