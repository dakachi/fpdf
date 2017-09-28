<?php
require 'fpdf.php';

$order = wc_get_order( $_GET['order_id'] );

$billing = $order->get_address('billing');

$first_name = $billing['first_name'];
$last_name = $billing['last_name'];
$address = $billing['address_1'];
$city = $billing['city'];
$post_code = $billing['postcode'];

$product_id = $_GET['product_id'];
$url = get_the_post_thumbnail_url($product_id, 'full');

class PDF extends FPDF
{
// Page header
    public function Header()
    {
        // Logo

        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);

        // Title
        // Line break
        $this->Ln(15);
    }

// Page footer
    public function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);

$pdf->Image($url, 120, 15, 80);
$pdf->SetFont('Arial', '', 18);
$pdf->Text( 138, 47, '10 - MAR - 2018');

$pdf->SetFont('Arial', '', 22);
$pdf->Text( 140, 64, 'W358 RRE');

$pdf->SetFont('Arial', '', 10);
$pdf->Text( 147, 82, '10 - MAR - 2018');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Mr. '.$first_name.' '. $last_name . ',', 0, 1);
$pdf->Cell(0, 6, $address, 0, 1);
$pdf->Cell(0, 6, $city, 0, 1);
$pdf->Cell(0, 6, $post_code, 0, 1);

$pdf->ln(5);

$pdf->Cell(0, 10, 'Simply peel off the sticker and place inside your vehicle. ', 0, 1);
$pdf->Cell(0, 10, 'Thank you for using Tax Disk Remind-Us.', 0, 1);

$pdf->Output();


exit;
