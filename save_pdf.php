<?php
$filename = uniqid() . ".pdf"; // Generate random filename
$password = substr(md5(uniqid(mt_rand(), true)), 0, 8); // Generate random password

// Create PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->AddPage();
$pdf->writeHTML('Your HTML Content Here');

// Encrypt PDF
$pdf->SetProtection(array(), $password);
$pdfData = $pdf->Output($filename, 'S');

// Save encrypted PDF
file_put_contents($filename, $pdfData);

// Return password
echo "PDF saved as: $filename<br>";
echo "Password: $password";
?>
