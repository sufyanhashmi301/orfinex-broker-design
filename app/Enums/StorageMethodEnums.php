<?php

namespace App\Enums;

enum StorageMethodEnums: string
{
    const FILESYSTEM = 'filesystem';
    const AWS_S3 = 'aws_amazon_s3';
}