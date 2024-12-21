import pdfplumber
import json
import sys

def extrair_tabela(pdf_url):
    try:
        with pdfplumber.open(pdf_url) as pdf:
            tabelas = []
            for pagina in pdf.pages:
                tabelas.extend(pagina.extract_tables())
            return json.dumps(tabelas)
    except Exception as e:
        return json.dumps({"error": str(e)})

if __name__ == "__main__":
    pdf_url = sys.argv[1]  # Primeiro argumento da linha de comando
    print(extrair_tabela(pdf_url))
