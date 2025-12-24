<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>رمز التحقق</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f6f6f6; padding: 20px;">

    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">
        
        <h2 style="color: #333;">مرحباً بك في HireMe 👋</h2>
        
        <p style="color: #666; font-size: 16px;">
            شكراً لتسجيلك معنا. لإكمال عملية التسجيل، يرجى استخدام رمز التحقق التالي:
        </p>

        <div style="background-color: #4F46E5; color: #fff; font-size: 32px; font-weight: bold; letter-spacing: 5px; padding: 15px; margin: 20px 0; border-radius: 5px;">
            {{ $code }}
        </div>

        <p style="color: #999; font-size: 14px;">
            هذا الرمز صالح لمدة 10 دقائق فقط.<br>
            إذا لم تطلب هذا الرمز، يمكنك تجاهل هذه الرسالة.
        </p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        
        <p style="font-size: 12px; color: #aaa;">
            &copy; {{ date('Y') }} HireMe Platform. All rights reserved.
        </p>
    </div>

</body>
</html>