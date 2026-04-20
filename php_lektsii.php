<?php

echo "Задание 1: Типы переменных<br>";
$intVar = 6;
$floatVar = 6.66;
$stringVar = "Привет!";
$boolVar = true;
$arrayVar = [1, 2, 3];

echo "Тип $intVar: " . gettype($intVar) . "<br>";
echo "Тип $floatVar: " . gettype($floatVar) . "<br>";
echo "Тип $stringVar: " . gettype($stringVar) . "<br>";
echo "Тип $boolVar: " . gettype($boolVar) . "<br>";
echo "Тип $arrayVar: " . gettype($arrayVar) . "<br><br>";

echo "Задание 2: Арифметическое действие<br>";
$num1 = 9;
$num2 = 6;
$result = $num1 + $num2;
echo "Сумма $num1 и $num2: $result<br><br>";

echo "Задание 3: Конкатенация строк<br>";
$text1 = "При";
$text2 = "вет!";
$concatenated = $text1 . $text2;
echo "Результат конкатенации: $concatenated<br><br>";

echo "Задание 4: Тернарный оператор<br>";
$varA = 6;
$varB = 9;
$comparisonResult = ($varA > $varB) ? "Первое число больше" : "Второе число больше";
echo "Сравнение $varA и $varB: $comparisonResult<br>";

echo "<br>Задание 1<br>";
$age = 23;
if ($age < 18) {
    echo 'Слишком молод<br>';
} elseif ($age <= 35) {
    echo 'Счастливчик!<br>';
} else {
    echo 'Не повезло<br>';
}

echo "<br>Задание 2<br>";
$arr = array();
for ($i = 1; $i <= 100; $i++) {
    if ($i % 2 == 0) {
        $arr[] = $i;
    }
}
foreach ($arr as $key => $value) {
    if ($value % 5 == 0) {
        echo $value . " ";
    }
}
echo "<br>";

echo "<br>Задание 3<br>";
$dict['Name'] = 'Мария';
$dict['Address'] = 'ул. Ленина';
$dict['Phone'] = '+7999999999';
$dict['Mail'] = 'mariwpm@pochta.ru';

foreach ($dict as $index => $value) {
    echo "Элемент $index имеет значение: $value<br>";
}

echo "<br>Задание 1<br>";
$str1 = 'php';
echo strtoupper($str1) . "<br><br>";

echo "Задание 2<br>";
$str2 = 'london';
echo ucfirst($str2) . "<br><br>";

echo "Задание 3<br>";
$str3 = 'London';
echo strtolower($str3) . "<br><br>";

echo "Задание 4<br>";
$str4 = 'html css php';
echo "Количество символов: " . strlen($str4) . "<br><br>";

echo "Задание 5<br>";
$Spassword = 'admin123';
if (strlen($Spassword) > 5 && strlen($Spassword) < 10) {
    echo 'Пароль подходит<br><br>';
} else {
    echo 'Нужно придумать другой пароль<br><br>';
}

echo "Задание 6<br>";
$str6 = 'image.png';
if (substr($str6, -4) === '.png') {
    echo 'Да<br><br>';
} else {
    echo 'Нет<br><br>';
}

echo "Задание 7<br>";
$dateStr = '31.12.2013';
$dateStr = str_replace('.', '-', $dateStr);
echo $dateStr . "<br><br>";

echo "Задание 8<br>";
$str8 = 'abc';
$str8 = str_replace('a', '1', $str8);
$str8 = str_replace('b', '2', $str8);
$str8 = str_replace('c', '3', $str8);
echo $str8 . "<br><br>";

echo "Задание 9<br>";
$str9 = '1a2b3c4b5d6e7f8g9h0';
$digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
$str9 = str_replace($digits, '', $str9);
echo $str9 . "<br><br>";

echo "Задание 10<br>";
$str10 = 'abc abc abc';
$posFirstB = strpos($str10, 'b');
echo "Позиция первой буквы 'b': $posFirstB<br><br>";

echo "Задание 11<br>";
$str11 = 'abc abc abc';
$posLastB = strrpos($str11, 'b');
echo "Позиция последней буквы 'b': $posLastB<br><br>";

echo "<br>Задание 1<br>";
$arrayForSquares = [1, 2, 3, 4, 5];
$sumOfSquares = 0;
foreach ($arrayForSquares as $element) {
    $sumOfSquares += $element * $element;
}
echo "Сумма квадратов элементов массива " . implode(", ", $arrayForSquares) . " равна: $sumOfSquares<br><br>";

echo "<br>Задание 2<br>";
$arrayForCondition = [1, 15, 3, 8, 12, 5, -2, 9];
$sumOfFilteredElements = 0;
foreach ($arrayForCondition as $element) {
    if ($element > 0 && $element < 10) {
        $sumOfFilteredElements += $element;
    }
}
echo "Сумма элементов массива " . implode(", ", $arrayForCondition) . ", которые больше 0 и меньше 10, равна: $sumOfFilteredElements<br><br>";

echo "<br>Задание 3<br>";
$string = 'abcde';
$letterArray = str_split($string);
echo "Исходная строка: '$string'<br>";
echo "Полученный массив букв: ";
print_r($letterArray);
echo "<br><br>";

echo "<br>Задание 4<br>";
$urlsArray = [
    'http://example.com',
    'https://primer.com',
    'http://test.org',
    'ftp://site.com',
    'http://localhost',
    'www.google.com'
];

$filteredUrls = array_filter($urlsArray, function($url) {
    return strpos($url, 'http://') === 0;
});
echo "Исходный массив URL:<br>";
print_r($urlsArray);
echo "<br>Отфильтрованный массив (только 'http://'):<br>";
print_r($filteredUrls);
echo "<br><br>";

echo "<br>Задание 5<br>";
$array = [1, 2, 3, 4, 5, 6];
$length = count($array);
$halfLength = intdiv($length, 2);
$sumFirstHalf = 0;

for ($i = 0; $i < $halfLength; $i++) {
    $sumFirstHalf += $array[$i];
}

echo "Исходный массив: ";
print_r($array);
echo "<br>Сумма первой половины элементов: $sumFirstHalf<br><br>";
echo "<br>Задание 6<br>";
$sumSecondHalf = 0;

for ($i = $halfLength; $i < $length; $i++) {
    $sumSecondHalf += $array[$i];
}
echo "Сумма второй половины элементов: $sumSecondHalf<br>";

if ($sumSecondHalf != 0) {
    $result = $sumFirstHalf / $sumSecondHalf;
    echo "Результат деления суммы первой половины на сумму второй половины: $result<br><br>";
} else {
    echo "Ошибка: деление на ноль невозможно (сумма второй половины равна 0).<br><br>";
}

?>