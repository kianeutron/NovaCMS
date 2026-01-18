<?php

namespace NovaCMS\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case AUTHOR = 'author';
    case VIEWER = 'viewer';
}

