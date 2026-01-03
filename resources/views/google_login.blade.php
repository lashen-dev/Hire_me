<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - HireMe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96 text-center">
        <h1 class="text-3xl font-bold text-blue-600 mb-2">HireMe 🚀</h1>
        <p class="text-gray-500 mb-8">سجل دخولك وابحث عن وظيفة أحلامك</p>

        <a href="{{ route('google.login') }}" 
           class="flex items-center justify-center w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-4 rounded shadow-sm transition duration-300 ease-in-out transform hover:scale-105">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-6 h-6 ml-3" alt="Google Logo">
            <span>تسجيل الدخول بجوجل</span>
        </a>

        <div class="mt-6 border-t pt-4">
            <p class="text-xs text-gray-400">تجربة نظام Socialite للتسجيل الآمن</p>
        </div>
    </div>

</body>
</html>