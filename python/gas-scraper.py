import sys
import requests
from bs4 import BeautifulSoup
import pandas as pd
import re
from io import BytesIO
from datetime import datetime
import sqlite3
import os

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
    df = pd.read_excel(file, header=header_row, dtype=str)

    # quantidade de linhas 
    print(f"Total de linhas: {len(df)}")
    return df

def setup_database(db_path):
    os.makedirs(os.path.dirname(db_path), exist_ok=True)
    conn = sqlite3.connect(db_path)
    return conn

def save_or_update(df, conn):
    cursor = conn.cursor()
    for _, row in df.iterrows():
        cursor.execute('''
            INSERT INTO combustiveis (
                CNPJ, RAZAO, FANTASIA, ENDERECO, NUMERO, COMPLEMENTO, BAIRRO,
                CEP, MUNICIPIO, ESTADO, BANDEIRA, PRODUTO, UNIDADE_MEDIDA, PRECO_REVENDA, DATA_COLETA
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON CONFLICT(CNPJ, PRODUTO, DATA_COLETA) DO UPDATE SET
                RAZAO=excluded.RAZAO,
                FANTASIA=excluded.FANTASIA,
                ENDERECO=excluded.ENDERECO,
                NUMERO=excluded.NUMERO,
                COMPLEMENTO=excluded.COMPLEMENTO,
                BAIRRO=excluded.BAIRRO,
                CEP=excluded.CEP,
                MUNICIPIO=excluded.MUNICIPIO,
                ESTADO=excluded.ESTADO,
                BANDEIRA=excluded.BANDEIRA,
                UNIDADE_MEDIDA=excluded.UNIDADE_MEDIDA,
                PRECO_REVENDA=excluded.PRECO_REVENDA
        ''', (
            str(row['CNPJ']).zfill(14),
            row['RAZÃO'],
            row['FANTASIA'],
            row['ENDEREÇO'],
            str(row['NÚMERO']),
            row['COMPLEMENTO'],
            row['BAIRRO'],
            str(row['CEP']),
            row['MUNICÍPIO'],
            row['ESTADO'],
            row['BANDEIRA'],
            row['PRODUTO'],
            row['UNIDADE DE MEDIDA'],
            float(row['PREÇO DE REVENDA']) if not pd.isnull(row['PREÇO DE REVENDA']) else None,
            str(row['DATA DA COLETA'])
        ))
    conn.commit()

if __name__ == '__main__':
    page_url = sys.argv[1]
    links = fetch_excel_links(page_url)

    if not links:
        print("Nenhum arquivo encontrado.")
        sys.exit(1)

    links.sort(key=extract_date_from_url, reverse=True)
    most_recent_urls = links[:2]

    db_path = './../database/database.db'
    conn = setup_database(db_path)

    for url in most_recent_urls:
        try:
            df = parse_excel(url)
            save_or_update(df, conn)
            print(f"Arquivo {url} processado e salvo.")
        except Exception as e:
            print(f"Erro ao processar {url}: {str(e)}")

    conn.close()
