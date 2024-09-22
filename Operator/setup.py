from setuptools import setup, find_packages

with open('requirements.txt') as f:
    required = f.read().splitlines()

setup(
    name="operator_app",
    version="0.1.0",
    packages=find_packages(where="src"),
    package_dir={"": "src"},
    install_requires=[
        'annotated-types==0.6.0',
        'anyio==4.3.0',
        'certifi==2024.2.2',
        'click==8.1.7',
        'fastapi==0.110.2',
        'h11==0.14.0',
        'httpcore==1.0.5',
        'httpx==0.27.0',
        'idna==3.7',
        'iniconfig==2.0.0',
        'packaging==24.0',
        'pigpio==1.78',
        'pluggy==1.5.0',
        'pydantic==2.7.1',
        'pydantic_core==2.18.2',
        'pytest==8.1.2',
        'pytest-asyncio==0.23.6',
        'sniffio==1.3.1',
        'starlette==0.37.2',
        'typing_extensions==4.11.0',
        'uvicorn==0.29.0',
        'charset-normalizer==3.3.2',
        'pn532pi==1.5',
        'pyserial==3.5',
        'requests==2.31.0',
        'spidev==3.6',
        'typing==3.7.4.3',
        'urllib3==2.2.1'
    ],
)
