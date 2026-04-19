<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ChromaDB Vector Database
    |--------------------------------------------------------------------------
    */
    'chroma' => [
        'host' => env('CHROMA_HOST', 'http://localhost:8000'),
    ],

    /*
    |--------------------------------------------------------------------------
    | LLM (Large Language Model) Configuration
    |--------------------------------------------------------------------------
    */
    'llm' => [
        'api_url' => env('LLM_API_URL', 'https://api.openai.com/v1/chat/completions'),
        'api_key' => env('LLM_API_KEY', ''),
        'model' => env('LLM_MODEL', 'gpt-4o-mini'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Embedding Model Configuration
    |--------------------------------------------------------------------------
    */
    'embedding' => [
        'url' => env('EMBEDDING_API_URL', 'https://api.openai.com/v1/embeddings'),
        'model' => env('EMBEDDING_MODEL', 'text-embedding-3-small'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google OAuth
    |--------------------------------------------------------------------------
    */
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

];
