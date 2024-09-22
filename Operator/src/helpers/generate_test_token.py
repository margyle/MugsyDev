import sys
import time
from authlib.jose import jwt

def create_token(private_key_path):
    try:
        with open(private_key_path, 'rb') as f:
            private_key = f.read()
    except FileNotFoundError:
        print(f"Error: Private key file not found at {private_key_path}")
        sys.exit(1)
    except PermissionError:
        print(f"Error: Permission denied when trying to read {private_key_path}")
        sys.exit(1)
    
    payload = {
        "exp": int(time.time()) + 3600,  # 1 hour expiration
        "iat": int(time.time()),
        "user_id": "test_user_id",
        "some_claim": "test_claim_value"
    }
    header = {"alg": "RS256"}

    try:
        token = jwt.encode(header, payload, private_key)
        return token.decode('utf-8')  # Convert bytes to string
    except Exception as e:
        print(f"Error creating token: {str(e)}")
        sys.exit(1)

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python generate_test_token.py /path/to/private_key.pem")
        sys.exit(1)
    
    private_key_path = sys.argv[1]
    token = create_token(private_key_path)
    print(token)