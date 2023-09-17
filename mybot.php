<?php
include "Telegram.php";
require_once "user.php";
$telegram = new Telegram('6566224733:AAGO-lZR3AJHUDZeC4dawiwS3uxSotmFbEM');

$admin_id = "872530082";

$chat_id = $telegram->ChatID();
$text = $telegram->Text();
$user = $telegram->FirstName();

$data = $telegram->getData();

$message = $data['message'];

$capacities = ["1kg - 50000 so'm", "1.5kg(1L) - 75000 so'm", "4.5kg(3L) - 220000 so'm", "7.5kg(5L) - 370000 so'm"];

//sendMessageJson();
if ($text == "/start" || $text == "🔝asosiy menyu"){
    showMain();
}
switch (getPage($chat_id)){
    case 'main':
        if ($text == "🍯Batafsil ma'lumot"){
            showAbout();
        } elseif ($text == "🍯Buyurtma berish"){
            showOrder();
        } else {
            chooseButton();
        }
        break;
    case 'massa':
        if (in_array($text, $capacities)){
            setMass($chat_id, $text);
            showPhone();
        } elseif ($text == "↪️orqaga↪️"){
            showMain();
        } else {
            chooseButton();
        }
        break;
    case 'phone':
        $phone = $message["contact"]["phone_number"];
        if ($phone != ""){
            setPhone($chat_id, $phone);
            showDeliveryTypes();
        } elseif ($text == "↪️orqaga↪️"){
            showOrder();
        } elseif(is_numeric(substr($text, 1, strlen($text)-1)) && $text[0]=="+") {
            setPhone($chat_id, $text);
            showDeliveryTypes();
        } else {
            checkPhone();
        }
        break;
    case 'delivery':
        if ($text == "✈️Yetkazib berish✈"){
            showLocation();
        } elseif ($text = '🍯 Borib olish 🍯'){
            showReady();
        } elseif ($text == "↪️orqaga↪️"){
            showPhone();
        } else {
            chooseButton();
        }
        break;
    case 'location':
        if ($message['location']['latitude'] != ""){
            setLatitude($chat_id, $message['location']['latitude']);
            setLongitude($chat_id, $message['location']['longitude']);
            showReady();
        } elseif ($text == "Lokatsiya jo'nata olmayman"){
            showReady();
        } elseif ($text == "↪️orqaga↪️"){
            showDeliveryTypes();
        } else {
            chooseButton();
        }
        break;
    case 'ready':
        if ($text == "Boshqa buyurtma berish"){
            showMain();
        }
        break;

}

function showMain(){
    global $telegram, $chat_id, $user;

    setPage($chat_id, 'main');

    $opt_category = array(
        array($telegram->buildKeyboardButton("🍯Batafsil ma'lumot")),
        array($telegram->buildKeyboardButton("🍯Buyurtma berish"))
    );
    $keyb = $telegram->buildKeyBoard($opt_category, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id,
        'reply_markup' => $keyb,
        'text' => "<b>Salom $user. Botimizga xush kelibsiz!. Ushbu bot orqali siz BeeO asal-arichilik firmasidan tabiiy asal va asal mahsulotlarini sotib olishingiz mumkin</b>",
        'parse_mode' => 'html'
    );
    $telegram->sendMessage($content);
}

function showAbout()
{
    global $telegram, $chat_id;
    $content = array('chat_id' => $chat_id,
        'text' => "<b>Biz haqimizda ma'lumot. </b><a href='https://telegra.ph/Biz-haqimizda-09-11'>Havola</a>",
        'parse_mode' => 'html');
    $telegram->sendMessage($content);
}

function chooseButton(){
    global $telegram, $chat_id;
    $content = array('chat_id' => $chat_id, 'text' => "<b>Iltimos quyidagi tugmalardan birini tanlang</b>", "parse_mode" => "html");
    $telegram->sendMessage($content);
}

function showOrder()
{
    global $telegram, $chat_id;

    setPage($chat_id, 'massa');

    $opt_capacity = array(
        array($telegram->buildKeyboardButton("1kg - 50000 so'm"), $telegram->buildKeyboardButton("1.5kg(1L) - 75000 so'm")),
        array($telegram->buildKeyboardButton("4.5kg(3L) - 220000 so'm"),($telegram->buildKeyboardButton("7.5kg(5L) - 370000 so'm"))),
        array($telegram->buildKeyboardButton("↪️orqaga↪️"))
    );
    $keyb = $telegram->buildKeyBoard($opt_capacity, $onetime = false, $resize = true);
    $telegram->sendPhoto(['chat_id'=>$chat_id,
        'photo'=>'https://zomin-asali.uz/wp-content/uploads/2021/12/honey-photo.jpg',
        "reply_markup" => $keyb,
        'caption'=> "<b>Buyurtma berish uchun hajmlardan birini tanlang yoki o'zingiz hohlagan hajmni kiriting</b>", 'parse_mode'=>'html' ]);

}

