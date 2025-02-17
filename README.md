# ChatBot

Bu proje, [OpenRouter.ai](https://openrouter.ai/) hizmetini kullanarak bir ChatBot uygulaması örneğidir. Kullanıcının tarayıcı üzerinden sorduğu sorular, PHP aracılığıyla OpenRouter API'ına iletilir ve gelen yanıtlar ekranda gösterilir. Bu örnekte [Qwen/Qwen2.5-vl-72b-instruct:free](https://openrouter.ai/docs/models) modeli kullanılmaktadır.

## Özellikler

- **Gerçek zamanlı sohbet**: Kullanıcının yazdığı sorular `fetch` ile sunucuya gönderilir.  
- **Kolay entegrasyon**: Tek bir PHP dosyasında hem API çağrısı hem de arayüz kodu bulunur.  
- **Bootstrap**: Basit bir arayüz ve responsive tasarım sağlamak için Bootstrap kütüphanesi eklenmiştir.

## Kurulum

1. **Proje dosyalarını indirin veya kopyalayın**  
   ```bash
   git clone https://github.com/kullaniciadi/patoloji-chatbot.git
   cd patoloji-chatbot
   ```

2. **API Anahtarını ekleyin**  
   - `index.php` (veya ilgili dosya adı) içinde `$apiKey` değişkenini kendi OpenRouter API anahtarınızla güncelleyin.  
   - Örnek:  
     ```php
     $apiKey = 'sk-or-v1-****'; // Burayı kendi geçerli anahtarınızla değiştirin
     ```

3. **Model Adını Doğrulayın (Opsiyonel)**  
   - Varsayılan olarak `$modelName = 'qwen/qwen2.5-vl-72b-instruct:free'` kullanılmıştır.  
   - İsteğe göre başka bir model kullanmak isterseniz `$modelName` değerini güncelleyin.

4. **Sunucu Ortamı**  
   - Yerel bir PHP sunucusu veya paylaşılmış bir hosting kullanabilirsiniz.  
   - Projenin çalışması için PHP (7.4+), cURL ve JSON uzantılarına ihtiyaç duyulur.  
   - PHP’yi yerelde çalıştırmak için:  
     ```bash
     php -S localhost:8000
     ```  
     Komutunu kullanabilir ve tarayıcınızdan [http://localhost:8000/](http://localhost:8000/) adresine gidebilirsiniz.

5. **Referer Başlığı (Opsiyonel)**  
   - `curl_setopt` ayarlarında `'HTTP-Referer: https://www.patoloji.com.tr'` şeklinde bir örnek kullanılmaktadır.  
   - Kendi domain’inize göre güncellemek isterseniz `'HTTP-Referer'` başlığını değiştirebilirsiniz.

## Kullanım

1. **Ana sayfayı tarayıcıda açın.**  
   - Örneğin, `http://localhost:8000/` veya sunucunuzdaki alan adını kullanın.

2. **Soru sorun**  
   - Sayfadaki metin kutusuna sorunuz veya isteğinizi yazın ve **Gönder** butonuna basın.

3. **Yanıtı görüntüleyin**  
   - Sorduğunuz sorunun altında asistanın yanıtı görüntülenir.  
   - Yeni bir soru sormak için metin kutusunu tekrar kullanabilirsiniz.

## API Yanıtı Mantığı

- `POST` yöntemiyle tarayıcıdan gelen `messages` verisi alınıyor.
- cURL ile [OpenRouter.ai](https://openrouter.ai/docs) API'ına `model` ve `messages` değerleri gönderiliyor.
- Gelen yanıt JSON olarak çözülüp, `choices[0].message.content` içinden sohbet cevabı alınarak ekranda gösteriliyor.

## Önemli Notlar

- Proje örnek amacıyla hazırlanmıştır. Gerçek ortamlarda API anahtarınızı, gizli bilgilerinizi güvenli bir şekilde saklamayı unutmayın.
- Demo uygulama olduğu için ek güvenlik önlemleri (ör. CORS, giriş kontrolü, rate limit vb.) varsayılan olarak eklenmemiştir.

## Katkıda Bulunmak

- Hata bildirmek, yeni özellik önermek veya katkı sağlamak isterseniz lütfen bir [Issue](../../issues) veya [Pull Request](../../pulls) açın.

## Lisans

Bu proje MIT Lisansı altında lisanslanmıştır.
