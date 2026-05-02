import sys
import pypdf

def read_pdf(file_path, out_path):
    try:
        reader = pypdf.PdfReader(file_path)
        with open(out_path, 'w', encoding='utf-8') as f:
            for page in reader.pages:
                f.write(page.extract_text() + "\n")
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    read_pdf("TokoKelontong_Kel11_3SIC.pdf", "pdf_text.txt")
