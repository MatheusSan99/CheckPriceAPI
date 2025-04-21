import sys
import requests
from bs4 import BeautifulSoup
import pandas as pd
import json
from io import BytesIO
import re
from datetime import datetime

def fetch_excel_links(page_url):
    response = requests.get(page_url)
    soup = BeautifulSoup(response.text, 'html.parser')
    links = [
        a['href'] for a in soup.find_all('a', href=True)
        if ('revendas_lpc_' in a['href']) and a['href'].endswith('.xlsx')
    ]
    return links

def extract_date_from_url(url):
    match = re.search(r'(\d{4}-\d{2}-\d{2})_(\d{4}-\d{2}-\d{2})', url)
    if match:
        return datetime.strptime(match.group(2), '%Y-%m-%d')
    return datetime.min

def parse_excel(url):
    response = requests.get(url)
    file = BytesIO(response.content)

    df_raw = pd.read_excel(file, header=None)

    header_row = None
    for index, row in df_raw.iterrows():
        if any(str(cell).strip().upper() in ['CNPJ', 'CEP', 'BAIRRO'] for cell in row):
            header_row = index
            break

    if header_row is None:
        raise ValueError("Cabeçalho não encontrado no arquivo Excel.")

    file.seek(0) 
    df = pd.read_excel(file, header=header_row)

    return df.to_dict(orient='records')

if __name__ == '__main__':
    page_url = sys.argv[1]

    links = fetch_excel_links(page_url)

    if not links:
        print(json.dumps({"error": "Nenhum arquivo encontrado."}))
        sys.exit(1)

    links.sort(key=extract_date_from_url, reverse=True)

    most_recent_urls = links[:2]

    data = []
    for url in most_recent_urls:
        try:
            file_data = parse_excel(url)
            data.append({
                "url": url,
                "data": file_data
            })
        except Exception as e:
            data.append({"error": f"Erro ao processar {url}: {str(e)}"})

    print(json.dumps(data, ensure_ascii=False))
