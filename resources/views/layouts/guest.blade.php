<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="VOXARA - Platform pembaca dokumen yang aksesibel untuk pengguna tunanetra dengan antarmuka suara dan dukungan Braille.">
    <title>@yield('title', 'VOXARA')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1B4F72',
                        secondary: '#2E86C1',
                        'light-bg': '#EBF5FB',
                        'text-primary': '#1A1A2E',
                        'text-secondary': '#4A4A6A',
                        success: '#1E8449',
                        danger: '#C0392B',
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full bg-light-bg text-text-primary font-sans">
    <div id="app">
        @yield('content')
    </div>
</body>
</html>
