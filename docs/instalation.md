### Permission

- Do permission to scripts in the scripts folder
```bash
chmod +x scripts/*.sh
```
- Do permission to start.sh in the root folder
```bash
chmod +x start.sh
```

## Build Image
- Build the image

```bash
docker compose build --build-arg DEV_ENV=true --no-cache 
```
- Build the image without dev dependencies
```bash
docker compose build --build-arg DEV_ENV=false --no-cache
```

## Run the image
```bash
docker-compose up -d
```

## Wait for the container to be up, check progress with
```bash
docker compose logs checkprice
```