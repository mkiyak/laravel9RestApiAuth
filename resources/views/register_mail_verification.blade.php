<div style="text-align: center;">Sayın <strong>{{$name}}</strong>, <br>

    Sisteme girişiniz için lütfen epostanızı onaylayınız.<br>

    <div style="margin: 30px;">
    <a href="{{ route("user-verification", $email) }}" style="padding: 15px 30px; background-color: #333333; color: #ffffff;">Eposta Onayla</a>
    </div>

    <br>

    Butona tıklanma konusunda sorun yaşıyorsunanız bu linke tıklayınız veya linki tarayıcınızda açınız. <a href="{{ route("user-verification", $email) }}">{{ route("user-verification", $email) }}</a>
</div>
