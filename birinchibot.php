<?php

require_once 'db_connect.php';
include "Telegram.php";
$telegram = new Telegram('6465746893:AAE519bwuK_ZMX4hnsxYbyUkhvtIEPAgKNU');

$text = $telegram->Text();
$chat_id = $telegram->ChatID();
$user = $telegram->FirstName();

$data = $telegram->getData();
$message = $data['message'];
$phone = $message["contact"]["phone_number"];

$telegram->sendMessage(array('chat_id'=>$chat_id, 'text'=>json_encode($data['message']['location'], JSON_PRETTY_PRINT)));

$capacities = ["1kg - 50000 so'm", "1.5kg(1L) - 75000 so'm", "4.5kg(3L) - 220000 so'm", "7.5kg(5L) - 370000 so'm"];

switch($text){
    case '/start':
        showStart();
        break;
    case "🍯Batafsil ma'lumot":
        showAbout();
        break;
    case "🍯Buyurtma berish":
        showOrder();
        break;
    case "✈️Yetkazib berish✈":
        showDelivery();
        break;
    case "🔝asosiy menyu":
        showStart();
        break;
    case "↪️orqaga↪️":
        switch(file_get_contents('users/step.txt')){
            case 'order':
                showStart();
                break;
            case 'phone':
                showOrder();
                break;
            case 'deliveryTypes':
                askContact();
                break;
            case 'delivery':
                showDeliveryTypes();
                break;
        }
    default:
        if (in_array($text, $capacities)) {
            file_put_contents("users/massa.txt", $text);
            askContact();
        } elseif(is_numeric(substr($phone, 1, strlen($phone)-1))) {
            switch (file_get_contents('users/phone.txt')){
                case "phone":
                    if ($phone != "") {
                        file_put_contents("users/phone.txt", $phone);
                    }
                    showDeliveryTypes();
                    break;
            }
        }
        break;
}

function showStart(){
    global $telegram, $chat_id, $user;

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

function showOrder()
{
    global $telegram, $chat_id;

    file_put_contents('users/step.txt', 'order');

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

function askContact()
{
    global $telegram, $chat_id;

    file_put_contents("users/step.txt", 'phone');
    file_put_contents("users/phone.txt", "phone");

    $request_contact = array(
        array($telegram->buildKeyboardButton("Raqamni jo'natish", $request_contact = true)),
        array($telegram->buildKeyboardButton("↪️orqaga↪️"), $telegram->buildKeyboardButton("🔝asosiy menyu"))
    );
    $keyb = $telegram->buildKeyboard($request_contact, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' =>  $keyb,
        'text' => '<b>Hajm tanlandi, endi 📞telefon raqamingizni kiritsangiz!</b>',
        'parse_mode' => 'html'
    );
    $telegram->sendMessage($content);
}

function showDeliveryTypes(){
    global $telegram, $chat_id;

    file_put_contents('users/step.txt', 'deliveryTypes');

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

function showDelivery(){
    global $telegram, $chat_id;

    file_put_contents('users/step.txt', 'delivery');

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

function getLocation(){
    global $telegram, $chat_id;
    file_put_contents('step.txt', 'location');
}
testDb();
function testDb(){
    global $db;
    $result = $db->query("SELECT * From `users`");
    while ($arr=$result->fetch_assoc()){
        var_dump($arr);
        if (isset($arr['data_json'])){
//            print $arr['data_json'];
            print "<br>";
        }
    }

}