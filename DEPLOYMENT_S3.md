S3 deployment steps

1. Install the S3 Flysystem adapter locally or in CI:

```bash
composer require league/flysystem-aws-s3-v3
```

2. On Render (or your production host) set the environment variables in the service dashboard:

- `FILESYSTEM_DISK` = `s3`
- `AWS_ACCESS_KEY_ID` = <your key>
- `AWS_SECRET_ACCESS_KEY` = <your secret>
- `AWS_DEFAULT_REGION` = <your region, e.g. us-east-1>
- `AWS_BUCKET` = <your bucket name>
- (optional) `AWS_URL` = https://your-cdn-or-bucket-url

3. Deploy. Files uploaded with `->store(..., 'public')` will be written to S3 and `Storage::url(...)` will return the public URL.

4. Local development: keep `FILESYSTEM_DISK=local` in your local `.env` if you prefer local storage.