function showPhone()
{
    global $telegram, $chat_id;

    setPage($chat_id, 'phone');

    $request_contact = array(
        array($telegram->buildKeyboardButton("Raqamni jo'natish", $request_contact = true)),
        array($telegram->buildKeyboardButton("↪️orqaga↪️"), $telegram->buildKeyboardButton("🔝asosiy menyu"))
    );
    $keyb = $telegram->buildKeyboard($request_contact, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' =>  $keyb,
        'text' => "<b>Hajm tanlandi, Iltimos endi pastdagi tugmacha orqali raqamingizni jo'nating yoki o'zingiz +998------- ko'rinishida raqamingizni kiriting! </b>",
        'parse_mode' => 'html'
    );
    $telegram->sendMessage($content);
}

function checkPhone(){
    global $telegram, $chat_id;
    $content = array('chat_id' => $chat_id, 'text' => "<b>Iltimos telefon raqamni to'g'riligini tekshiring!</b>", "parse_mode" => "html");
    $telegram->sendMessage($content);
}

function showDeliveryTypes(){
    global $telegram, $chat_id;

    setPage($chat_id, 'delivery');

    $deliveryType = array(
        array($telegram->buildKeyboardButton("✈️Yetkazib berish✈")),
        array($telegram->buildKeyboardButton("🍯 Borib olish 🍯")),
        array($telegram->buildKeyboardButton("↪️orqaga↪️"), $telegram->buildKeyboardButton("🔝asosiy menyu"))
    );
    $keyb = $telegram->buildKeyBoard($deliveryType, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id,'photo'=>'https://img.freepik.com/free-vector/delivery-staff-ride-motorcycles-shopping-concept_1150-34879.jpg?w=360&t=st=1694688786~exp=1694689386~hmac=b62ba96ba60dff5d3acf522b2d4658c098269673b447f4977e273bde4416a3be',
        "reply_markup" => $keyb,
        'caption' => "<b>Bizda Toshkent shahri bo'ylab yetkazib berish xizmati mavjud. Yoki o'zingiz tashrif buyurib olib ketishingiz mumkin. Manzil: Toshkent sh. Olmazor tum. Talabalar shaharchasi,</b>",
        'parse_mode'=> 'html');
    $telegram->sendPhoto($content);
}

function showLocation(){
    global $telegram, $chat_id;

    setPage($chat_id, 'location');

    $location = array(
        array($telegram->buildKeyboardButton("Lokatsiya jo'natish", false, true)),
        array($telegram->buildKeyboardButton("Lokatsiya jo'nata olmayman")),
        array($telegram->buildKeyboardButton("↪️orqaga↪️"),$telegram->buildKeyboardButton("🔝asosiy menyu"))
    );
    $keyb = $telegram->buildKeyBoard($location, $onetime = false, $resize = true);
    $animation = array('chat_id' => $chat_id,
        "reply_markup" => $keyb,
        'photo' => 'https://www.androidauthority.com/wp-content/uploads/2015/07/location_marker_gps_shutterstock-1000x673.jpg.webp',
        'caption' => "<b>Yaxshi, endi lokatsiya jo'nating</b>",
        'parse_mode'=> 'html');
    $telegram->sendPhoto($animation);
}

function showReady(){
    global $telegram, $chat_id, $admin_id;

    setPage($chat_id, 'ready');

    $option = array(
        array($telegram->buildKeyboardButton("Boshqa buyurtma berish"))
    );
    $keyb = $telegram->buildKeyBoard($option, false, true);
    $content = ["chat_id"=>$chat_id, 'reply_markup' => $keyb,
        'text' => "<b>Sizning Buyurtmangiz qabul qilindi. Tez orada siz bilan bog'lanamiz. Murojaatingiz uchun raxmat!</b>",
        "parse_mode" => 'html'];
    $telegram->sendMessage($content);

//    send admin
    $text = "Yangi buyurtma keldi\n";
    $text .= "Hajm: ".getMass($chat_id);
    $text .= "\n";
    $text .= "Telefon raqam: ".getPhone($chat_id);
    $text .= "\n";

    $content = ["chat_id"=>$admin_id, 'text' => "<b>".$text."</b>", "parse_mode" => 'html'];
    $telegram->sendMessage($content);

    if (getLatitude($chat_id) != ""){
        $content = ["chat_id"=>$admin_id, 'latitude'=>getLatitude($chat_id), "longitude" => getLatitude($chat_id)];
        $telegram->sendLocation($content);
    }
}