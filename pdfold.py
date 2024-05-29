
import pdfkit
import sys
import PyPDF2
from PyPDF2 import PdfWriter, PdfReader
import requests  # Added for image loading
from urllib.parse import urljoin  # Added for URL handling

# ... (rest of your code) ...
pdfkit_config = pdfkit.configuration(wkhtmltopdf=r'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe')

def convert_html_to_pdf(html_file, pdf_file):
    """
    Converts an HTML file to a PDF using wkhtmltopdf, handling image loading.

    Args:
        html_file (str): Path to the HTML file.
        pdf_file (str): Path to the output PDF file.
    """
    try:
        # Print details for debugging
        print(f"PDF file: {pdf_file}")
        print(f"Password: {password}")  # Note: Don't print the actual password for security reasons

        # ... (rest of your encryption logic) ...
    except Exception as e:
        print(f"An error occurred: {str(e)}")
        
    try:
        pdfkit.from_file(html_file, pdf_file, configuration=pdfkit_config)
        print("PDF successfully created!")
    except Exception as e:
        print(f"An error occurred creating PDF: {str(e)}")
    try:
        with open(html_file, 'r') as f:
            html_content = f.read()

        # Process images until they load
        base_url = urljoin(html_file, '/')  # Ensure correct base URL for loading
        while True:
            images_loaded = True
            for image_url in re.findall(r'<img src="([^"]+)"', html_content):
                try:
                    requests.get(urljoin(base_url, image_url), stream=True).raise_for_status()  # Check for errors
                except requests.exceptions.RequestException:
                    print(f"Image {image_url} not loaded yet. Retrying...")
                    images_loaded = False
                    time.sleep(2)  # Pause briefly before retrying
                    break  # Break out of the inner loop to try loading images again

            if images_loaded:
                break  # All images loaded successfully

        pdfkit.from_file(html_file, pdf_file, configuration=pdfkit_config)
        print("PDF successfully created!")
    except Exception as e:
        print(f"An error occurred: {str(e)}")


def convert_html_to_pdf(html_file, pdf_file):
    """
    Converts an HTML file to a PDF using wkhtmltopdf.

    Args:
        html_file (str): Path to the HTML file.
        pdf_file (str): Path to the output PDF file.
    """
    try:
        pdfkit.from_file(html_file, pdf_file, configuration=pdfkit_config)
        print("PDF successfully created!")
    except Exception as e:
        print(f"An error occurred: {str(e)}")


def modify_letter_spacing(pdf_file, letter_spacing):
    """
    Modifies letter spacing in an existing PDF using PyPDF2.

    Args:
        pdf_file (str): Path to the PDF file.
        letter_spacing (float): The desired letter spacing value.
    """
    pdf_reader = PdfReader(open(pdf_file, 'rb'))
    pdf_writer = PdfWriter()

    for page_num in range(len(pdf_reader.pages)):
        page = pdf_reader.pages[page_num]
        # Get the content stream from the page
        content_stream = page["/Contents"].getObject().getData()

        # Modify the content stream to adjust letter spacing (using iText syntax)
        modified_stream = content_stream.replace(b"Tw", f"({letter_spacing:.2f} Tw".encode())

        # Create a new page object with the modified content stream
        new_page = pdf_writer.addPage(pdf_reader.pages[page_num])
        new_page["/Contents"].setData(modified_stream)

    # Save the modified PDF (consider renaming)
    with open(f"{pdf_file}_encrypted.pdf", 'wb') as output_pdf:  # Add suffix for encrypted file
        writer.write(output_pdf)


# Adjust this value for desired spacing


if __name__ == "__main__":
    # Check if the password argument is provided
    if len(sys.argv) < 4:
        print("Usage: python convert_html_to_pdf.py <HTML_FILE> <PDF_FILE> <PASSWORD>")
        sys.exit(1)

    html_file = sys.argv[1]  # Path to your HTML file
    pdf_file = sys.argv[2]   # Path to the output PDF file
    password = sys.argv[3]    # Password passed from PHP

    # Convert HTML to PDF
    
    letter_spacing = 0.2
    # Modify letter spacing in the generated PDF
    #modify_letter_spacing(pdf_file, letter_spacing)
    # Convert HTML to PDF
    convert_html_to_pdf(html_file, pdf_file)

    # Add password to the PDF (ensure this line is not commented out)
    add_password_to_pdf(pdf_file, password)

