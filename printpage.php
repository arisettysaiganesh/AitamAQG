
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Print PDF with Encryption</title>
<style>
    /* Optional styling for the button */
    .print-button {
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }</style>
</head>
<body>
    <h1>Your HTML Content Here</h1>
    <button class="print-button" id="printButton">Print</button>
    <script >

document.getElementById("printButton").addEventListener("click", function() {
    window.print();
});
document.getElementById("printButton").addEventListener("click", async function() {
    window.print();
    await generateAndSaveEncryptedPDF();
});

async function generateAndSaveEncryptedPDF() {
    const { PDFDocument, PDFName, StandardFonts, drawText } = PDFLib;

    // Create PDF
    const pdfDoc = await PDFDocument.create();
    const page = pdfDoc.addPage();
    const helveticaFont = await pdfDoc.embedFont(StandardFonts.Helvetica);
    const { width, height } = page.getSize();
    const fontSize = 30;
    page.drawText('Your PDF Content Here', {
        x: 50,
        y: height - 4 * fontSize,
        size: fontSize,
        font: helveticaFont,
        color: rgb(0, 0, 0),
    });

    // Encrypt PDF
    const randomPassword = Math.random().toString(36).substring(7); // Generate random password
    const password = new PDFName(randomPassword);
    pdfDoc.encrypt(password, password);

    // Save encrypted PDF
    const pdfBytes = await pdfDoc.save();
    const blob = new Blob([pdfBytes], { type: 'application/pdf' });
    const filename = `encrypted_pdf_${Date.now()}.pdf`;
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();

    // Display password on screen
    alert(`PDF saved as: ${filename}\nPassword: ${randomPassword}`);
}

    </script>
</body>
</html>

