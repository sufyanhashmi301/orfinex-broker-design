# 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
import ast
import os
import sys
from typing import List, Tuple

import pandas as pd
from openai import OpenAI
from scipy import spatial
import tiktoken

# Constants
EMBEDDING_MODEL = "text-embedding-3-small"
GPT_MODEL = "gpt-3.5-turbo"

# Initialize OpenAI client
client = OpenAI(api_key=os.environ.get("OPENAI_API_KEY", "sk-None-smaEDApxcaJCrGXtDjiOT3BlbkFJVA5z9RatKMOZckYciIJp"))


def load_embeddings(embeddings_path: str) -> pd.DataFrame:
    """Load pre-computed embeddings from a CSV file."""
    df = pd.read_csv(embeddings_path)
    df['embedding'] = df['embedding'].apply(ast.literal_eval)
    return df


def strings_ranked_by_relatedness(
    query: str,
    df: pd.DataFrame,
    relatedness_fn=lambda x, y: 1 - spatial.distance.cosine(x, y),
    top_n: int = 100
) -> Tuple[List[str], List[float]]:
    """Returns a list of strings and relatednesses, sorted from most related to least."""
    query_embedding_response = client.embeddings.create(
        model=EMBEDDING_MODEL,
        input=query,
    )
    query_embedding = query_embedding_response.data[0].embedding
    strings_and_relatednesses = [
        (row["text"], relatedness_fn(query_embedding, row["embedding"]))
        for _, row in df.iterrows()
    ]
    strings_and_relatednesses.sort(key=lambda x: x[1], reverse=True)
    strings, relatednesses = zip(*strings_and_relatednesses)
    return list(strings[:top_n]), list(relatednesses[:top_n])


def num_tokens(text: str, model: str = GPT_MODEL) -> int:
    """Return the number of tokens in a string."""
    encoding = tiktoken.encoding_for_model(model)
    return len(encoding.encode(text))


def query_message(
    query: str,
    df: pd.DataFrame,
    model: str,
    token_budget: int
) -> str:
    """Return a message for GPT, with relevant source texts pulled from a dataframe."""
    strings, relatednesses = strings_ranked_by_relatedness(query, df, top_n=5)
    introduction = 'Use the below pdf document content to answer the subsequent question.'
    question = f"\n\nQuestion: {query}"
    message = introduction
    for string in strings:
        next_article = f'\n\nContent:\n"""\n{string}\n"""'
        if (num_tokens(message + next_article + question, model=model) > token_budget):
            break
        message += next_article
    return message + question


def ask(
    query: str,
    df: pd.DataFrame,
    model: str,
    token_budget: int,
    print_message: bool = False,
) -> str:
    """Answers a query using GPT and a dataframe of relevant texts and embeddings."""
    message = query_message(query, df, model=model, token_budget=token_budget)
    if print_message:
        print(message)
    messages = [
        {"role": "system", "content": "You answer questions about the cw360."},
        {"role": "user", "content": message},
    ]
    response = client.chat.completions.create(
        model=model,
#         response_format={ "type": "json_object" },
        messages=messages,
        temperature=0
    )
    response_message = response.choices[0].message.content
    return response_message


if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python script.py <embeddings_path> <query>")
        sys.exit(1)

    embeddings_path = sys.argv[1]
    query = sys.argv[2]

    df = load_embeddings(embeddings_path)
    response = ask(query, df, model=GPT_MODEL, token_budget=4096 - 500, print_message=False)
    print(response)


