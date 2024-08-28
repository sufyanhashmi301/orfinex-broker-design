import openai
import os
import pandas as pd
from PyPDF2 import PdfReader
import re
import sys

# Initialize OpenAI client
# client = openai.OpenAI(api_key=os.environ.get("OPENAI_API_KEY"))
client = openai.OpenAI(api_key="sk-None-smaEDApxcaJCrGXtDjiOT3BlbkFJVA5z9RatKMOZckYciIJp")

# Function to clean and split text from PDF
def clean_and_split_pdf_text(pdf_path, max_tokens_per_chunk=800):
    # Read PDF
    with open(pdf_path, 'rb') as file:
        reader = PdfReader(file)
        text = ''
        for page in reader.pages:
            text += page.extract_text()

    # Remove extra whitespaces, newlines, and special characters
    text = re.sub(r'\s+', ' ', text)

    # Split text into chunks
    chunks = []
    chunk_start = 0
    while chunk_start < len(text):
        chunk_end = min(chunk_start + max_tokens_per_chunk, len(text))
        chunks.append(text[chunk_start:chunk_end])
        chunk_start = chunk_end

    return chunks

# Function to clean and split text from Excel
def clean_and_split_excel_text(excel_path, max_tokens_per_chunk=800):
    # Read Excel file
    file_extension = os.path.splitext(excel_path)[1]
    if file_extension == '.xlsx':
        engine = 'openpyxl'
    elif file_extension == '.xls':
        engine = 'xlrd'
    else:
        raise ValueError(f"Unsupported Excel file format: {file_extension}")

    try:
        xls = pd.ExcelFile(excel_path, engine=engine)
    except Exception as e:
        raise ValueError(f"Error reading Excel file: {e}")

    text = ''
    for sheet_name in xls.sheet_names:
        try:
            df = pd.read_excel(xls, sheet_name, engine=engine)
            text += df.to_string(index=False)
        except Exception as e:
            print(f"Error reading sheet {sheet_name}: {e}")

    # Remove extra whitespaces, newlines, and special characters
    text = re.sub(r'\s+', ' ', text)

    # Split text into chunks
    chunks = []
    chunk_start = 0
    while chunk_start < len(text):
        chunk_end = min(chunk_start + max_tokens_per_chunk, len(text))
        chunks.append(text[chunk_start:chunk_end])
        chunk_start = chunk_end

    return chunks

def generate_embeddings(chunks, embedding_model="text-embedding-3-small", batch_size=1000):
    embeddings = []
    for i in range(0, len(chunks), batch_size):
        batch = chunks[i:i + batch_size]
        response = client.embeddings.create(model=embedding_model, input=batch)
        embeddings.extend([emb.embedding for emb in response.data])
    return embeddings

def main(file_path):
    if file_path.lower().endswith('.pdf'):
        # Clean and split PDF text
        chunks = clean_and_split_pdf_text(file_path)
    elif file_path.lower().endswith('.xlsx') or file_path.lower().endswith('.xls'):
        # Clean and split Excel text
        try:
            chunks = clean_and_split_excel_text(file_path)
        except ValueError as e:
            print(e)
            return
    else:
        print("Unsupported file format. Please provide a PDF or Excel file.")
        return

    # Generate embeddings for chunks
    embeddings = generate_embeddings(chunks)

    # Create DataFrame
    df = pd.DataFrame({'text': chunks, 'embedding': embeddings})

    # Get the directory and filename components of the input file path
    directory, filename = os.path.split(file_path)

    # Change the extension to .csv
    csv_filename = os.path.splitext(filename)[0] + '.csv'

    # Construct the path to save the CSV file in the same directory as the input file
    csv_path = os.path.join(directory, csv_filename)

    # Save DataFrame to CSV
    df.to_csv(csv_path, index=False)

    print(f"File text embedded and saved to {csv_path}")

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python script.py <file_path>")
    else:
        file_path = sys.argv[1]
        main(file_path)
