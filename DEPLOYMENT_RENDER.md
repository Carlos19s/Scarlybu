Render persistent disk setup

1. Create a Persistent Disk in the Render dashboard and attach it to your service.
   - Note the mount path (example: `/mnt/storage`).

2. In Render Environment variables for your service, add:

```
FILESYSTEM_DISK=render_disk
RENDER_DISK_PATH=/mnt/storage
```

3. The Docker image already symlinks the mount into the app public folder at `/public/uploads` during container start. No extra commands required.

4. Optional: change the upload path or the symlink target in the Dockerfile if you prefer a different public path.

5. Deploy/rebuild the service. Uploaded files will be stored under the persistent disk and served from `/uploads/...`.

Notes
- Persistent Disk is not shared across different services — it's per-service.
- If you scale to multiple instances, prefer S3 or another shared storage provider.
