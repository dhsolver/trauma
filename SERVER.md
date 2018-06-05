# Amazon Web Services Configuration

## EC2
Server is hosted on AWS EC2 instance and we have a single instance for both live and staging.

Connect to the server with the .pem file over SSH. There are two directories under home (~) directory; trauma_live and trauma_staging that hosts live and staging servers separately.

## Amazon Certificate Manager
Site certificate is issued and managed by ACM.

## Elastic Load Balancer
Currently we have only one instance, but ELB is needed to host the certificate issued by ACM. It listens both 80 and 443 ports and forwards the traffic to the instance.

## Route 53
We use Route 53 to manage DNS records. We have two A records (@ and staging) that point to the ELB.

## S3
All user files (profile photo, course documents) are uploaded to S3. There are 3 buckets that stores live/staging/local files respectively.

## Identity and Access Management
Currently IAM access key is used only to upload files to S3 bucket. When you create a new access key, make sure to keep the .env files updated.
