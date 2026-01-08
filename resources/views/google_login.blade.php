<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - HireMe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 h-screen flex items-center justify-center">

    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md text-center transform transition-all hover:shadow-2xl">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-blue-600 mb-2 tracking-wide">HireMe <span class="text-2xl">🚀</span></h1>
            <p class="text-gray-500 text-sm">بوابتك الأولى نحو وظيفة المستقبل</p>
        </div>

        <div class="space-y-4">

            <a href="{{ route('social.login', 'google') }}"
               class="flex items-center justify-center w-full bg-white border border-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl shadow-sm hover:bg-gray-50 transition duration-300 transform hover:-translate-y-1 group">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-6 h-6 ml-3 group-hover:scale-110 transition-transform" alt="Google">
                <span>المتابعة باستخدام Google</span>
            </a>

            <a href="{{ route('social.login', 'facebook') }}"
               class="flex items-center justify-center w-full bg-[#1877F2] text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:bg-[#166fe5] transition duration-300 transform hover:-translate-y-1">
                <i class="fa-brands fa-facebook text-xl ml-3"></i>
                <span>المتابعة باستخدام Facebook</span>
            </a>

            <a href="{{ route('social.login', 'github') }}"
               class="flex items-center justify-center w-full bg-[#24292e] text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:bg-[#2f363d] transition duration-300 transform hover:-translate-y-1">
                <i class="fa-brands fa-github text-xl ml-3"></i>
                <span>المتابعة باستخدام GitHub</span>
            </a>

        </div>

        <div class="mt-8 relative flex py-3 items-center">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="flex-shrink-0 mx-4 text-gray-400 text-xs">نظام تسجيل آمن ومشفر</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

    </div>

</body>
</html>
