import logging
from fastapi import HTTPException, Security
from fastapi.security import HTTPAuthorizationCredentials, HTTPBearer
from authlib.jose import jwt, JWTClaims
from authlib.jose.errors import JoseError
from dotenv import load_dotenv
import os

load_dotenv()

logging.basicConfig(level=logging.DEBUG)
logger = logging.getLogger(__name__)

class Auth:
    def __init__(self):
        self.public_key_path = os.getenv("PUBLIC_KEY_PATH")
        self.testing = os.getenv("TESTING", "false").lower() == "true"

        logger.debug(f"Attempting to read public key from: {self.public_key_path}")
        try:
            with open(self.public_key_path, 'r') as f:
                self.public_key = f.read()
            logger.debug("Public key read successfully")
        except Exception as e:
            logger.error(f"Error reading public key: {str(e)}")
            raise RuntimeError("Failed to read public key")

    async def decode_token(self, credentials: HTTPAuthorizationCredentials = Security(HTTPBearer())):
        if self.testing:
            return {"sub": "test_user"}  # Return mock claims for testing

        token = credentials.credentials
        logger.debug("Attempting to decode token")
        try:
            # Decode the token to get the claims
            claims = jwt.decode(token, self.public_key)
            logger.debug("Token decoded successfully")

            # Validate the claims
            claims.validate(leeway=10)  # Adding leeway for clock skew

            return claims
        except JoseError as e:
            logger.error(f"Error decoding token: {str(e)}")
            raise HTTPException(status_code=401, detail="Invalid token")
        except Exception as e:
            logger.error(f"Unexpected error: {str(e)}")
            raise HTTPException(status_code=500, detail="Internal server error")

auth_handler = Auth()
