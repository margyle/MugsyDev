import os
import sys
import logging
from fastapi import FastAPI, status
from starlette.responses import JSONResponse
from fastapi.exceptions import RequestValidationError
from starlette.exceptions import HTTPException
from operator_app.api.v1.routes import v1_router

# Configure logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

debug = os.getenv('DEBUG', 'False').lower() in ('true', '1', 't')
app = FastAPI(debug=debug)

app.include_router(v1_router, prefix="/v1")

@app.exception_handler(RequestValidationError)
async def validation_exception_handler(request, exc):
    logger.error(f"RequestValidationError: {exc}")
    return JSONResponse(
        status_code=status.HTTP_422_UNPROCESSABLE_ENTITY,
        content={"detail": exc.errors()},
    )

@app.exception_handler(HTTPException)
async def http_exception_handler(request, exc):
    logger.error(f"HTTPException: {exc.status_code} - {exc.detail}")
    return JSONResponse(
        status_code=exc.status_code,
        content={"detail": exc.detail},
    )

@app.exception_handler(Exception)
async def general_exception_handler(request, exc):
    logger.error(f"Unhandled exception: {exc}", exc_info=True)
    return JSONResponse(
        status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
        content={"detail": "An unexpected error occurred."},
    )

@app.on_event("startup")
async def startup_event():
    logger.info("Application is starting up")

@app.on_event("shutdown")
async def shutdown_event():
    logger.info("Application is shutting down")

if __name__ == "__main__":
    import uvicorn
    try:
        uvicorn.run(app, host="0.0.0.0", port=8000)
    except Exception as e:
        logger.critical(f"Fatal error: {e}", exc_info=True)
        sys.exit(1)